<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Compras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/relatorios.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
</head>

<body>

    <div id="relatorio">
        <div class="nome-relatorio">
            <h1>Relatório de Compras</h1>
            <p>Data de emissão: {{ date('d/m/Y') }}</p>
            <p>Período de busca: {{ $dados['texto_periodo'] ?? '' }}</p>
        </div>

        <div class="filtro-pesquisa no-print">
            <form method="POST" action="{{ route('relatorioCompra',['data' => $data]) }}">
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
                                $linha['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
                                    ? 's'
                                    : '';
                            $peso_liquido =
                                $linha['pesoLiquido'] != null
                                    ? ($linha['pesoLiquido'] / 1000) * $linha['tamanho']
                                    : '';
                            if ($peso_liquido != '') {
                                $peso_liquido = "($peso_liquido kg no total)";
                            }
                            $valorGeral += $linha['total'];
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($linha['data_entrada'])->format('d/m/Y') }}</td>
                            <td>{{ $linha['nomeProduto'] }}</td>
                            <td>{{ $linha['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}</td>
                            <td>{{ $linha['nomeFornecedor'] }}</td>
                            <td>{{ ($linha['nomeMarca'] ?? "-")}}</td>
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

    <div class="botoes-container no-print">
        <a href="{{ route('relatorios') }}" class="botao voltar-btn">Voltar</a>
        <a href="{{route('CompraExcel',['produtos' => Crypt::encrypt($dados)])}}" class="botao exportar-btn">Exportar Excel</a>
        <a href="{{ route('CompraPDF', ['produtos' => Crypt::encrypt($dados)])}}" class="botao pdf-btn">Exportar PDF</a>
    </div>
</body>
</html>
