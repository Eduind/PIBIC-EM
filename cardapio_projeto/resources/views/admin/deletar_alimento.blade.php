<x-layout.main_layout>
    <x-slot:title>Visualizar alimentos</x-slot:title>
    <x-slot:css>{{ asset('assets/css/adm.css') }}</x-slot:css>
    <div class="confirm-card">
        <h2 class="confirm-title">
            <i class="bi bi-exclamation-triangle-fill"></i> Atenção!
        </h2>

        <p class="confirm-text">Tem certeza de que deseja excluir a(s) categoria(s) abaixo?</p>

        <ul class="confirm-list">
            <li>{{ $dados['alimento']['nomeAlimento'] }}</li>
        </ul>

        <form method="post" action="{{ route('DeletarAlimentosConfirm') }}">
            @csrf
            <input type="hidden" name="id_alimento" value="{{$id}}">

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
</x-layout.main_layout>
