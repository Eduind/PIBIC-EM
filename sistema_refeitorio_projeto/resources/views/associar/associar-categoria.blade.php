<x-layout.layout-secundario>
    <x-slot:title>Associar Categoria</x-slot:title>
    <div class="form-container">
        @if (session('criacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('criacao_valida') }}
            </div>
        @endif
        <h2>Associar Categoria</h2>
        <form method="post" action="{{ route('AssociarCategoriaSubmit') }}">
            @csrf
            <select name="id_categoria" required>
                @if (count($dados['categorias']) === 0)
                    <option value="" selected disabled>Nenhuma categoria para associar</option>
                @else
                    @foreach ($dados['categorias'] as $categoria)
                        <option value="{{ Crypt::encrypt($categoria['idCategorias']) }}">
                            {{ $categoria['nomeCategoria'] }}
                        </option>
                    @endforeach
                @endif
            </select>
            <select name="id_produto" required>
                @if (count($dados['produtos']) === 0)
                    <option value="" selected disabled>Nenhum produto para associar</option>
                @else
                    @foreach ($dados['produtos'] as $produtos)
                        <option value="{{ Crypt::encrypt($produtos['idProdutos']) }}">
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
                            @endphp
                            {{ $produtos['nomeProduto'] }} de tamanho {{ $produtos['tamanho'] }}
                            {{ $unidadeFormatada }}{{ $plural }}
                        </option>
                    @endforeach
                @endif
            </select>
            <div class="button-group">
                <a href="{{ route('home') }}" class="btn-cancel"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
                <button type="submit" class="btn-submit"><i class="fa-regular fa-circle-check me-2"></i>Criar</button>
            </div>
        </form>
    </div>
</x-layout.layout-secundario>
