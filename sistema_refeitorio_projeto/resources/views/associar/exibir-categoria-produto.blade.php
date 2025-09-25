<x-layout.layout-terciario>
    <x-slot:title>Exibir categoria</x-slot:title>
    <x-slot:text>Categoria - {{ $dados['categoria']['nomeCategoria'] }}</x-slot:text>
    @if (count($dados['produtos']) === 0)
        <td colspan="4">Nenhum registro encontrado.</td>
    @else
        @foreach ($dados['produtos'] as $produtos)
            <td>{{ $produtos['nomeProduto'] }}</td>
            <td>
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
                {{ $produtos['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }}</td>
            <td>@switch($produtos['UnidadeMedida'])
                    @case('g')
                        {{ $unidade = 'Grama' }}
                    @break

                    @case('kg')
                        {{ $unidade = 'Quilogramas' }}
                    @break

                    @case('L')
                        {{ $unidade = 'Litros' }}
                    @break

                    @case('ml')
                        {{ $unidade = 'Mililitros' }}
                    @break

                    @case('bandeja')
                        {{ $unidade = 'Bandejas' }}
                    @break

                    @case('placa')
                        {{ $unidade = 'Placas' }}
                    @break

                    @case('pacote')
                        {{ $unidade = 'Pacotes' }}
                    @break

                    @case('sache')
                        {{ $unidade = 'Sachês' }}
                    @break

                    @case('lata')
                        {{ $unidade = 'Latas' }}
                    @break

                    @case('pote')
                        {{ $unidade = 'Potes' }}
                    @break

                    @case('balde')
                        {{ $unidade = 'Baldes' }}
                    @break
                @endswitch
            </td>
            <td>
                <div class='btn-wrapper'>
                    <a class="btn-atz"
                        href="{{ route('ExibirCategoriaSubmit') .
                            '?' .
                            http_build_query([
                                'categoria' => Crypt::encrypt($dados['categoria']['idCategorias']),
                                'produto' => Crypt::encrypt($produtos['idProdutos']),
                            ]) }}">
                        <i class="bi-x-circle"></i>
                    </a>
                </div>
            </td>
        @endforeach
    @endif
</x-layout.layout-terciario>
