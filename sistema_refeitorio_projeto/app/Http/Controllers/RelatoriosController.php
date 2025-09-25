<?php

namespace App\Http\Controllers;

use App\Exports\ComprasExport;
use App\Exports\FinanceiroExport;
use App\Exports\InventarioExport;
use App\Exports\ProdutoVencimentoExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovimentacoesExport;
use App\Exports\NivelReposicaoExport;
use App\Models\tb_lote;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class RelatoriosController extends Controller
{
    public function redirecionamento(Request $request)
    {
        $tipo = $request->input('tipo_relatorio');
        switch ($tipo) {
            case 'inventario':
                return redirect()->route('relatorioInventario');
            case 'movimentacao':
                $dado = $request->validate([
                    'periodo_estoque' => 'required'
                ], [
                    'periodo_estoque.required' => 'O periodo é obrigatorio'
                ]);
                return redirect()->route('relatorioMovimentacao', ['data' => Crypt::encrypt($dado['periodo_estoque'])]);
            case 'vencido':
                return redirect()->route('relatorioVencido');
            case 'nivel':
                return redirect()->route('relatorioNivel');
            case 'compra':
                $dado = $request->validate([
                    'periodo_financeiro' => 'required'
                ], [
                    'periodo_financeiro.required' => 'O periodo é obrigatorio'
                ]);
                return redirect()->route('relatorioCompra', ['data' => Crypt::encrypt($dado['periodo_financeiro'])]);
            case 'financeiro':
                $dado = $request->validate([
                    'periodo_financeiro' => 'required'
                ], [
                    'periodo_financeiro.required' => 'O periodo é obrigatorio'
                ]);
                return redirect()->route('relatorioFinanceiro', ['data' => Crypt::encrypt($dado['periodo_financeiro'])]);
        }
    }
    public function inventario()
    {
        $produtos = DB::table('tb_produtos as p')
            ->leftJoin('tb_marca_produto as mp', 'p.idProdutos', '=', 'mp.produto_id')
            ->leftJoin('tb_marca as m', 'mp.marca_id', '=', 'm.idMarca')
            ->select(
                'p.idProdutos',
                'p.pesoLiquido',
                'p.nomeProduto',
                'p.qtdEstoque',
                'p.tamanho',
                'p.UnidadeMedida',
                'm.nomeMarca'
            )
            ->orderBy('p.nomeProduto', 'asc')
            ->whereNull('p.deleted_at')
            ->get()
            ->map(function ($item) {
                $arr = (array) $item;
                $arr['lotes'] = DB::table('tb_lotes')
                    ->where('idProdutos', $arr['idProdutos'])
                    ->where('qtdLote', '>', 0)
                    ->orderBy('dataValidade', 'asc')
                    ->get()
                    ->map(fn($i) => (array) $i)
                    ->toArray();
                return $arr;
            })
            ->toArray();

        return view('relatorios.relatorio_inventario', compact('produtos'));
    }

    public function InventarioExport($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        return Excel::download(new InventarioExport($produtos), "inventario_$data.xlsx");
    }

    public function InventarioPDF($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdfs.relatorio_inventario_pdf', ['dados' => ['produtos' => $produtos]]);
        return $pdf->download("relatorio_inventario_$data.pdf");
    }
    public function movimentacao($data)
    {
        $dia = $this->descriptografar($data);
        $hoje = Carbon::now()->format('Y-m-d');

        // Texto do período
        if (Carbon::parse($dia)->isToday()) {
            $textoPeriodo = 'Hoje';
        } elseif (Carbon::parse($hoje)->diffInDays(Carbon::parse($dia)) < 0) {
            $diadiff = Carbon::parse($hoje)->diffInDays(Carbon::parse($dia)) * -1;
            $textoPeriodo = "Últimos {$diadiff} dias (desde " . Carbon::parse($dia)->format('d/m/Y') . ")";
        } else {
            $diadiff = Carbon::parse($hoje)->diffInDays(Carbon::parse($dia));
            $textoPeriodo = "Nos próximos {$diadiff} dias (desde " . Carbon::parse($dia)->format('d/m/Y') . ")";
        }

        // Entradas
        $entradas = DB::table('tb_entrada as e')
            ->join('tb_lotes as l', 'l.idLotes', '=', 'e.idLotes')
            ->join('tb_produtos as p', 'p.idProdutos', '=', 'l.idProdutos')
            ->leftJoin('tb_marca_produto', 'tb_marca_produto.produto_id', '=', 'p.idProdutos')
            ->leftJoin('tb_marca as m', 'm.idMarca', '=', 'tb_marca_produto.marca_id')
            ->leftJoin('tb_fornecedor as f', 'f.idFornecedor', '=', 'e.idFornecedor')
            ->select(
                'p.nomeProduto',
                'p.tamanho',
                'p.UnidadeMedida',
                'p.pesoLiquido',
                'm.nomeMarca',
                'f.nomeFornecedor',
                'e.qtdEntrada',
                'e.dataEntrada as data'
            )
            ->whereNull('p.deleted_at')
            ->when($dia, fn($query, $dataPesquisa) => $query->where('e.dataEntrada', '>=', $dataPesquisa))
            ->get();

        // Saídas (com fornecedor via lote → entrada)
        $saidas = DB::table('tb_saidas as s')
            ->join('tb_lotes as l', 'l.idLotes', '=', 's.idLotes')
            ->join('tb_entrada as e', 'e.idLotes', '=', 'l.idLotes') // fornecedor via entrada
            ->join('tb_produtos as p', 'p.idProdutos', '=', 'l.idProdutos')
            ->leftJoin('tb_marca_produto', 'tb_marca_produto.produto_id', '=', 'p.idProdutos')
            ->leftJoin('tb_marca as m', 'm.idMarca', '=', 'tb_marca_produto.marca_id')
            ->leftJoin('tb_fornecedor as f', 'f.idFornecedor', '=', 'e.idFornecedor')
            ->select(
                'p.nomeProduto',
                'p.tamanho',
                'p.UnidadeMedida',
                'p.pesoLiquido',
                'm.nomeMarca',
                'f.nomeFornecedor',
                's.qtdSaida',
                's.dataSaida as data'
            )
            ->whereNull('p.deleted_at')
            ->when($dia, fn($query, $dataPesquisa) => $query->where('s.dataSaida', '>=', $dataPesquisa))
            ->get();

        $movimentacoes = [];

        foreach ($entradas as $linha) {
            $chave = $linha->data
                . '|' . $linha->nomeProduto
                . '|' . ($linha->nomeMarca ?? '')
                . '|' . $linha->nomeFornecedor;

            $movimentacoes[$chave] = [
                'data'          => $linha->data,
                'produto'       => $linha->nomeProduto,
                'marca'         => $linha->nomeMarca,
                'fornecedor'    => $linha->nomeFornecedor,
                'tamanho'       => $linha->tamanho,
                'UnidadeMedida' => $linha->UnidadeMedida,
                'pesoLiquido'   => $linha->pesoLiquido,
                'qtdEntrada'    => (int) $linha->qtdEntrada,
                'qtdSaida'      => 0,
            ];
        }

        foreach ($saidas as $linha) {
            $chave = $linha->data
                . '|' . $linha->nomeProduto
                . '|' . ($linha->nomeMarca ?? '')
                . '|' . $linha->nomeFornecedor;

            if (isset($movimentacoes[$chave])) {
                $movimentacoes[$chave]['qtdSaida'] += (int) $linha->qtdSaida;
            } else {
                $movimentacoes[$chave] = [
                    'data'          => $linha->data,
                    'produto'       => $linha->nomeProduto,
                    'marca'         => $linha->nomeMarca ?? '',
                    'fornecedor'    => $linha->nomeFornecedor,
                    'tamanho'       => $linha->tamanho,
                    'UnidadeMedida' => $linha->UnidadeMedida,
                    'pesoLiquido'   => $linha->pesoLiquido,
                    'qtdEntrada'    => 0,
                    'qtdSaida'      => (int) $linha->qtdSaida,
                ];
            }
        }

        usort(
            $movimentacoes,
            fn($a, $b) =>
            $a['data'] === $b['data']
                ? strcmp($a['produto'], $b['produto'])
                : strcmp($a['data'], $b['data'])
        );

        return view('relatorios.relatorio_movimentacao', [
            'dados'         => [
                'produtos' => $movimentacoes,
                'texto_periodo' => $textoPeriodo
            ],
            'dia'           => $dia,
        ]);
    }

    public function MovimentacoesExport($movimentacoes)
    {
        $movimentacoes = $this->descriptografar($movimentacoes);
        $data = Carbon::now()->format('d-m-Y');
        return Excel::download(new MovimentacoesExport($movimentacoes), "movimentacoes_$data.xlsx");
    }
    public function MovimentacoesPDF($movimentacoes)
    {
        $movimentacoes = $this->descriptografar(($movimentacoes));
        $data = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdfs.relatorio_movimentacao_pdf', ['dados' => $movimentacoes]);
        return $pdf->download("relatorio_movimentacao_$data.pdf");
    }
    public function analiseVencimento()
    {
        $produtos = DB::table('tb_produtos')
            ->leftJoin('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->select(
                'tb_produtos.idProdutos',
                'tb_produtos.nomeProduto',
                'tb_produtos.qtdEstoque',
                'tb_produtos.tamanho',
                'tb_produtos.pesoLiquido',
                'tb_produtos.UnidadeMedida',
                'tb_marca.nomeMarca',
            )
            ->orderBy('tb_produtos.nomeProduto', 'asc')
            ->whereNull('tb_produtos.deleted_at')
            ->where('tb_produtos.qtdEstoque', '>', 0)
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();
        $lotes = tb_lote::where('qtdLote', '>', 0)->get()->toArray();

        $dadoEstruturado = [];

        foreach ($produtos as $produto) {
            $dadoEstruturado[$produto['idProdutos']] = $produto;
            $dadoEstruturado[$produto['idProdutos']]['lotes'] = [];
        }

        foreach ($lotes as $lote) {
            $idProduto = $lote['idProdutos'];

            if (isset($dadoEstruturado[$idProduto])) {
                $dadoEstruturado[$idProduto]['lotes'][] = $lote;
            }
        }

        return view('relatorios.relatorio_produtos_proximos_vencimento', ['dados' => ['produtos' => $dadoEstruturado]]);
    }

    public function analizseVencimentoExport($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        return Excel::download(new ProdutoVencimentoExport($produtos), "analise_vencimento_$data.xlsx");
    }
    public function analiseVencimentoPDF($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdfs.relatorio_produtos_proximos_vencimento_pdf', ['dados' => ['produtos' => $produtos]]);
        return $pdf->download("relatorio_analise_vencimento_$data.pdf");
    }
    public function compras(Request $request, $data = null)
    {

        $dia = $this->descriptografar($data);

        $diaCarbon = null;
        if ($dia) {
            try {
                if (is_numeric($dia) && strlen((string)$dia) >= 10) {
                    $diaCarbon = Carbon::createFromTimestamp((int)$dia)->startOfDay();
                } else {
                    $diaCarbon = Carbon::parse($dia)->startOfDay();
                }
            } catch (\Exception $e) {
                $diaCarbon = null;
            }
        }

        $hoje = Carbon::today();
        if ($diaCarbon) {
            $diff = $diaCarbon->diffInDays($hoje, false);
            if ($diff === 0) {
                $textoPeriodo = 'Hoje';
            } elseif ($diff > 0) {
                $textoPeriodo = "Últimos {$diff} dias (desde " . $diaCarbon->format('d/m/Y') . ")";
            } else {
                $textoPeriodo = "Nos próximos " . abs($diff) . " dias (até " . $diaCarbon->format('d/m/Y') . ")";
            }
        } else {
            $textoPeriodo = 'Período não informado';
        }

        $pesquisa = trim($request->input('pesquisa', ''));

        $query = DB::table('tb_entrada as e')
            ->select(
                DB::raw('DATE(e.dataEntrada) as data_entrada'),
                'p.pesoLiquido',
                'p.nomeProduto',
                'p.tamanho',
                'p.UnidadeMedida',
                'f.nomeFornecedor',
                'm.nomeMarca',
                DB::raw("GROUP_CONCAT(DISTINCT e.numeroNotaFiscal ORDER BY e.numeroNotaFiscal SEPARATOR ', ') AS notas"),
                DB::raw('SUM(e.qtdEntrada) as quantidade'),
                DB::raw('p.precoProduto as custo_unitario'),
                DB::raw('SUM(e.qtdEntrada * p.precoProduto) as total')
            )
            ->join('tb_lotes as l', 'l.idLotes', '=', 'e.idLotes')
            ->join('tb_produtos as p', 'p.idProdutos', '=', 'l.idProdutos')
            ->join('tb_fornecedor as f', 'f.idFornecedor', '=', 'e.idFornecedor')
            ->leftJoin('tb_marca_produto', 'p.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca as m', 'tb_marca_produto.marca_id', '=', 'm.idMarca')
            ->whereNull('p.deleted_at');

        if ($diaCarbon) {
            $query->whereDate('e.dataEntrada', '>=', $diaCarbon->toDateString());
        }

        if (!empty($pesquisa)) {
            $query->where(function ($q) use ($pesquisa) {
                $q->where('p.nomeProduto', 'like', "%{$pesquisa}%")
                    ->orWhere('e.numeroNotaFiscal', 'like', "%{$pesquisa}%")
                    ->orWhere('p.tamanho', 'like', "%{$pesquisa}%")
                    ->orWhere('f.nomeFornecedor', 'like', "%{$pesquisa}%");
            });
        }

        $produtos = $query
            ->groupBy(DB::raw('DATE(e.dataEntrada)'), 'p.idProdutos', 'f.idFornecedor', 'm.nomeMarca')
            ->orderBy(DB::raw('DATE(e.dataEntrada)'), 'desc')
            ->orderBy('p.nomeProduto')
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        return view(
            'relatorios.relatorio_compras',
            [
                'dados' => [
                    'produtos' => $produtos,
                    'texto_periodo' => $textoPeriodo
                ],
                'pesquisa' => $pesquisa,
                'data' => $dia ? Crypt::encrypt($dia) : null
            ]
        );
    }
    public function ComprasExport($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        return Excel::download(new ComprasExport($produtos), "compras_$data.xlsx");
    }
    public function ComprasPDF($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdfs.relatorio_compras_pdf', ['dados' => ['produtos' => $produtos]])
            ->setPaper('a4', 'landscape');
        return $pdf->download("relatorio_compras_$data.pdf");
    }
    public function nivelReposicao()
    {
        $hoje = Carbon::today()->toDateString();
        $produtos = DB::table('tb_produtos')
            ->leftJoin('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->join('tb_lotes', 'tb_lotes.idProdutos', '=', 'tb_produtos.idProdutos')
            ->select(
                'tb_produtos.idProdutos',
                'tb_produtos.nomeProduto',
                'tb_produtos.qtdEstoque',
                'tb_produtos.tamanho',
                'tb_produtos.pesoLiquido',
                'tb_produtos.UnidadeMedida',
                'tb_marca.nomeMarca',
                DB::raw('MIN(tb_lotes.dataValidade) as dataValidade')
            )
            ->orderBy('tb_produtos.nomeProduto', 'asc')
            ->whereNull('tb_produtos.deleted_at')
            ->groupBy(
                'tb_produtos.idProdutos',
                'tb_produtos.nomeProduto',
                'tb_produtos.qtdEstoque',
                'tb_produtos.tamanho',
                'tb_produtos.pesoLiquido',
                'tb_produtos.UnidadeMedida',
                'tb_marca.nomeMarca'
            )
            ->whereNull('tb_produtos.deleted_at')
            ->whereColumn('qtdEstoque', '<', 'qtdMinima')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        $lotes = tb_lote::where('dataValidade', '>=', $hoje)->orderBy('dataValidade', 'asc')->get()->toArray();

        $dadosEstruturados = [];

        foreach ($produtos as $produto) {
            $produto['lotes'] = [];

            foreach ($lotes as $lote) {
                if ($lote['idProdutos'] == $produto['idProdutos']) {
                    $produto['lotes'][] = $lote;
                }
            }

            $dadosEstruturados[] = $produto;
        }

        return view('relatorios.relatorio_nivel_reposicao', ['dados' => ['produtos' => $dadosEstruturados]]);
    }
    public function NivelReposicaoExport($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        return Excel::download(new NivelReposicaoExport($produtos), "nivel_reposicao_$data.xlsx");
    }
    public function NivelReposicaoPDF($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdfs.relatorio_nivel_reposicao_pdf', ['dados' => ['produtos' => $produtos]]);
        return $pdf->download("relatorio_nivel_reposicao_$data.pdf");
    }
    public function financeiro(Request $request,$data)
    {

        $pesquisa = trim($request->input('pesquisa', ''));

        $dia = $this->descriptografar($data);

        $hoje = Carbon::now()->format('Y-m-d');
        if (Carbon::parse($dia)->isToday()) {
            $textoPeriodo = 'Hoje';
        } elseif (Carbon::parse($hoje)->diffInDays(Carbon::parse($dia)) < 0) {
            $diadiff = Carbon::parse($hoje)->diffInDays(Carbon::parse($dia));
            $diadiff *= -1;
            $textoPeriodo = "Últimos {$diadiff} dias (desde " . Carbon::parse($dia)->format('d/m/Y') . ")";
        } else {
            $diadiff = Carbon::parse($hoje)->diffInDays(Carbon::parse($dia));
            $textoPeriodo = "Nos proximos $diadiff dias (desde " . Carbon::parse($dia)->format('d/m/Y') . ")";
        }

        $query = DB::table('tb_saidas as s')
            ->join('tb_lotes as l', 'l.idlotes', '=', 's.idLotes')
            ->join('tb_produtos as p', 'p.idProdutos', '=', 'l.idProdutos')
            ->leftJoin('tb_marca_produto', 'tb_marca_produto.produto_id', '=', 'p.idProdutos')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->select(
                'p.nomeProduto',
                'p.UnidadeMedida',
                'p.pesoLiquido',
                'p.tamanho',
                'tb_marca.nomeMarca',
                'p.precoProduto as custo_unitario',
                DB::raw('SUM(s.qtdsaida) as quantidade_total'),
                DB::raw('SUM(s.qtdsaida * p.precoProduto) as total_gasto')
            )->whereNull('p.deleted_at')
            ->groupBy('p.idProdutos', 'tb_marca.nomeMarca')
            ->orderByDesc('total_gasto');

        if ($dia) {
            $query->where('s.dataSaida', '>=', $dia);
        }

        if (!empty($pesquisa)) {
            $query->where(function ($q) use ($pesquisa) {
                $q->where('p.nomeProduto', 'like', "%{$pesquisa}%")
                    ->orWhere('p.tamanho', 'like', "%{$pesquisa}%")
                    ->orWhere('tb_marca.nomeMarca', 'like', "%{$pesquisa}%");
            });
        }

        $produtos = $query->get()->map(fn($item) => (array) $item)->toArray();

        return view('relatorios.relatorio_financeiro', [
            'dados' =>
            [
                'produtos' => $produtos,
                'texto_periodo' => $textoPeriodo

            ],
            'pesquisa' => $pesquisa,
            'data' => $dia ? Crypt::encrypt($dia) : null,
        ]);
    }
    public function FinanceiroExport($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        return Excel::download(new FinanceiroExport($produtos), "financeiro_$data.xlsx");
    }
    public function FinanceiroPDF($produtos)
    {
        $produtos = $this->descriptografar(($produtos));
        $data = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdfs.relatorio_financeiro_pdf', ['dados' => $produtos]);
        return $pdf->download("relatorio_relatorio_financeiro_$data.pdf");
    }
    private function descriptografar($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('relatorios');
        }
        return $id;
    }
}
