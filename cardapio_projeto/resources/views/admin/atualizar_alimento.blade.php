<x-layout.main_layout>
    <x-slot:title>Atualizar alimento</x-slot:title>
    <x-slot:css>{{ asset('assets/css/adm.css') }}</x-slot:css>
    <h2>Atualizar Alimento</h2>
    @if (session('criacao_valida'))
        <div class="alert alert-success text-center px-4 py-3">
            {{ session('criacao_valida') }}
        </div>
    @endif

    <form action="{{ route('EditarAlimentoSubmit') }}" method="post" class="food-form">
        @csrf
        <input type="hidden" name="id" value="{{ $dados['alimento']['idAlimento'] }}">

        <div class="form-group">
            <label for="nome">Nome do alimento</label>
            <input type="text" id="nome" name="text_nome_alimento" placeholder="Ex: Arroz integral"
                value="{{ old('text_nome_alimento', $dados['alimento']['nomeAlimento']) }}" required>
            @error('text_nome_alimento')
                <small class="text-danger d-block ms-2">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="ingredientes">Ingredientes</label>
            <input type="text" id="ingredientes" name="text_ingredientes" placeholder="Ex: Arroz, sal, óleo..."
                value="{{ old('text_ingredientes', $dados['alimento']['ingredientes']) }}" required>
            @error('text_ingredientes')
                <small class="text-danger d-block ms-2">{{ $message }}</small>
            @enderror
        </div>

        <fieldset class="macros">
            <legend>Informações Nutricionais</legend>
            <div class="macro-grid">
                <div class="form-group">
                    <input type="hidden" name="id" value="{{$dados['alimento']['idAlimento']}}">
                    <label for="calorias">Calorias (kcal)</label>
                    <input type="number" id="calorias" name="text_calorias"
                        value="{{ old('text_calorias', $dados['alimento']['calorias']) }}" required>
                    @error('text_calorias')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="carboidratos">Carboidratos (g)</label>
                    <input type="number" id="carboidratos" name="text_carboidratos"
                        value="{{ old('text_carboidratos', $dados['alimento']['carboidratos']) }}" required>
                    @error('text_carboidratos')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="proteinas">Proteínas (g)</label>
                    <input type="number" id="proteinas" name="text_proteinas"
                        value="{{ old('text_proteinas', $dados['alimento']['proteinas']) }}" required>
                    @error('text_proteinas')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gorduras">Gorduras Totais (g)</label>
                    <input type="number" id="gorduras" name="text_gorduras_totais"
                        value="{{ old('text_gorduras_totais', $dados['alimento']['gorduras_totais']) }}" required>
                    @error('text_gorduras_totais')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </fieldset>

        <fieldset class="alergias">
            <legend>Alergênicos</legend>

            <div class="radio-group">
                <span>Contém glúten?</span>
                <label>
                    <input type="radio" name="text_gluten" value="1"
                        {{ old('text_gluten', $dados['alimento']['gluten']) == 1 ? 'checked' : '' }}> Sim
                </label>
                <label>
                    <input type="radio" name="text_gluten" value="0"
                        {{ old('text_gluten', $dados['alimento']['gluten']) == 0 ? 'checked' : '' }}> Não
                </label>
                @error('text_gluten')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
            </div>

            @php
                $alergiasSelecionadas = old('itensAlergias', $dados['alimento']['alergicos'] ?? []);
            @endphp

            <div class="checkbox-grid">
                <label><input type="checkbox" name="itensAlergias[]" value="leite" {{ in_array('leite', $alergiasSelecionadas) ? 'checked' : '' }}> Leite</label>
                <label><input type="checkbox" name="itensAlergias[]" value="trigo" {{ in_array('trigo', $alergiasSelecionadas) ? 'checked' : '' }}> Trigo</label>
                <label><input type="checkbox" name="itensAlergias[]" value="centeio" {{ in_array('centeio', $alergiasSelecionadas) ? 'checked' : '' }}> Centeio</label>
                <label><input type="checkbox" name="itensAlergias[]" value="cevada" {{ in_array('cevada', $alergiasSelecionadas) ? 'checked' : '' }}> Cevada</label>
                <label><input type="checkbox" name="itensAlergias[]" value="aveia" {{ in_array('aveia', $alergiasSelecionadas) ? 'checked' : '' }}> Aveia</label>
                <label><input type="checkbox" name="itensAlergias[]" value="crustaceos" {{ in_array('crustaceos', $alergiasSelecionadas) ? 'checked' : '' }}> Crustáceos</label>
                <label><input type="checkbox" name="itensAlergias[]" value="ovo" {{ in_array('ovo', $alergiasSelecionadas) ? 'checked' : '' }}> Ovo</label>
                <label><input type="checkbox" name="itensAlergias[]" value="peixe" {{ in_array('peixe', $alergiasSelecionadas) ? 'checked' : '' }}> Peixe</label>
                <label><input type="checkbox" name="itensAlergias[]" value="amendoim" {{ in_array('amendoim', $alergiasSelecionadas) ? 'checked' : '' }}> Amendoim</label>
                <label><input type="checkbox" name="itensAlergias[]" value="amendoas" {{ in_array('amendoas', $alergiasSelecionadas) ? 'checked' : '' }}> Amêndoas</label>
                <label><input type="checkbox" name="itensAlergias[]" value="avelas" {{ in_array('avelas', $alergiasSelecionadas) ? 'checked' : '' }}> Avelãs</label>
                <label><input type="checkbox" name="itensAlergias[]" value="castanha-caju" {{ in_array('castanha-caju', $alergiasSelecionadas) ? 'checked' : '' }}> Castanha de caju</label>
                <label><input type="checkbox" name="itensAlergias[]" value="castanha-para" {{ in_array('castanha-para', $alergiasSelecionadas) ? 'checked' : '' }}> Castanha do Pará</label>
                <label><input type="checkbox" name="itensAlergias[]" value="macadamias" {{ in_array('macadamias', $alergiasSelecionadas) ? 'checked' : '' }}> Macadâmias</label>
                <label><input type="checkbox" name="itensAlergias[]" value="nozes" {{ in_array('nozes', $alergiasSelecionadas) ? 'checked' : '' }}> Nozes</label>
                <label><input type="checkbox" name="itensAlergias[]" value="pecas" {{ in_array('pecas', $alergiasSelecionadas) ? 'checked' : '' }}> Pecãs</label>
                <label><input type="checkbox" name="itensAlergias[]" value="pistache" {{ in_array('pistache', $alergiasSelecionadas) ? 'checked' : '' }}> Pistache</label>
                <label><input type="checkbox" name="itensAlergias[]" value="castanhas" {{ in_array('castanhas', $alergiasSelecionadas) ? 'checked' : '' }}> Outras castanhas</label>
                <label><input type="checkbox" name="itensAlergias[]" value="sulfitos" {{ in_array('sulfitos', $alergiasSelecionadas) ? 'checked' : '' }}> Sulfitos</label>
            </div>
        </fieldset>

        <div class="button-group">
            <a href="{{ route('home') }}" class="btn-cancel"><i class="bi bi-x-circle"></i> Cancelar</a>
            <button type="submit" class="btn-submit"><i class="bi bi-check-circle"></i> Atualizar</button>
        </div>
    </form>
    </div>
    </main>

    <script>
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            document.querySelector('.nav-list').classList.toggle('active');
        });

        document.querySelectorAll('.nav-list a').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.querySelector('.nav-list').classList.remove('active');
                }
            });
        });
    </script>
</x-layout.main_layout>
