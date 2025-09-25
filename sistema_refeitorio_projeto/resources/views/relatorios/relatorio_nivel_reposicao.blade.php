<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Nível de Reposição</title>
    <link rel="stylesheet" href="{{asset('css/relatorios.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo_ifba.png')}}" type="image/png">
</head>

<body>

    <div id="relatorio">
        <div class="nome-relatorio">
            <h1>Relatório de Nível de Reposição</h1>
            <p>Data de emissão: {{ date('d/m/Y') }}</p>
        </div>

        <div class="tabela">
            <table>
                <thead>
                    <tr>
                        <th>Nome do Produto</th>
                        <th>Tamanho/Unidade</th>
                        <th>Quantidade Disponível</th>
                        <th>Nome da Marca</th>
                        <th>Data de Validade Mais Próxima</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dados['produtos'] as $produto)
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

                            $unidadeOriginal = strtolower($produto['UnidadeMedida'] ?? '');
                            $unidadeFormatada =
                                $mapaUnidades[$unidadeOriginal] ?? ucfirst($unidadeOriginal ?: 'Unidade');
                            $plural =
                                ($produto['tamanho'] ?? 0) != 1 && array_key_exists($unidadeOriginal, $mapaUnidades)
                                    ? 's'
                                    : '';

                            $peso_liquido = '';
                            if (!empty($produto['pesoLiquido']) && !empty($produto['tamanho'])) {
                                $pesoTotal = ($produto['pesoLiquido'] / 1000) * $produto['tamanho'];
                                $peso_liquido = number_format($pesoTotal, 2, ',', '.') . ' kg no total';
                            }
                            $validade = \Carbon\Carbon::parse($produto['dataValidade']);
                            $status = 'Necessário Repor';
                        @endphp
                        <tr class="critico">
                            <td>{{ $produto['nomeProduto'] }}</td>
                            <td>{{ $produto['tamanho'] }} {{ $unidadeFormatada }}{{ $plural }} {{ $peso_liquido }}</td>
                            <td>{{ $produto['qtdEstoque'] }} unidades</td>
                            <td>{{ $produto['nomeMarca'] }}</td>
                            <td>{{ $validade->format('d/m/Y') }}</td>
                            <td>{{ $status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                Todos os produtos estão acima do nível mínimo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="botoes-container">
        <a href="{{ route('relatorios') }}" class="botao voltar-btn">Voltar</a>
        <a href="{{ route('NivelExcel',['produtos' => Crypt::encrypt($dados['produtos'])]) }}" class="botao exportar-btn">Exportar Excel</a>
        <a href="{{ route('NivelPDF', ['produtos' => Crypt::encrypt($dados['produtos'])])}}" class="botao pdf-btn">Exportar PDF</a>
    </div>
</body>
</html>
