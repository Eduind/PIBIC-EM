<x-layout.main-layout>
    <x-slot:title>Confirmar Exclusão</x-slot:title:>
    <div class="confirm-card">
        <h2 class="confirm-title">
            <i class="bi bi-exclamation-triangle-fill"></i> Atenção!
        </h2>

        <p class="confirm-text">Tem certeza de que deseja excluir o(s) fornecedor(es) abaixo?</p>

        <ul class="confirm-list">
            @foreach ($dados['fornecedores'] as $fornecedor)
                <li>{{ $fornecedor['nomeFornecedor'] }}</li>
            @endforeach
        </ul>

        <form method="post" action="{{ route('deletarFornecedor') }}">
            @csrf
            @foreach ($dados['idsSelecionados'] as $id)
                <input type="hidden" name="itensFornecedor[]" value="{{ $id }}">
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
