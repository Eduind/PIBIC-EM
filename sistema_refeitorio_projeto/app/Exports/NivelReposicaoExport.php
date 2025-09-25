<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NivelReposicaoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        return ['Nome do Produto', 'Tamanho/Unidade',  'Quantidade Disponivel','Nome da marca', 'Data de Validade Mais Proxima', 'Status'];
    }

    public function map($row): array
    {
        $mapaUnidades = [
            'sache' => 'Sachê',
            'bandeja' => 'Bandeja',
            'placa' => 'Placa',
            'pacote' => 'Pacote',
            'lata' => 'Lata',
            'pote' => 'Pote',
            'balde' => 'Balde',
        ];

        $unidadeOriginal = strtolower($row['UnidadeMedida'] ?? '');
        $unidadeFormatada =
            $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal ?: 'Unidade');
        $plural =
            ($row['tamanho'] ?? 0) != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
            ? 's'
            : '';

        $peso_liquido = '';
        if (!empty($row['pesoLiquido']) && !empty($row['tamanho'])) {
            $pesoTotal    = ($row['pesoLiquido'] / 1000) * $row['tamanho'];
            $peso_liquido = " (" . number_format($pesoTotal, 2, ',', '.') . " kg no total)";
        }
        $validade = \Carbon\Carbon::parse($row['dataValidade']);
        $status = 'Necessário Repor';
        return [
            $row['nomeProduto'],
            ($row['tamanho'] ?? 0) . " {$unidadeFormatada}{$plural}{$peso_liquido}",
            ($row['qtdEstoque'] !== 0 ? $row['qtdEstoque']: '0') . " unidades",
            trim($row['nomeMarca'] ?? '') !== '' ? $row['nomeMarca'] : 'Não possui',
            $validade->format('d/m/Y'),
            $status
        ];
    }
}
