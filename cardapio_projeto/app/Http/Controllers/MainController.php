<?php

namespace App\Http\Controllers;

use App\Models\tb_alimento;
use App\Models\tb_cardapio;
use App\Models\tb_cardapio_refeicoes;
use App\Models\tb_usuario;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


class MainController extends Controller
{
    public function ExibirCardapioGeral(): View
    {
        $cardapioAtivo = tb_cardapio::where('ativo', 1)->first();

        if (!$cardapioAtivo) {
            $refeicoes = [];
        } else {
            $refeicoes = [];
            $periodos = ['matutino', 'almoco', 'vespertino', 'noturno'];
            $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];

            foreach ($periodos as $periodo) {
                foreach ($dias as $dia) {
                    $refeicoes[$periodo][$dia] = tb_cardapio_refeicoes::where('cardapio_id', $cardapioAtivo->idCardapio)
                        ->where('horario', $periodo)
                        ->where('dia_semana', $dia)
                        ->whereHas('alimento', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->with(['alimento' => function ($q) {
                            $q->whereNull('deleted_at');
                        }])
                        ->get()
                        ->map(function ($ref) {
                            return [
                                'nomeAlimento'    => $ref->alimento->nomeAlimento,
                                'ingredientes'    => $ref->alimento->ingredientes,
                                'calorias'        => $ref->alimento->calorias,
                                'carboidratos'    => $ref->alimento->carboidratos,
                                'proteinas'       => $ref->alimento->proteinas,
                                'gorduras_totais' => $ref->alimento->gorduras_totais,
                                'contem_gluten'   => $ref->alimento->contem_gluten,
                                'alergicos'       => $ref->alimento->alergicos ?? [],
                            ];
                        })->toArray();
                }
            }
        }

        return view('cardapio', compact('refeicoes'));
    }

    public function ExibirAlimentos(Request $request): View
    {
        $pesquisa = trim($request->input('text_pesquisa', ''));

        $alimentos = tb_alimento::query()
            ->when($pesquisa, function ($query) use ($request) {
                $query->where('nomeAlimento', 'like', "%{$request->text_pesquisa}%");
            })
            ->whereNull('deleted_at')
            ->get();

        return view('admin.home', ['dados' => ['alimentos' => $alimentos]]);
    }

    public function CriarAlimentos(): View
    {
        return view('admin.criar_alimentos');
    }
    public function CriarAlimentosSubmit(Request $request): View
    {
        $data = $request->validate(
            [
                'text_nome_alimento'   => 'required|string|max:150',
                'text_ingredientes'    => 'required|min:3',
                'text_calorias'        => 'required|numeric',
                'text_carboidratos'    => 'required|numeric',
                'text_proteinas'       => 'required|numeric',
                'text_gorduras_totais' => 'required|numeric',
                'text_gluten'          => 'required|boolean',
                'itensAlergias'        => 'nullable|array',
                'itensAlergias.*'      => 'string|max:50',
            ],
            [
                'text_nome_alimento.required'   => 'O nome do alimento é obrigatório',
                'text_nome_alimento.string'   => 'O nome do alimento deve ser um texto',
                'text_nome_alimento.max'   => 'O nome do alimento deve ter no maximo :max caracteres',
                'text_ingredientes.required'    => 'Os ingredientes são obrigatórios',
                'text_ingredientes.min'         => 'O campo deve ter ao menos :min caracteres',
                'text_calorias.required'        => 'É obrigatório informar a quantidade de calorias do alimento',
                'text_calorias.numeric'         => 'O campo deve conter números',
                'text_carboidratos.required'    => 'É obrigatório informar a quantidade de carboidratos do alimento',
                'text_carboidratos.numeric'     => 'O campo deve conter números',
                'text_proteinas.required'       => 'É obrigatório informar a quantidade de proteínas do alimento',
                'text_proteinas.numeric'        => 'O campo deve conter números',
                'text_gorduras_totais.required' => 'É obrigatório informar a quantidade de gorduras totais do alimento',
                'text_gorduras_totais.numeric'  => 'O campo deve conter números',
                'text_gluten.required'          => 'É obrigatório informar se o alimento contém ou não glúten',
                'text_gluten.boolean'           => 'O campo deve ser 0 (não contém) ou 1 (contém)',
            ]
        );

        $alimento = new tb_alimento();
        $alimento->nomeAlimento    = $data['text_nome_alimento'];
        $alimento->ingredientes    = $data['text_ingredientes'];
        $alimento->calorias        = $data['text_calorias'];
        $alimento->carboidratos    = $data['text_carboidratos'];
        $alimento->proteinas       = $data['text_proteinas'];
        $alimento->gorduras_totais = $data['text_gorduras_totais'];
        $alimento->contem_gluten   = $data['text_gluten'];
        $alimento->alergicos = $data['itensAlergias'] ?? [];

        $alimento->save();
        session()->flash('criacao_valida', 'Alimento criado com sucesso!');
        return view('admin.criar_alimentos');
    }

    public function ExibirCardapios(Request $request): View
    {
        $cardapios = tb_cardapio::with(['refeicoes' => function ($q) {
            $q->whereHas('alimento', function ($q2) {
                $q2->whereNull('deleted_at');
            })->with(['alimento' => function ($q2) {
                $q2->whereNull('deleted_at');
            }]);
        }])
            ->whereNull('tb_cardapios.deleted_at')
            ->get()
            ->map(function ($cardapio) {
                $refeicoes = [];
                $dias = [];

                foreach ($cardapio->refeicoes as $refeicao) {
                    if ($refeicao->alimento) {
                        $dia = ucfirst($refeicao->dia_semana);
                        $horario = $refeicao->horario;

                        if (!isset($refeicoes[$dia])) {
                            $refeicoes[$dia] = [
                                'matutino'   => [],
                                'almoco'     => [],
                                'vespertino' => [],
                                'noturno'    => [],
                            ];
                        }

                        $refeicoes[$dia][$horario][] = $refeicao->alimento->nomeAlimento;
                        $dias[] = $dia;
                    }
                }

                return [
                    'idCardapio'   => $cardapio->idCardapio,
                    'nomeCardapio' => $cardapio->nomeCardapio,
                    'dataInicio'   => $cardapio->data_inicio,
                    'dataFim'      => $cardapio->data_fim,
                    'refeicoes'    => $refeicoes,   // agora é [dia][horario] => alimentos
                    'dias'         => array_unique($dias),
                    'ativo'        => $cardapio->ativo,
                ];
            });

        return view('admin.exibir_cardapios', [
            'dados' => ['cardapios' => $cardapios]
        ]);
    }


    public function CriarCardapios(): View
    {
        $diasSemana = ['Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta', 'Sabado'];
        $alimentos = tb_alimento::all()->toArray();
        return view('admin.criar_cardapio', ['diasSemana' =>  $diasSemana, 'dados' => ['alimentos' => $alimentos]]);
    }
    public function CriarCardapiosSubmit(Request $request)
    {
        $data = $request->validate([
            'text_name'      => 'required|string|max:100',
            'text_data_ini'  => 'required|date',
            'text_data_fim'  => 'required|date|after_or_equal:text_data_ini',
            'refeicoes'                => 'required|array',
            'refeicoes.*.*'            => 'nullable|integer|exists:tb_alimentos,idAlimento',
        ], [
            'text_name.required'      => 'O nome do cardápio é obrigatório',
            'text_name.string'        => 'O nome deve ser um texto válido',
            'text_name.max'           => 'O nome do cardápio deve ter no máximo :max caracteres',
            'text_data_ini.required'  => 'A data de início é obrigatória',
            'text_data_ini.date'      => 'Data de início inválida',
            'text_data_fim.required'  => 'A data de fim é obrigatória',
            'text_data_fim.date'      => 'Data de fim inválida',
            'text_data_fim.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início',
            'refeicoes.required'      => 'É necessário selecionar pelo menos um alimento',
            'refeicoes.*.*.integer'   => 'Cada alimento selecionado deve ser um número válido',
            'refeicoes.*.*.exists'    => 'O alimento selecionado não existe',
        ]);

        $cardapio = tb_cardapio::create([
            'nomeCardapio' => $data['text_name'],
            'data_inicio'  => $data['text_data_ini'],
            'data_fim'     => $data['text_data_fim'],
        ]);

        foreach ($data['refeicoes'] as $dia => $horarios) {
            foreach ($horarios as $horario => $alimentoId) {
                if ($alimentoId) {
                    tb_cardapio_refeicoes::create([
                        'cardapio_id' => $cardapio->idCardapio,
                        'alimento_id' => $alimentoId,
                        'dia_semana'  => strtolower($dia),
                        'horario'     => strtolower($horario),
                    ]);
                }
            }
        }
        session()->flash('criacao_valida', 'Produto criado com sucesso!');
        return redirect()->route('CriarCardapios');
    }

    public function AtivarCardapio($id)
    {
        $idDescriptografado = $this->descriptografar($id);

        $cardapioativo = tb_cardapio::where('ativo', 1)->first();
        if ($cardapioativo) {
            $cardapioativo->ativo = 0;
            $cardapioativo->save();
        }

        $cardapioNovoAtivo = tb_cardapio::find($idDescriptografado);
        if ($cardapioNovoAtivo) {
            $cardapioNovoAtivo->ativo = 1;
            $cardapioNovoAtivo->save();
        }

        return redirect()->route('ExibirCardapios');
    }

    private function descriptografar($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('home');
        }
        return $id;
    }

    public function EditarAlimento($id)
    {
        $idDescriptografado = $this->descriptografar($id);
        $alimento = tb_alimento::find($idDescriptografado);
        return view('admin.atualizar_alimento', ['dados' => ['alimento' => $alimento]]);
    }
    public function EditarAlimentoSubmit(Request $request)
    {
        $data = $request->validate(
            [
                'text_nome_alimento'   => 'required|string|max:150',
                'text_ingredientes'    => 'required|min:3',
                'text_calorias'        => 'required|numeric',
                'text_carboidratos'    => 'required|numeric',
                'text_proteinas'       => 'required|numeric',
                'text_gorduras_totais' => 'required|numeric',
                'text_gluten'          => 'required|boolean',
                'itensAlergias'        => 'nullable|array',
                'itensAlergias.*'      => 'string|max:50',
            ],
            [
                'text_nome_alimento.required'   => 'O nome do alimento é obrigatório',
                'text_nome_alimento.string'   => 'O nome do alimento deve ser um texto',
                'text_nome_alimento.max'   => 'O nome do alimento deve ter no maximo :max caracteres',
                'text_ingredientes.required'    => 'Os ingredientes são obrigatórios',
                'text_ingredientes.min'         => 'O campo deve ter ao menos :min caracteres',
                'text_calorias.required'        => 'É obrigatório informar a quantidade de calorias do alimento',
                'text_calorias.numeric'         => 'O campo deve conter números',
                'text_carboidratos.required'    => 'É obrigatório informar a quantidade de carboidratos do alimento',
                'text_carboidratos.numeric'     => 'O campo deve conter números',
                'text_proteinas.required'       => 'É obrigatório informar a quantidade de proteínas do alimento',
                'text_proteinas.numeric'        => 'O campo deve conter números',
                'text_gorduras_totais.required' => 'É obrigatório informar a quantidade de gorduras totais do alimento',
                'text_gorduras_totais.numeric'  => 'O campo deve conter números',
                'text_gluten.required'          => 'É obrigatório informar se o alimento contém ou não glúten',
                'text_gluten.boolean'           => 'O campo deve ser 0 (não contém) ou 1 (contém)',
            ]
        );

        $alimento = tb_alimento::find($request->id);
        $alimento->nomeAlimento    = $data['text_nome_alimento'];
        $alimento->ingredientes    = $data['text_ingredientes'];
        $alimento->calorias        = $data['text_calorias'];
        $alimento->carboidratos    = $data['text_carboidratos'];
        $alimento->proteinas       = $data['text_proteinas'];
        $alimento->gorduras_totais = $data['text_gorduras_totais'];
        $alimento->contem_gluten   = $data['text_gluten'];
        $alimento->alergicos = $data['itensAlergias'] ?? [];
        $alimento->save();
        session()->flash('criacao_valida', 'Alimento atualizado com sucesso!');
        return redirect()->route('EditarAlimento', ['id' => Crypt::encrypt($request->id)]);
    }
    public function EditarCardapio($id)
    {
        $idDescriptografado = $this->descriptografar($id);
        $cardapio = tb_cardapio::with('refeicoes')->findOrFail($idDescriptografado);
        $diasSemana = ['Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta', 'Sabado'];

        $refeicoesFormatadas = [];

        foreach ($diasSemana as $dia) {
            $diaKey = strtolower($dia);
            $refeicoesFormatadas[$diaKey] = [
                'matutino'  => optional($cardapio->refeicoes->firstWhere(fn($r) => strtolower($r->dia) == $diaKey && $r->horario == 'matutino'))->id_alimento,
                'almoco'    => optional($cardapio->refeicoes->firstWhere(fn($r) => strtolower($r->dia) == $diaKey && $r->horario == 'almoco'))->id_alimento,
                'vespertino' => optional($cardapio->refeicoes->firstWhere(fn($r) => strtolower($r->dia) == $diaKey && $r->horario == 'vespertino'))->id_alimento,
                'noturno'   => optional($cardapio->refeicoes->firstWhere(fn($r) => strtolower($r->dia) == $diaKey && $r->horario == 'noturno'))->id_alimento,
            ];
        }

        $dados = [
            'cardapio' => [
                'idCardapio'   => $cardapio->idCardapio,
                'nomeCardapio' => $cardapio->nomeCardapio,
                'dataInicio'   => $cardapio->data_inicio,
                'dataFim'      => $cardapio->data_fim,
                'refeicoes'    => $refeicoesFormatadas,
            ],
            'alimentos' => tb_alimento::select('idAlimento', 'nomeAlimento')->get(),
        ];

        return view('admin.atualizar_cardapio', compact('dados', 'diasSemana'));
    }
    public function DeletarAlimentos($id)
    {
        $idDescriptografado = $this->descriptografar($id);
        $alimento = tb_alimento::find($idDescriptografado)->toArray();
        return view('admin.deletar_alimento', ['dados' => ['alimento' => $alimento], 'id' => $idDescriptografado]);
    }
    public function DeletarAlimentosConfirm(Request $request)
    {
        $alimento = tb_alimento::find($request->id_alimento);
        $alimento->delete();
        return redirect()->route('home');
    }
}
