<x-layout.layout-secundario>
    <x-slot:title>Atualizar produto</x-slot:title>
    <div class="form-container">
        @if(session('atualizacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('atualizacao_valida') }}
            </div>
        @endif
        <h2>Atualizar Produto</h2>
        <div class="botao">
            <form action="{{route('atualizarProdutoSubmit')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$dados['id']}}">

                <input type="text" name="text_name" value="{{old('text_name', $dados['produto']['nomeProduto']) }}" placeholder="Nome do Produto" required>
                @error('text_name')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror

                <input type="text" name="text_tamanho" value="{{old('text_tamanho',$dados['produto']['tamanho'])}}" placeholder="Tamanho do produto" required>
                @error('text_tamanho')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror

                <input type="text" name="text_preco" value="{{old('text_preco',$dados['produto']['precoProduto'])}}" placeholder="Preço do produto" required>
                @error('text_preco')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror

                <input type="text" name="text_qtdminima" value="{{old('text_qtdminima',$dados['produto']['qtdMinima'])}}" placeholder="Quantidade mínima permitida" required>
                @error('text_qtdminima')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror

                <div class="form-group">
                    <small for="unidadeMedida">Unidade de apresentação</small>
                    <select name="unidade" id="unidadeMedida" required>
                        <option value="">Selecione...</option>
                        <option value="kg" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="g" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'g' ? 'selected' : '' }}>Gramas</option>
                        <option value="l" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'l' ? 'selected' : '' }}>Litros</option>
                        <option value="ml" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'ml' ? 'selected' : '' }}>Mililitros</option>
                        <option value="bandeja" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'bandeja' ? 'selected' : '' }}>Bandeja</option>
                        <option value="placa" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'placa' ? 'selected' : '' }}>Placa</option>
                        <option value="pacote" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'pacote' ? 'selected' : '' }}>Pacote</option>
                        <option value="sache" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'sache' ? 'selected' : '' }}>Sachê</option>
                        <option value="lata" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'lata' ? 'selected' : '' }}>Lata</option>
                        <option value="pote" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'pote' ? 'selected' : '' }}>Pote</option>
                        <option value="balde" {{ old('unidade',$dados['produto']['UnidadeMedida']) == 'balde' ? 'selected' : '' }}>Balde</option>
                    </select>
                </div>
                @error('unidade')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror

                <div class="form-group" id="campoPesoLiquido" style="display:none;">
                    <label for="pesoLiquido">Peso líquido por unidade (g)</label>
                    <input type="number" step="0.01" name="peso_liquido" id="pesoLiquido" min="1"
                           value="{{ old('peso_liquido',$dados['produto']['pesoLiquido'] ?? '') }}"
                           placeholder="Ex: 1000 para 1kg">
                </div>

                <div class="button-group">
                    <a href="{{route('home')}}" class="btn-cancel"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
                    <button type="submit" class="btn-submit"><i class="fa-regular fa-circle-check me-2"></i>Atualizar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectUnidade = document.getElementById('unidadeMedida');
            const campoPeso = document.getElementById('campoPesoLiquido');
            const inputPeso = document.getElementById('pesoLiquido');

            const unidadesQuePrecisamDePeso = ["bandeja", "placa", "pacote", "sache", "lata", "pote", "balde"];

            function togglePesoField() {
                if (unidadesQuePrecisamDePeso.includes(selectUnidade.value)) {
                    campoPeso.style.display = 'block';
                    inputPeso.required = true;
                } else {
                    campoPeso.style.display = 'none';
                    inputPeso.required = false;
                    inputPeso.value = '';
                }
            }

            selectUnidade.addEventListener('change', togglePesoField);
            togglePesoField();
        });
    </script>
</x-layout.layout-secundario>
