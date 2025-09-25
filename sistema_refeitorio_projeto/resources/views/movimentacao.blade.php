<x-layout.main-layout>
    <x-slot:title>Gerenciamento de estoque</x-slot:title>
    <div class="container">

        <div class="cards">
            <div class="card active" data-content="1">Entrada de produtos</div>
            <div class="card" data-content="2">Saída de produtos</div>
        </div>

        <div class="content">
            <div class="content-item active" id="content-1">
                <div class="action-buttons">
                    <a href="{{ route('novaEntrada') }}" class="btn-new">
                        <i class="bi bi-plus-lg"></i> Novo
                    </a>
                    <div class="div-pesquisa">
                        <input id="pesquisarEntrada" class="input-pesquisa" type="text" placeholder="Pesquisar..."
                            value="{{ request('pesquisarEntrada', '') }}">
                        <button class="btn-pesquisa" data-input="pesquisarEntrada"
                            data-route="{{ route('movimentacao') }}">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="table-style">
                        <thead>
                            <tr>
                                <th>Nome do produto</th>
                                <th>Peso</th>
                                <th>Nome do fornecedor</th>
                                <th>Nome da marca</th>
                                <th>Data de entrada</th>
                                <th>Data de validade</th>
                                <th>Quantidade entregue</th>
                                <th>Nota fiscal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dados['entradas'] as $entrada)
                                @php
                                    $dataEntradaFormatada = date('d/m/Y', strtotime($entrada['dataEntrada']));
                                    $dataValidadeFormatada = date('d/m/Y', strtotime($entrada['dataValidade']));
                                    $mapaUnidades = [
                                        'sache' => 'Sachê',
                                        'bandeja' => 'Bandeja',
                                        'placa' => 'Placa',
                                        'pacote' => 'Pacote',
                                        'lata' => 'Lata',
                                        'pote' => 'Pote',
                                        'balde' => 'Balde',
                                    ];
                                    $unidadeOriginal = strtolower($entrada['UnidadeMedida']);
                                    $unidadeFormatada = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal);
                                    $plural =
                                        $entrada['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
                                            ? 's'
                                            : '';
                                    $peso_liquido =
                                        $entrada['pesoLiquido'] != null
                                            ? ($entrada['pesoLiquido'] / 1000) * $entrada['tamanho']
                                            : '';
                                    if ($peso_liquido != '') {
                                        $peso_liquido = "($peso_liquido kg no total)";
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $entrada['nomeProduto'] }}</td>
                                    <td>{{ $entrada['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }}
                                        {{ $peso_liquido }}</td>
                                    <td>{{ $entrada['nomeFornecedor'] }}</td>
                                    <td>{{ $entrada['nomeMarca'] }}</td>
                                    <td>{{ $dataEntradaFormatada }}</td>
                                    <td>{{ $dataValidadeFormatada }}</td>
                                    <td>{{ $entrada['qtdEntrada'] }}</td>
                                    <td>{{ $entrada['numeroNotaFiscal'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-item" id="content-2">
                <div class="action-buttons">
                    <a href="{{ route('novaSaida') }}" class="btn-new">
                        <i class="bi bi-plus-lg"></i> Novo
                    </a>
                    <div class="div-pesquisa">
                        <input id="pesquisarSaida" class="input-pesquisa" type="text" placeholder="Pesquisar..."
                            value="{{ request('pesquisarSaida', '') }}">
                        <button class="btn-pesquisa" data-input="pesquisarSaida"
                            data-route="{{ route('movimentacao') }}">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="table-style">
                        <thead>
                            <tr>
                                <th>Nome do produto</th>
                                <th>Peso</th>
                                <th>Nome da marca</th>
                                <th>Data da saída</th>
                                <th>Quantidade retirada</th>
                                <th>Destino</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dados['saidas'] as $saida)
                                @php
                                    $dataSaidaFormatada = date('d/m/Y', strtotime($saida['dataSaida']));
                                    $destino = $saida['destino'] == 2 ? 'Cozinha' : 'Descarte';
                                    $mapaUnidades = [
                                        'sache' => 'Sachê',
                                        'bandeja' => 'Bandeja',
                                        'placa' => 'Placa',
                                        'pacote' => 'Pacote',
                                        'lata' => 'Lata',
                                        'pote' => 'Pote',
                                        'balde' => 'Balde',
                                    ];
                                    $unidadeOriginal = strtolower($saida['UnidadeMedida']);
                                    $unidadeFormatada = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal);
                                    $plural =
                                        $saida['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
                                            ? 's'
                                            : '';
                                    $peso_liquido =
                                        $saida['pesoLiquido'] != null
                                            ? ($saida['pesoLiquido'] / 1000) * $saida['tamanho']
                                            : '';
                                    if ($peso_liquido != '') {
                                        $peso_liquido = "($peso_liquido kg no total)";
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $saida['nomeProduto'] }}</td>
                                    <td>{{ $saida['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }}
                                        {{ $peso_liquido }}</td>
                                    <td>{{ $saida['nomeMarca'] }}</td>
                                    <td>{{ $dataSaidaFormatada }}</td>
                                    <td>{{ $saida['qtdSaida'] }}</td>
                                    <td>{{ $destino }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout.main-layout>
