<x-layout.layout-secundario>
    <x-slot:title>Nova categoria</x-slot:title>
    <div class="form-container">
        @if (session('criacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('criacao_valida') }}
            </div>
        @endif
        @if (session('criacao_falha'))
            <div class="alert alert-danger text-center px-4 py-3">
                {{ session('criacao_falha') }}
            </div>
        @endif
        <h2>Registrar Saida</h2>
        <div class="botao">
            <form action="{{ route('novaSaidaSubmit') }}" method="post">
                @csrf
                <select name="id_produto">
                    @if (count($dados['produtos']) === 0)
                        <option value="" selected disabled>Nenhum produto encontrado</option>
                    @else
                        @foreach ($dados['produtos'] as $produtos)
                            @php
                                $dataValidade = $produtos['dataValidade']
                                    ? date('d/m/Y', strtotime($produtos['dataValidade']))
                                    : 'N/A';
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
                            <option value="{{ Crypt::encrypt($produtos['idProdutos']) }}">
                                {{ $produtos['nomeProduto'] }} {{ $produtos['tamanho'] }}
                                {{ $unidadeFormatada }}{{ $plural }}
                                {{ $produtos['nomeMarca'] ? "da marca {$produtos['nomeMarca']}" : 'NDA' }}
                                (E: {{ $produtos['qtdTotalLotes'] }}, V: {{ $dataValidade }})
                            </option>
                        @endforeach
                    @endif
                </select>
                <input type="date" name="text_datasaida" value="{{ old('text_datasaida') }}"
                    placeholder="Data da Saída" required>
                @error('text_datasaida')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_qtd" value="{{ old('text_qtd') }}" placeholder="Quantidade" required>
                @error('text_qtd')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <select name="destino">
                    <option value="1">Descarte</option>
                    <option value="2">Cozinha</option>
                </select>
                @error('destino')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <div class="button-group">
                    <a href="{{ route('movimentacao') }}" class="btn-cancel"><i
                            class="fa-solid fa-ban me-2"></i>Cancelar</a>
                    <button type="submit" class="btn-submit"><i
                            class="fa-regular fa-circle-check me-2"></i>Criar</button>
                </div>
            </form>
        </div>
    </div>
</x-layout.layout-secundario>
