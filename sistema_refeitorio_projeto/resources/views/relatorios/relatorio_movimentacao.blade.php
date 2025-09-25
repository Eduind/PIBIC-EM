<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório Simplificado de Movimentações</title>
    <link rel="stylesheet" href="{{ asset('css/relatorios.css') }}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
</head>

<body>
    <div class="titulo-relatorio">
        <h1>Relatório de Movimentações</h1>
        <p>Período: {{  $dados['texto_periodo']  }}</p>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Tamanho/Unidade</th>
                    <th>Nome da Marca</th>
                    <th>Nome do Fornecedor</th>
                    <th>Qtd. Entrada</th>
                    <th>Qtd. Saída</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados['produtos'] as $produto)
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
                        $unidadeOriginal = strtolower($produto['UnidadeMedida']);
                        $unidadeFormatada = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal);
                        $plural =
                            $produto['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades) ? 's' : '';
                        $peso_liquido =
                            $produto['pesoLiquido'] != null
                                ? ($produto['pesoLiquido'] / 1000) * $produto['tamanho']
                                : '';
                        if ($peso_liquido != '') {
                            $peso_liquido = "($peso_liquido kg no total)";
                        }
                        $data = date('d/m/Y', strtotime($produto['data']));
                    @endphp
                    <tr>
                        <td>{{ $data }}</td>
                        <td>{{ $produto['produto'] }}</td>
                        <td>{{ $produto['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}
                        </td>
                        <td>{{ $produto['marca'] }}</td>
                        <td>{{ $produto['fornecedor'] }}</td>
                        <td>{{ $produto['qtdEntrada'] }}</td>
                        <td>{{ $produto['qtdSaida'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">Nenhuma movimentação encontrada no período.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
    <div class="botoes-container">
        <a href="{{ route('relatorios') }}" class="botao voltar-btn">Voltar</a>
        <a href="{{ route('MovimentacaoExcel', ['movimentacao' => Crypt::encrypt($dados)]) }}"
            class="botao exportar-btn">Exportar Excel</a>
        <a href="{{ route('MovimentacaoPDF', ['movimentacao' => Crypt::encrypt($dados)]) }}"
            class="botao pdf-btn">Exportar PDF</a>
    </div>
</body>

</html>
