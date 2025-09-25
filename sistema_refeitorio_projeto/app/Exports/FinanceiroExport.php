<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FinanceiroExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $produtos;
    protected $totalGeral;

    public function __construct($produtos)
    {
        $this->produtos = $produtos;

        $this->totalGeral = collect($produtos)->sum(function ($row) {
            return $row['total_gasto'] ?? 0;
        });
    }

    public function collection()
    {
        $dados = collect($this->produtos);

        $dados->push([
            'linha_total' => true,
            'total' => $this->totalGeral,
        ]);

        return $dados;
    }

    public function headings(): array
    {
        return [
            'Produto',
            'Tamanho/Unidade',
            'Marca',
            'Quantidade',
            'Custo Un.',
            'Total Gasto',
        ];
    }

    public function map($row): array
    {
        if (isset($row['linha_total']) && $row['linha_total'] === true) {
            return [
                '', '', '', '',
                'TOTAL GERAL:',
                number_format($row['total'], 2, ',', '.'),
                '',
            ];
        }

        $mapaUnidades = [
            'sache'   => 'Sachê',
            'bandeja' => 'Bandeja',
            'placa'   => 'Placa',
            'pacote'  => 'Pacote',
            'lata'    => 'Lata',
            'pote'    => 'Pote',
            'balde'   => 'Balde',
        ];

        $unidadeOriginal = strtolower($row['UnidadeMedida'] ?? '');
        $unidadeFormatada = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal ?: 'Unidade');
        $plural = (($row['tamanho'] ?? 0) != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)) ? 's' : '';

        $peso_liquido = '';
        if (!empty($row['pesoLiquido']) && !empty($row['tamanho'])) {
            $pesoTotal    = ($row['pesoLiquido'] / 1000) * $row['tamanho'];
            $peso_liquido = " (" . number_format($pesoTotal, 2, ',', '.') . " kg no total)";
        }

        return [
            $row['nomeProduto'],
            ($row['tamanho'] ?? 0) . " {$unidadeFormatada}{$plural}{$peso_liquido}",
            trim($row['nomeMarca'] ?? '') !== '' ? $row['nomeMarca'] : 'Não possui',
            ($row['quantidade_total'] !== 0 ? $row['quantidade_total'] : '0'),
            number_format($row['custo_unitario'], 2, ',', '.'),
            number_format($row['total_gasto'], 2, ',', '.'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A{$lastRow}:I{$lastRow}")
                    ->getFont()->setBold(true);
            },
        ];
    }
}
