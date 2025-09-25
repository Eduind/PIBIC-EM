<x-layout.layout-secundario>
    <x-slot:title>Novo produto</x-slot:title>
    <div class="form-container">
        @if (session('criacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('criacao_valida') }}
            </div>
        @endif
        <h2>Registrar Produto</h2>
        <div class="botao">
            <form action="{{ route('novoProdutoSubmit') }}" method="post">
                @csrf
                <input type="text" name="text_name" value="{{ old('text_name') }}" placeholder="Nome do Produto"
                    required>
                @error('text_name')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_tamanho" value="{{ old('text_tamanho') }}"
                    placeholder="Tamanho do produto" required>
                @error('text_tamanho')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_preco" value="{{ old('text_tamanho') }}" placeholder="Preço do produto"
                    required>
                @error('text_preco')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_qtdminima" value="{{ old('text_qtdminima') }}"
                    placeholder="Quantidade mínima permitida" required>
                @error('text_qtdminima')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <div class="form-group">
                    <small for="unidadeMedida">Unidade de apresentação</small>
                    <select name="unidade" id="unidadeMedida" required>
                        <option value="">Selecione...</option>
                        <option value="kg">Kg</option>
                        <option value="g">Gramas</option>
                        <option value="l">Litros</option>
                        <option value="ml">Mililitros</option>
                        <option value="bandeja">Bandeja</option>
                        <option value="placa">Placa</option>
                        <option value="pacote">Pacote</option>
                        <option value="sache">Sachê</option>
                        <option value="lata">Lata</option>
                        <option value="pote">Pote</option>
                        <option value="balde">Balde</option>
                    </select>
                </div>

                <div class="form-group" id="campoPesoLiquido" style="display:none;">
                    <label for="pesoLiquido">Peso líquido por unidade (g)</label>
                    <input type="number" name="peso_liquido" id="pesoLiquido" min="1" placeholder="Ex: 1000 para 1kg">
                </div>
                @error('pesoLiquido')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror

                <div class="button-group">
                    <a href="{{ route('home') }}" class="btn-cancel"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
                    <button type="submit" class="btn-submit"><i
                            class="fa-regular fa-circle-check me-2"></i>Criar</button>
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

            selectUnidade.addEventListener('change', function() {
                if (unidadesQuePrecisamDePeso.includes(this.value)) {
                    campoPeso.style.display = 'block';
                    inputPeso.required = true;
                } else {
                    campoPeso.style.display = 'none';
                    inputPeso.required = false;
                    inputPeso.value = '';
                }
            });
        });
    </script>
</x-layout.layout-secundario>
