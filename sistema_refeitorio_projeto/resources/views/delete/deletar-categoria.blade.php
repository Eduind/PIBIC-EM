<x-layout.main-layout>
    <x-slot:title>Confirmar Exclusão</x-slot:title:>
    <div class="confirm-card">
        <h2 class="confirm-title">
            <i class="bi bi-exclamation-triangle-fill"></i> Atenção!
        </h2>

        <p class="confirm-text">Tem certeza de que deseja excluir a(s) categoria(s) abaixo?</p>

        <ul class="confirm-list">
            @foreach ($dados['categorias'] as $categoria)
                <li>{{ $categoria['nomeCategoria'] }}</li>
            @endforeach
        </ul>

        <form method="post" action="{{ route('deletarCategoria') }}">
            @csrf
            @foreach ($dados['idsSelecionados'] as $id)
                <input type="hidden" name="itensCategoria[]" value="{{$id}}">
            @endforeach

            <div class="button-group">
                <a href="{{ route('home') }}" class="btn-cancel">
                    <i class="fa-solid fa-ban"></i> Cancelar
                </a>
                <button type="submit" class="deletar2">
                    <i class="bi bi-trash-fill"></i> Confirmar
                </button>
            </div>
        </form>
    </div>
</x-layout.layout-principal>
