<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
            'Quantidade Total',
            'Tamanho',
            'Unidade',
            'Marca',
            'Validades (Qtd)'
        ];
    }

    public function map($row): array
    {
        // Mapa para nomes mais amigáveis
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

        // Calcula peso líquido total (se houver)
        $peso_liquido = '';
        if (!empty($row['pesoLiquido']) && !empty($row['tamanho'])) {
            $pesoTotal    = ($row['pesoLiquido'] / 1000) * $row['tamanho'];
            $peso_liquido = " (" . number_format($pesoTotal, 2, ',', '.') . " kg no total)";
        }

        // Switch para unidade
        switch ($row['UnidadeMedida']) {
            case 'g':
                $unidade = 'Grama';
                break;
            case 'kg':
                $unidade = 'Quilogramas';
                break;
            case 'L':
                $unidade = 'Litros';
                break;
            case 'ml':
                $unidade = 'Mililitros';
                break;
            case 'bandeja':
                $unidade = 'Bandejas';
                break;
            case 'placa':
                $unidade = 'Placas';
                break;
            case 'pacote':
                $unidade = 'Pacotes';
                break;
            case 'sache':
                $unidade = 'Sachês';
                break;
            case 'lata':
                $unidade = 'Latas';
                break;
            case 'pote':
                $unidade = 'Potes';
                break;
            case 'balde':
                $unidade = 'Baldes';
                break;
            default:
                $unidade = 'Unidade não definida';
                break;
        }

        // Validades dos lotes
        if (!empty($row['lotes'])) {
            $datas = [];
            foreach ($row['lotes'] as $lote) {
                $data = !empty($lote['dataValidade']) ? date('d/m/Y', strtotime($lote['dataValidade'])) : 'Não registrado';
                $qtd  = $lote['qtdLote'] ?? 0;
                $datas[] = "$data ($qtd)";
            }
            $validades = implode("; ", $datas);
        } else {
            $validades = 'Não registrado';
        }

        return [
            $row['nomeProduto'] ?? 'Sem nome',
            ($row['qtdEstoque'] !== 0 ? $row['qtdEstoque']: '0'),
            ($row['tamanho'] ?? 0) . " {$unidadeFormatada}{$plural}{$peso_liquido}",
            $unidade,
            trim($row['nomeMarca'] ?? '') !== '' ? $row['nomeMarca'] : 'Não possui',
            $validades,
        ];
    }
}
