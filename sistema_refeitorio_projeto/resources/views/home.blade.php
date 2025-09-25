<x-layout.main-layout>
    <x-slot:title>Registro de Itens</x-slot:title>

    <div class="container">
        <div class="cards">
            <div class="card active" data-content="2">Categorias</div>
            <div class="card" data-content="1">Produtos</div>
            <div class="card" data-content="3">Marca</div>
            <div class="card" data-content="4">Fornecedor</div>
        </div>

        <div class="content">
            <div class="content-item active" id="content-2">
                <form method="post" action="{{ route('deletarCategoriaConfirmar') }}">
                    @csrf
                    <div class="action-buttons">
                        <a href="{{ route('novaCategoria') }}" class="btn-new"><i class="bi bi-plus-lg"></i> Novo</a>
                        <button name="deletarCategoria" class="deletar"><i class="bi bi-trash-fill"></i></button>
                        <a href="{{ route('AssociarCategoria') }}" class="btn-insert">
                            <i class="bi bi-box-arrow-in-left"></i> Inserir produto
                        </a>
                        <a href="{{ route('restaurarCategoria')}}" class="btn-cancel"><i class="bi bi-plus-lg"></i>Restaurar</a>
                        <div class="div-pesquisa">
                            <input id="pesquisarCategoria" class="input-pesquisa" type="text"
                                placeholder="Pesquisar..." value="{{ $pesquisarCategoria ?? '' }}">
                            <button type="button" class="btn-pesquisa" data-route="{{ route('home') }}"
                                data-input="pesquisarCategoria">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="table-style">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nome da Categoria</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados['categorias'] as $categoria)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="itensCategoria[]"
                                                value="{{ Crypt::encrypt($categoria['idCategorias']) }}">
                                        </td>
                                        <td>{{ $categoria['nomeCategoria'] }}</td>
                                        <td>
                                            <div class="btn-wrapper">
                                                <a href="{{ route('atualizarCategoria', ['id' => Crypt::encrypt($categoria['idCategorias'])]) }}"
                                                    class="btn-atz"><i class="bi bi-pencil-square"></i></a>
                                                <a href="{{ route('ExibirCategoria', ['id' => Crypt::encrypt($categoria['idCategorias'])]) }}"
                                                    class="btn-exibir"><i class="bi bi-eye"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <div class="content-item" id="content-1">
                <form method="post" action="{{ route('deletarProdutoConfirmar') }}">
                    @csrf
                    <div class="action-buttons">
                        <a href="{{ route('novoProduto') }}" class="btn-new"><i class="bi bi-plus-lg"></i> Novo</a>
                        <button name="deletarProduto" class="deletar"><i class="bi bi-trash-fill"></i></button>
                        <div class="div-pesquisa">
                            <input id="pesquisarProduto" class="input-pesquisa" type="text"
                                placeholder="Pesquisar..." value="{{ $pesquisarProduto ?? '' }}">
                            <button type="button" class="btn-pesquisa" data-route="{{ route('home') }}"
                                data-input="pesquisarProduto">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="table-style">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nome do Produto</th>
                                    <th>Peso</th>
                                    <th>Unidade de apresentação</th>
                                    <th>Categoria</th>
                                    <th>Marca</th>
                                    <th>Preço</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados['produtos'] as $produto)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="itensProduto[]"
                                                value="{{ Crypt::encrypt($produto['idProdutos']) }}">
                                        </td>
                                        <td>{{ $produto['nomeProduto'] }}</td>
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
                                                $unidadeOriginal = strtolower($produto['UnidadeMedida']);
                                                $unidadeFormatada =
                                                    $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal);
                                                $plural =
                                                    $produto['tamanho'] != 1 &&
                                                    array_key_exists($unidadeOriginal, $mapaUnidades)
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
                                            {{ $produto['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}
                                        </td>
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
                                        <td>{{ $produto['categorias'][0]['nomeCategoria'] ?? '' }}</td>
                                        <td>{{ $produto['marcas'][0]['nomeMarca'] ?? '' }}</td>
                                        <td>R${{ $produto['precoProduto'] }}</td>
                                        <td>
                                            <div class="btn-wrapper">
                                                <a href="{{ route('atualizarProduto', ['id' => Crypt::encrypt($produto['idProdutos'])]) }}"
                                                    class="btn-atz"><i class="bi bi-pencil-square"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="content-item" id="content-3">
                <form method="post" action="{{ route('deletarMarcaConfirmar') }}">
                    @csrf
                    <div class="action-buttons">
                        <a href="{{ route('novaMarca') }}" class="btn-new"><i class="bi bi-plus-lg"></i> Novo</a>
                        <button name="deletarMarca" class="deletar"><i class="bi bi-trash-fill"></i></button>
                        <a href="{{ route('AssociarMarca') }}" class="btn-insert"><i
                                class="bi bi-box-arrow-in-left"></i> Inserir produto</a>
                        <div class="div-pesquisa">
                            <input id="pesquisarMarca" class="input-pesquisa" type="text" placeholder="Pesquisar..."
                                value="{{ $pesquisarMarca ?? '' }}">
                            <button type="button" class="btn-pesquisa" data-route="{{ route('home') }}"
                                data-input="pesquisarMarca">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="table-style">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nome da Marca</th>
                                    <th>Descrição</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados['marcas'] as $marca)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="itensMarca[]"
                                                value="{{ Crypt::encrypt($marca['idMarca']) }}">
                                        </td>
                                        <td>{{ $marca['nomeMarca'] }}</td>
                                        <td>{{ $marca['descricao'] }}</td>
                                        <td>
                                            <div class="btn-wrapper">
                                                <a href="{{ route('atualizarMarca', ['id' => Crypt::encrypt($marca['idMarca'])]) }}"
                                                    class="btn-atz"><i class="bi bi-pencil-square"></i></a>
                                                <a href="{{ route('ExibirMarca', ['id' => Crypt::encrypt($marca['idMarca'])]) }}"
                                                    class="btn-exibir"><i class="bi bi-eye"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="content-item" id="content-4">
                <form method="post" action="{{ route('deletarFornecedorConfirmar') }}">
                    @csrf
                    <div class="action-buttons">
                        <a href="{{ route('novoFornecedor') }}" class="btn-new">
                            <i class="bi bi-plus-lg"></i> Novo
                        </a>
                        <button name="deletarFornecedor" class="deletar"><i class="bi bi-trash-fill"></i></button>
                        <div class="div-pesquisa">
                            <input id="pesquisarFornecedor" class="input-pesquisa" type="text"
                                placeholder="Pesquisar..." value="{{ $pesquisarFornecedor ?? '' }}">
                            <button type="button" class="btn-pesquisa" data-route="{{ route('home') }}"
                                data-input="pesquisarFornecedor">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="table-style">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nome do Fornecedor</th>
                                    <th>CNPJ</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Endereço</th>
                                    <th>Numero de empenho</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados['fornecedores'] as $fornecedor)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="itensFornecedor[]"
                                                value="{{ Crypt::encrypt($fornecedor['idFornecedor']) }}">
                                        </td>
                                        <td>{{ $fornecedor['nomeFornecedor'] }}</td>
                                        <td>{{ $fornecedor['cnpj'] }}</td>
                                        <td>{{ $fornecedor['email'] }}</td>
                                        <td>{{ $fornecedor['telefone'] }}</td>
                                        <td>{{ $fornecedor['endereco'] }}</td>
                                        <td>{{ $fornecedor['Num_Empenho'] }}</td>
                                        <td>
                                            <div class="btn-wrapper">
                                                <a href="{{ route('atualizarFornecedor', ['id' => Crypt::encrypt($fornecedor['idFornecedor'])]) }}"
                                                    class="btn-atz"><i class="bi bi-pencil-square"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout.main-layout>
