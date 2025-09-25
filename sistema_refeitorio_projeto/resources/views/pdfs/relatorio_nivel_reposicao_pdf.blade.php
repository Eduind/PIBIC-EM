<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Nível de Reposição</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
            color: #333;
            font-size: 16px;
        }

        .titulo-relatorio,
        .nome-relatorio {
            text-align: center;
            margin-bottom: 30px;
        }

        .titulo-relatorio h1,
        .nome-relatorio h1 {
            font-size: 28px;
            color: #2e7d32;
            border-bottom: 2px solid #c8e6c9;
            padding-bottom: 10px;
        }

        .filtro-pesquisa {
            max-width: 1000px;
            margin: 0 auto 25px auto;
            text-align: right;
        }

        .filtro-pesquisa input[type="text"] {
            padding: 10px;
            width: 320px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filtro-pesquisa button {
            padding: 10px 18px;
            background-color: #2e7d32;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 8px;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }

        .filtro-pesquisa button:hover {
            background-color: #1b5e20;
        }

        .tabela,
        .table-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            overflow-x: auto;
        }

        .tabela table,
        .table-wrapper table,
        table.relatorio,
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-width: 700px;
        }

        .tabela th,
        .tabela td,
        .table-wrapper th,
        .table-wrapper td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .tabela thead,
        .table-wrapper thead {
            background-color: #2e7d32;
            color: white;
        }

        .tabela tr:nth-child(even),
        .table-wrapper tr:nth-child(even) {
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

        .botoes-container,
        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px auto;
            flex-wrap: wrap;
        }

        .botao,
        .btn {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .voltar-btn,
        .back-btn {
            background-color: #757575;
        }

        .voltar-btn:hover,
        .back-btn:hover {
            background-color: #424242;
        }

        .exportar-btn,
        .export-btn {
            background-color: #2e7d32;
        }

        .exportar-btn:hover,
        .export-btn:hover {
            background-color: #1b5e20;
        }

        .pdf-btn {
            background-color: #be260b;
        }

        .pdf-btn:hover {
            background-color: #9b1c06;
        }

        @media print {

            .no-print,
            .botoes-container,
            .buttons-container {
                display: none !important;
            }

            body {
                background: #fff;
                padding: 0;
            }
        }

        @media (max-width: 1024px) {
            body {
                padding: 20px;
                font-size: 15px;
            }

            .titulo-relatorio h1,
            .nome-relatorio h1 {
                font-size: 26px;
            }

            .filtro-pesquisa {
                text-align: center;
            }

            .filtro-pesquisa input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 768px) {

            .tabela table,
            .table-wrapper table {
                font-size: 14px;
                min-width: unset;
            }

            .titulo-relatorio h1,
            .nome-relatorio h1 {
                font-size: 22px;
            }

            .botao,
            .btn {
                font-size: 14px;
                padding: 8px 18px;
            }
        }

        @media (max-width: 480px) {
            body {
                font-size: 14px;
                padding: 15px;
            }

            .titulo-relatorio h1,
            .nome-relatorio h1 {
                font-size: 20px;
            }

            .botao,
            .btn {
                width: 100%;
                padding: 12px;
            }

            .botoes-container,
            .buttons-container {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
</head>
<body>
    <div id="relatorio">
        <div class="nome-relatorio">
            <h1>Relatório de Nível de Reposição</h1>
            <p>Data de emissão: {{ date('d/m/Y') }}</p>
        </div>

        <div class="tabela">
            <table>
                <thead>
                    <tr>
                        <th>Nome do Produto</th>
                        <th>Tamanho/Unidade</th>
                        <th>Quantidade Disponível</th>
                        <th>Nome da Marca</th>
                        <th>Data de Validade Mais Próxima</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dados['produtos'] as $produto)
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

                            $unidadeOriginal = strtolower($produto['UnidadeMedida'] ?? '');
                            $unidadeFormatada =
                                $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal ?: 'Unidade');
                            $plural =
                                ($produto['tamanho'] ?? 0) != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
                                    ? 's'
                                    : '';

                            $peso_liquido = '';
                            if (!empty($produto['pesoLiquido']) && !empty($produto['tamanho'])) {
                                $pesoTotal = ($produto['pesoLiquido'] / 1000) * $produto['tamanho'];
                                $peso_liquido = number_format($pesoTotal, 2, ',', '.') . ' kg no total';
                            }
                            $validade = \Carbon\Carbon::parse($produto['dataValidade']);
                            $status = 'Necessário Repor';
                        @endphp
                        <tr class="critico">
                            <td>{{ $produto['nomeProduto'] }}</td>
                            <td>{{ $produto['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}</td>
                            <td>{{ $produto['qtdEstoque'] }} unidades</td>
                            <td>{{ $produto['nomeMarca'] }}</td>
                            <td>{{ $validade->format('d/m/Y') }}</td>
                            <td>{{ $status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                Todos os produtos estão acima do nível mínimo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
