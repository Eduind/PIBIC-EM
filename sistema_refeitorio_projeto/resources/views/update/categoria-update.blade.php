<x-layout.layout-secundario>
    <x-slot:title>Atualizar categoria</x-slot:title>
    <div class="form-container">
        @if(session('atualizacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('atualizacao_valida') }}
            </div>
        @endif
        <h2>Atualizar Categoria</h2>
        <div class="botao">
            <form action="{{route('atualizarCategoriaSubmit')}}" method="post">
                @csrf
                <div>
                    <input type="hidden" name="id" value="{{$dados['id']}}">
                    <input type="text" name="text_name" value="{{ old('text_name', $dados['categoria']['nomeCategoria']) }}" placeholder="Nome da categoria" required>
                    @error('text_name')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                    @enderror
                <div>
                <div class="button-group">
                    <a href="{{route('home')}}" class="btn-cancel"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
                    <button type="submit" class="btn-submit"><i class="fa-regular fa-circle-check me-2"></i>Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</x-layout.layout-secundario>
