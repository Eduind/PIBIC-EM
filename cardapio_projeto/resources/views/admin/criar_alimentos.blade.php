<x-layout.main_layout>
    <x-slot:title>Criar alimento</x-slot:title>
    <x-slot:css>{{ asset('assets/css/adm.css') }}</x-slot:css>
    <h2>Criar Alimento</h2>
    @if (session('criacao_valida'))
        <div class="alert alert-success text-center px-4 py-3">
            {{ session('criacao_valida') }}
        </div>
    @endif
    <form action="{{ route('CriarAlimentosSubmit') }}" method="post" class="food-form">
        @csrf
        <div class="form-group">
            <label for="nome">Nome do alimento</label>
            <input type="text" id="nome" name="text_nome_alimento" placeholder="Ex: Arroz integral"
                value="{{ old('text_nome_alimento') }}" required>
            @error('text_nome_alimento')
                <small class="text-danger d-block ms-2">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="ingredientes">Ingredientes</label>
            <input type="text" id="ingredientes" name="text_ingredientes" placeholder="Ex: Arroz, sal, óleo..."
                value="{{ old('text_ingredientes') }}" required>
            @error('text_ingredientes')
                <small class="text-danger d-block ms-2">{{ $message }}</small>
            @enderror
        </div>

        <fieldset class="macros">
            <legend>Informações Nutricionais</legend>
            <div class="macro-grid">
                <div class="form-group">
                    <label for="calorias">Calorias (kcal)</label>
                    <input type="number" id="calorias" name="text_calorias" value="{{ old('text_calorias') }}"
                        required>
                    @error('text_calorias')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="carboidratos">Carboidratos (g)</label>
                    <input type="number" id="carboidratos" name="text_carboidratos"
                        value="{{ old('text_carboidratos') }}" required>
                    @error('text_carboidratos')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="proteinas">Proteínas (g)</label>
                    <input type="number" id="proteinas" name="text_proteinas" value="{{ old('text_proteinas') }}"
                        required>
                    @error('text_proteinas')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gorduras">Gorduras Totais (g)</label>
                    <input type="number" id="gorduras" name="text_gorduras_totais"
                        value="{{ old('text_gorduras_totais') }}" required>
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
                <label><input type="radio" name="text_gluten" value="1"> Sim</label>
                <label><input type="radio" name="text_gluten" value="0"> Não</label>
                @error('text_gluten')
                    <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
            </div>

            <div class="checkbox-grid">
                <label><input type="checkbox" name="itensAlergias[]" value="leite"> Leite</label>
                <label><input type="checkbox" name="itensAlergias[]" value="trigo"> Trigo</label>
                <label><input type="checkbox" name="itensAlergias[]" value="centeio"> Centeio</label>
                <label><input type="checkbox" name="itensAlergias[]" value="cevada"> Cevada</label>
                <label><input type="checkbox" name="itensAlergias[]" value="aveia"> Aveia</label>
                <label><input type="checkbox" name="itensAlergias[]" value="crustaceos"> Crustáceos</label>
                <label><input type="checkbox" name="itensAlergias[]" value="ovo"> Ovo</label>
                <label><input type="checkbox" name="itensAlergias[]" value="peixe"> Peixe</label>
                <label><input type="checkbox" name="itensAlergias[]" value="amendoim"> Amendoim</label>
                <label><input type="checkbox" name="itensAlergias[]" value="amendoas"> Amêndoas</label>
                <label><input type="checkbox" name="itensAlergias[]" value="avelas"> Avelãs</label>
                <label><input type="checkbox" name="itensAlergias[]" value="castanha-caju"> Castanha de caju</label>
                <label><input type="checkbox" name="itensAlergias[]" value="castanha-para"> Castanha do Pará</label>
                <label><input type="checkbox" name="itensAlergias[]" value="macadamias"> Macadâmias</label>
                <label><input type="checkbox" name="itensAlergias[]" value="nozes"> Nozes</label>
                <label><input type="checkbox" name="itensAlergias[]" value="pecas"> Pecãs</label>
                <label><input type="checkbox" name="itensAlergias[]" value="pistache"> Pistache</label>
                <label><input type="checkbox" name="itensAlergias[]" value="castanhas"> Outras castanhas</label>
                <label><input type="checkbox" name="itensAlergias[]" value="sulfitos"> Sulfitos</label>
            </div>
        </fieldset>

        <div class="button-group">
            <a href="{{ route('home') }}" class="btn-cancel"><i class="bi bi-x-circle"></i> Cancelar</a>
            <button type="submit" class="btn-submit"><i class="bi bi-check-circle"></i> Criar</button>
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
'
