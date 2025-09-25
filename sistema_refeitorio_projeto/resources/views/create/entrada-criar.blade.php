<x-layout.layout-secundario>
    <x-slot:title>Novo fornecedor</x-slot:title>
    <div class="form-container">
        @if (session('criacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('criacao_valida') }}
            </div>
        @endif
        <h2>Registrar Entrada</h2>
        <div class="botao">
            <form action="{{ route('novaEntradaSubmit') }}" method="post">
                @csrf
                <select name="id_produto" required>
                @if (count($dados['produtos']) === 0)
                    <option value="" selected disabled>Nenhum produto encontrado</option>
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
                            {{ $produtos['nomeMarca'] ? "da marca {$produtos['nomeMarca']}" : 'com nenhuma marca associada' }}
                        </option>
                    @endforeach
                @endif
                </select>
                @error('id_produto')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <select name="fornecedor" required>
                    @if (count($dados['fornecedor']) === 0)
                        <option value="" selected disabled>Nenhum fornecedor encontrado</option>
                    @else
                        @foreach ($dados['fornecedor'] as $fornecedor)
                            <option value="{{ Crypt::encrypt($fornecedor['idFornecedor']) }}">
                                {{ $fornecedor['nomeFornecedor'] }} </option>
                        @endforeach
                    @endif
                </select>
                @error('fornecedor')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_qtd" placeholder="Quantidade" value="{{old('text_qtd')}}" required>
                @error('text_qtd')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_notafiscal" placeholder="Número da nota fiscal" value="{{old('text_notafiscal')}}" required>
                @error('text_notafiscal')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <div class="input-group">
                    <small>Data de entrada</small>
                    <input type="date" name="text_dataentrada" value="{{ old('text_dataentrada') }}" required>
                </div>
                @error('text_dataentrada')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <div class="input-group">
                    <small>Data de validade</small>
                    <input type="date" name="text_datavalidade" value="{{ old('text_datavalidade') }}" required>
                </div>
                @error('text_datavalidade')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <div class="button-group">
                    <a href="{{ route('movimentacao') }}" class="btn-cancel"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
                    <button type="submit" class="btn-submit"><i class="fa-regular fa-circle-check me-2"></i>Criar</button>
                </div>
            </form>
        </div>
    </div>
</x-layout.layout-secundario>
