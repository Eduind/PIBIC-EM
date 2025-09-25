<x-layout.main-layout>
    <x-slot:title> Estoque </x-slot:title>
    <div class="container">
        <div class="content">
            <div class="table-container">
                <table class="table-style">
                    <thead>
                        <tr>
                            <th>Nome do produto</th>
                            <th>Quantidade em estoque</th>
                            <th>Peso</th>
                            <th>Unidade de apresentação</th>
                            <th>Marca</th>
                            <th>Categoria</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hoje = new DateTime();
                        @endphp
                        @foreach ($dados['produtos'] as $produtos)
                            <tr>
                                <td>{{ $produtos['nomeProduto'] }}</td>
                                <td>{{ $produtos['qtdEstoque'] }}</td>
                                <td>
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
                                    $unidadeOriginal = strtolower($produtos['UnidadeMedida']);
                                    $unidadeFormatada = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal);
                                    $plural =
                                        $produtos['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
                                            ? 's'
                                            : '';
                                    $peso_liquido =
                                        $produtos['pesoLiquido'] != null
                                            ? ($produtos['pesoLiquido'] / 1000) * $produtos['tamanho']
                                            : '';
                                    if ($peso_liquido != '') {
                                        $peso_liquido = "($peso_liquido kg no total)";
                                    }
                                @endphp
                                {{ $produtos['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}
                                </td>
                                <td>
                                    @switch($produtos['UnidadeMedida'])
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
                                <td> {{ $produtos['nomeMarca'] ?? '' }} </td>
                                <td> {{ $produtos['nomeCategoria'] ?? '' }} </td>
                                <td><button type="button" class="expandBtn"
                                        data-product-id="{{ $produtos['idProdutos'] }}">↓</button></td>
                            </tr>
                            @foreach ($produtos['lotes'] as $lotes)
                                @php
                                    $validade = new DateTime($lotes['dataValidade']);
                                    $diff = $hoje->diff($validade);
                                    $diasRestantes = (int) $diff->format('%r%a');
                                    $dataFormatada = $validade->format('d/m/y');
                                    if ($diasRestantes > 60) {
                                        $classData = 'date-green';
                                    } elseif ($diasRestantes > 14) {
                                        $classData = 'date-yellow';
                                    } else {
                                        $classData = 'date-red';
                                    }
                                @endphp

                                <tr class="lot-row" data-product-id="{{ $lotes['idProdutos'] }}" style="display:none;">
                                    <td colspan="6">
                                        <div class="lot-details">
                                            <span class="lot-number">Lote:{{ $lotes['idLotes'] }}</span>
                                            <span
                                                class="lot-date {{ $classData }}">Validade:{{ $dataFormatada }}</span>
                                            <span class="lot-qty">Quantidade:{{ $lotes['qtdLote'] }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout.main-layout>
