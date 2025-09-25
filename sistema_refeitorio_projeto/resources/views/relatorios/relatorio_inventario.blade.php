<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/relatorios.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
    <title>Relatório de Inventário</title>
<body>

    <div class="titulo-relatorio">
        <h1>Relatório de Inventário</h1>
        <p>Data de emissão: {{ date('d/m/Y') }}</p>
    </div>

    <div class="tabela">
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade Total</th>
                    <th>Tamanho</th>
                    <th>Unidade</th>
                    <th>Marca</th>
                    <th>Validades (Qtd)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produtos as $produto)
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
                    @endphp
                    <tr>
                        <td>{{ $produto['nomeProduto'] }}</td>
                        <td>{{ $produto['qtdEstoque'] }}</td>
                        <td>{{ $produto['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }}
                            {{ $peso_liquido }}</td>
                        <td>
                            @switch($produto['UnidadeMedida'])
                            @case('g')
                                {{ $unidade = 'Grama' }}
                            @break

                            @case('kg')
                                {{ $unidade = 'Quilogramas' }}
                            @break

                            @case('L')
                                {{ $unidade = 'Litros' }}
                            @break

                            @case('ml')
                                {{ $unidade = 'Mililitros' }}
                            @break

                            @case('bandeja')
                                {{ $unidade = 'Bandejas' }}
                            @break

                            @case('placa')
                                {{ $unidade = 'Placas' }}
                            @break

                            @case('pacote')
                                {{ $unidade = 'Pacotes' }}
                            @break

                            @case('sache')
                                {{ $unidade = 'Sachês' }}
                            @break

                            @case('lata')
                                {{ $unidade = 'Latas' }}
                            @break

                            @case('pote')
                                {{ $unidade = 'Potes' }}
                            @break

                            @case('balde')
                                {{ $unidade = 'Baldes' }}
                            @break
                            @endswitch
                        </td>
                        <td>{{ $produto['nomeMarca'] ?? '-' }}</td>
                        <td>
                            @foreach ($produto['lotes'] as $lote)
                                {{ date('d/m/Y', strtotime($lote['dataValidade'])) }} ({{ $lote['qtdLote'] }})<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="botoes-container">
        <a href="{{ route('relatorios') }}" class="botao voltar-btn">Voltar</a>
        <a href="{{ route('InventarioExcel', ['produtos' => Crypt::encrypt($produtos)])}}" class="botao exportar-btn">Exportar Excel</a>
        <a href="{{ route('InventarioPDF', ['produtos' => Crypt::encrypt($produtos)])}}" class="botao pdf-btn">Exportar PDF</a>
    </div>
</body>
</html>
