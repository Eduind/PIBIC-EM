<x-layout.main-layout>
    <x-slot:title>Confirmar Exclusão</x-slot:title:>
    <div class="confirm-card">
        <h2 class="confirm-title">
            <i class="bi bi-exclamation-triangle-fill"></i> Atenção!
        </h2>
        <p class="confirm-text">Tem certeza de que deseja excluir o(s) produto(s) abaixo?</p>
        <ul class="confirm-list">
            @foreach ($dados['produtos'] as $produtos)
                @php
                    $mapaUnidades = [
                        'sache' => 'Sachê',
                        'bandeja' => 'Bandeja',
                        'placa' => 'Placa',
                        'pacote' => 'Pacote',
                        'lata' => 'Lata',
                        'pote' => 'Pote',
                        'balde' => 'Balde',
                    ];
                    $unidadeOriginal = strtolower($produtos['UnidadeMedida']);
                    $unidadeFormatada = $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal);
                    $plural = $produtos['tamanho'] != 1 && array_key_exists($unidadeOriginal, $mapaUnidades) ? 's' : '';
                @endphp
                <li>{{  $produtos['nomeProduto'] }} | {{ $produtos['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} | {{ $produtos['nomeMarca'] ? "Marca: {$produtos['nomeMarca']}" : 'nenhuma marca associada' }}</li>
            @endforeach
        </ul>

        <form method="post" action="{{ route('deletarProduto') }}">
            @csrf
            @foreach ($dados['idsSelecionados'] as $id)
                <input type="hidden" name="itensProdutos[]" value="{{ $id }}">
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
