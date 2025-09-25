<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Compras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 20px;
            background: #fff;
        }

        .nome-relatorio {
            text-align: center;
            margin-bottom: 20px;
        }

        .nome-relatorio h1 {
            font-size: 20px;
            color: #2e7d32;
            border-bottom: 2px solid #c8e6c9;
            padding-bottom: 5px;
        }

        .nome-relatorio p {
            margin: 2px 0;
            font-size: 12px;
        }
        .tabela {
            width: 100%;
            margin: 0 auto;
            overflow-x: visible;
        }

        .tabela table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
            font-size: 12px;
        }

        .tabela th,
        .tabela td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .tabela thead th {
            background-color: #2e7d32;
            color: #fff;
            font-weight: bold;
        }

        .tabela tr:nth-child(even) {
            background-color: #f1f8e9;
        }

        .verde {
            color: #2e7d32;
            font-weight: bold;
        }

        .amarelo {
            color: #ef6c00;
            font-weight: bold;
        }

        .vermelho {
            color: #c62828;
            font-weight: bold;
        }

        .critico {
            background-color: #ffebee;
            color: #c62828;
            font-weight: 700;
        }

        .tabela tfoot th {
            text-align: right;
            font-weight: bold;
            background-color: #e0e0e0;
        }

        .no-print,
        .botoes-container,
        .buttons-container {
            display: none !important;
        }
    </style>
</head>

<body>

    <div id="relatorio">
        <div class="nome-relatorio">
            <h1>Relatório de Compras</h1>
            <p>Data de emissão: {{ date('d/m/Y') }}</p>
            <p>Período de busca: {{ $dados['texto_periodo'] ?? '' }}</p>
        </div>
        <div class="tabela">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Produto</th>
                        <th>Tamanho/Unidade</th>
                        <th>Fornecedor</th>
                        <th>Marca</th>
                        <th>Quantidade</th>
                        <th>Custo Un.</th>
                        <th>Total</th>
                        <th>Nota Fiscal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $valorGeral = 0; @endphp
                    @forelse ($dados['produtos'] as $linha)
                        @php
                            $mapaUnidades = [
                                'sache' => 'Sachê',
                                'bandeja' => 'Bandeja',
                                'placa' => 'Placa',
                                'pacote' => 'Pacote',
                                'lata' => 'Lata',
                                'pote' => 'Pote',
                                'balde' => 'Balde',
                            ];
                            $unidadeOriginal = strtolower($linha['UnidadeMedida']);
                            $unidadeFormatada = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal);
                            $plural =
                                $linha['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades) ? 's' : '';
                            $peso_liquido =
                                $linha['pesoLiquido'] != null ? ($linha['pesoLiquido'] / 1000) * $linha['tamanho'] : '';
                            if ($peso_liquido != '') {
                                $peso_liquido = "($peso_liquido kg no total)";
                            }
                            $valorGeral += $linha['total'];
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($linha['data_entrada'])->format('d/m/Y') }}</td>
                            <td>{{ $linha['nomeProduto'] }}</td>
                            <td>{{ $linha['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}
                            </td>
                            <td>{{ $linha['nomeFornecedor'] }}</td>
                            <td>{{ $linha['nomeMarca'] ?? '-' }}</td>
                            <td>{{ number_format($linha['quantidade']) }}</td>
                            <td>R$ {{ number_format($linha['custo_unitario'], 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($linha['total'], 2, ',', '.') }}</td>
                            <td>{{ $linha['notas'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">Nenhuma compra encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" style="text-align:right">Total geral:</th>
                        <th colspan="2">R$ {{ number_format($valorGeral, 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
