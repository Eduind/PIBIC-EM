<?php

namespace App\Exports;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\RichText\Run;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Carbon\Carbon;

class ProdutoVencimentoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $produtos;

    public function __construct($produtos)
    {
        $this->produtos = $produtos;
    }

    public function collection()
    {
        return collect($this->produtos);
    }

    public function headings(): array
    {
        return [
            'Produto',
            'Marca',
            'Tamanho/Unidade',
            'Validades (Qtd)'
        ];
    }

    public function map($row): array
    {
        $mapaUnidades = [
            'sache'   => 'Sachê',
            'bandeja' => 'Bandeja',
            'placa'   => 'Placa',
            'pacote'  => 'Pacote',
            'lata'    => 'Lata',
            'pote'    => 'Pote',
            'balde'   => 'Balde',
        ];

        $unidadeOriginal   = strtolower($row['UnidadeMedida'] ?? '');
        $unidadeFormatada  = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal ?: 'Unidade');
        $plural            = ($row['tamanho'] ?? 0) != 1 && array_key_exists($unidadeOriginal, $mapaUnidades) ? 's' : '';

        $peso_liquido = '';
        if (!empty($row['pesoLiquido']) && !empty($row['tamanho'])) {
            $pesoTotal    = ($row['pesoLiquido'] / 1000) * $row['tamanho'];
            $peso_liquido = " (" . number_format($pesoTotal, 2, ',', '.') . " kg no total)";
        }

        $richText = new RichText();

        if (!empty($row['lotes'])) {
            $first = true;
            foreach ($row['lotes'] as $lote) {
                $texto = '';
                if (!$first) {
                    $texto .= "; ";
                }
                $first = false;

                if (!empty($lote['dataValidade'])) {
                    $dataStr = date('d/m/Y', strtotime($lote['dataValidade']));
                    $qtd     = $lote['qtdLote'] ?? 0;

                    $texto .= "$dataStr ($qtd)";

                    // Calcula diferença de dias
                    $diffDias = Carbon::now()->diffInDays(Carbon::createFromFormat('d/m/Y', $dataStr), false);

                    if ($diffDias < 30) {
                        $color = Color::COLOR_RED;
                    } elseif ($diffDias >= 30 && $diffDias <= 60) {
                        $color = Color::COLOR_DARKYELLOW;
                    } else {
                        $color = Color::COLOR_DARKGREEN;
                    }

                    $run = new Run($texto);
                    $run->getFont()->getColor()->setARGB($color);
                    $richText->addText($run);
                } else {
                    $texto .= "Não registrado";
                    $run = new Run($texto);
                    $run->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
                    $richText->addText($run);
                }
            }
        } else {
            $richText->createText("Não registrado");
        }

        return [
            $row['nomeProduto'] ?? 'Sem nome',
            trim($row['nomeMarca'] ?? '') !== '' ? $row['nomeMarca'] : 'Não possui',
            ($row['tamanho'] ?? 0) . " {$unidadeFormatada}{$plural}{$peso_liquido}",
            $richText, // <- aqui vai o RichText no lugar da string
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Começa na linha 2 porque a 1 é o cabeçalho
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cell = "D$row"; // Coluna D = Validades
                    $valor = $sheet->getCell($cell)->getValue();

                    if (!$valor || $valor === 'Não registrado') {
                        continue;
                    }

                    // Pode ter várias datas separadas por ";"
                    $datas = explode(";", $valor);
                    $color = null;

                    foreach ($datas as $d) {
                        if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $d, $matches)) {
                            $dataValidade = Carbon::createFromFormat('d/m/Y', $matches[1]);
                            $diffDias = Carbon::now()->diffInDays($dataValidade, false);

                            if ($diffDias < 30) {
                                $color = Color::COLOR_RED;
                                break; // vermelho tem prioridade
                            } elseif ($diffDias >= 30 && $diffDias <= 60) {
                                $color = Color::COLOR_DARKYELLOW;
                                // continua verificando caso exista algum vermelho
                            } elseif ($diffDias > 60 && !$color) {
                                $color = Color::COLOR_DARKGREEN;
                            }
                        }
                    }

                    if ($color) {
                        $sheet->getStyle($cell)->getFont()->getColor()->setARGB($color);
                    }
                }
            }
        ];
    }
}
