<x-layout.layout-secundario>
    <x-slot:title>Restaurar categoria</x-slot:title>
    <div class="form-container">
        <h2>Restaurar Categoria</h2>
        <div class="botao">
            <form action="{{ route('restaurarCategoriaSubmit') }}" method="post">
                @csrf
                <div class='content'>
                    @foreach ($dados['categorias'] as $categoria)
                        <label>
                            <input type="checkbox" name="restaurarCategoria[]" value="{{ $categoria['idCategorias'] }}">
                            <span>{{ $categoria['nomeCategoria'] }}</span>
                        </label>
                    @endforeach
                    <div>
                        <div class="button-group">
                            <a href="{{ route('home') }}" class="btn-cancel"><i
                                    class="fa-solid fa-ban me-2"></i>Cancelar</a>
                            <button type="submit" class="btn-submit"><i
                                    class="fa-regular fa-circle-check me-2"></i>Recuperar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout.layout-secundario>
