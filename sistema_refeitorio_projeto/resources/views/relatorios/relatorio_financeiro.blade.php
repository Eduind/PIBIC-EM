<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/relatorios.css') }}">
</head>

<body>
    <div id="relatorio">
        <div class="nome-relatorio">
            <h1>Relatório Financeiro</h1>
            <p>Data de emissão: {{ date('d/m/Y') }}</p>
            <p>Período de análise: {{ $dados['texto_periodo'] ?? '' }}</p>
        </div>

        <div class="filtro-pesquisa no-print">
            <form method="POST" action="{{ route('relatorioFinanceiro', ['data' => $data]) }}">
                @csrf
                <input type="text" name="pesquisa" placeholder="Pesquisar por produto ou nota fiscal..."
                    value="{{ $pesquisa ?? '' }}">
                <button type="submit">Filtrar</button>
            </form>
        </div>

        <div class="tabela">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Tamanho/Unidade</th>
                        <th>Nome da Marca</th>
                        <th>Quantidade retirada</th>
                        <th>Custo Unitário</th>
                        <th>Total Gasto</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalGeral = 0; @endphp
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
                            $totalGeral += $linha['total_gasto'];
                        @endphp
                        <tr>
                            <td>{{ $linha['nomeProduto'] }}</td>
                            <td>{{ $linha['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}
                            </td>
                            <td>{{ $linha['nomeMarca'] ?? '-' }}</td>
                            <td>{{ number_format($linha['quantidade_total']) }}</td>
                            <td>R$ {{ number_format($linha['custo_unitario'], 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($linha['total_gasto'], 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">Nenhuma saída encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align:right">Total Geral Gasto:</th>
                        <th>R$ {{ number_format($totalGeral, 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="botoes-container no-print">
        <a href="{{ route('relatorios') }}" class="botao voltar-btn">Voltar</a>
        <a href="{{ route('FinanceiroExcel', ['produtos' => Crypt::encrypt($dados)]) }}"
            class="botao exportar-btn">Exportar Excel</a>
        <a href="{{ route('FinanceiroPDF', ['produtos' => Crypt::encrypt($dados)]) }}" class="botao pdf-btn">Exportar
            PDF</a>
    </div>

</body>

</html>
