<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Produtos e Validades</title>
    <link rel="stylesheet" href="{{asset('css/relatorios.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
</head>

<body>
    <div id="relatorio">
        <div class="nome-relatorio">
            <h1>Relatório de Produtos e Validades</h1>
            <p>Data de emissão: {{ date('d/m/Y') }}</p>
        </div>

        <div class="tabela">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Marca</th>
                        <th>Tamanho/Unidade</th>
                        <th>Validades (quantidade)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dados['produtos'] as $produto)
                        <tr>
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
                                    $produto['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
                                        ? 's'
                                        : '';
                                $peso_liquido =
                                    $produto['pesoLiquido'] != null
                                        ? ($produto['pesoLiquido'] / 1000) * $produto['tamanho']
                                        : '';
                                if ($peso_liquido != '') {
                                    $peso_liquido = "($peso_liquido kg no total)";
                                }
                            @endphp
                            <td>{{ $produto['nomeProduto'] }}</td>
                            <td>{{ $produto['nomeMarca'] ?? '-' }}</td>
                            <td>{{ $produto['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}
                            <td>
                                @foreach ($produto['lotes'] as $lote)
                                    @php
                                        $hoje = \Carbon\Carbon::today();
                                        $validade = \Carbon\Carbon::parse($lote['dataValidade']);
                                        $dias = $hoje->diffInDays($validade, false);
                                        $classe = $dias > 60 ? 'verde' : ($dias > 30 ? 'amarelo' : 'vermelho');
                                    @endphp

                                    <span class="{{ $classe }}">
                                        {{ $validade->format('d/m/Y') }} ({{ $lote['qtdLote'] }})
                                    </span>
                                    <br>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="botoes-container">
            <a href="{{ route('relatorios') }}" class="botao voltar-btn">Voltar</a>
            <a href="{{ route('VencidoExcel', ['produtos' => Crypt::encrypt($dados['produtos'])]) }}" class="botao exportar-btn">Exportar Excel</a>
            <a href="{{ route('VencidoPDF', ['produtos' => Crypt::encrypt($dados['produtos'])])}}" class="botao pdf-btn">Exportar PDF</a>
        </div>
    </div>
</body>

</html>
