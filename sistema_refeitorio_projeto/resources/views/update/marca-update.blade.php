<x-layout.layout-secundario>
    <x-slot:title>Nova marca</x-slot:title>
    <div class="form-container">
        @if(session('atualizacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('atualizacao_valida') }}
            </div>
        @endif
        <h2>Registrar Marca</h2>
        <div class="botao">
            <form action="{{route('atualizarMarcaSubmit')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$dados['id']}}">
                <input type="text" name="text_name" value="{{ old('text_name', $dados['marca']['nomeMarca']) }}" placeholder="Nome da marca" required>
                @error('text_name')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_descricao" value="{{old('text_descricao',  $dados['marca']['descricao'])}}" placeholder="Descrição" required>
                @error('text_descricao')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <div class="button-group">
                    <a href="{{route('home')}}" class="btn-cancel"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
                    <button type="submit" class="btn-submit"><i class="fa-regular fa-circle-check me-2"></i>Criar</button>
                </div>
            </form>
        </div>
    </div>
</x-layout.layout-secundario>
