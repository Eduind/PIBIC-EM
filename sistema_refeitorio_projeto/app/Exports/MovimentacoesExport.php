<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovimentacoesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $movimentacoes;

    public function __construct($movimentacoes)
    {
        $this->movimentacoes = $movimentacoes;
    }

    public function collection()
    {
        return collect($this->movimentacoes);
    }

    public function headings(): array
    {
        return ['Data', 'Produto', 'Tamanho/Unidade', 'Peso Líquido', 'Nome da Marca', 'Nome Fornecedor', 'Qtd Entrada', 'Qtd Saída'];
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

        return [
            !empty($row['data']) ? Carbon::parse($row['data'])->format('d/m/Y') : 'Sem data',
            trim($row['produto'] ?? '') !== '' ? $row['produto'] : 'Não possui',
            ($row['tamanho'] ?? 0) . " {$unidadeFormatada}{$plural}",
            $peso_liquido !== '' ? $peso_liquido : 'Nâo possui',
            trim($row['marca'] ?? '') !== '' ? $row['marca'] : 'Não possui',
            trim($row['fornecedor'] ?? '') !== '' ? $row['fornecedor'] : 'Não possui',
            ($row['qtdEntrada'] !== 0 ? $row['qtdEntrada']: '0'),
            ($row['qtdSaida'] !== 0 ? $row['qtdSaida']: '0')
        ];
    }
}
