<x-layout.main_layout>
    <x-slot:title>Exibir cardapios</x-slot:title>
    <x-slot:css>{{ asset('assets/css/exibir_cardapio.css') }}</x-slot:css>

    <h2>Cardápios Cadastrados</h2>
    <div class="table-container">
        <table class="food-table">
            <thead>
                <tr>
                    <th>Nome do Cardápio</th>
                    <th>Periodo de duração</th>
                    <th>Dias</th>
                    <th>Status / Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['cardapios'] as $cardapios)
                    <tr>
                        <td>{{ $cardapios['nomeCardapio'] }}</td>
                        <td>{{ $cardapios['dataInicio'] }} até {{ $cardapios['dataFim'] }}</td>
                        <td>{{ implode(', ', $cardapios['dias']) }}</td>
                        <td class="actions">
                            <span class="badge {{ $cardapios['ativo'] ? 'badge-success' : 'badge-danger' }}">
                                {{ $cardapios['ativo'] ? 'Ativo' : 'Inativo' }}
                            </span>
                            <!-- Botão para abrir modal -->
                            <a href="#" class="btn-view" title="Visualizar"
                                onclick="openModal({{ $cardapios['idCardapio'] }})">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('EditarCardapios', ['id' => Crypt::encrypt($cardapios['idCardapio'])]) }}"
                                class="btn-edit" title="Editar"><i class="bi bi-pencil-square"></i></a>
                            <a href="{{ route('AtivarCardapio', ['id' => Crypt::encrypt($cardapios['idCardapio'])]) }}"
                                class="btn-delete" title="{{ $cardapios['ativo'] ? 'Desativar' : 'Ativar' }}">
                                {{ $cardapios['ativo'] ? '⛔' : '✅' }}
                            </a>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div id="modal-{{ $cardapios['idCardapio'] }}" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal({{ $cardapios['idCardapio'] }})">&times;</span>
                            <h3>Cardápio: {{ $cardapios['nomeCardapio'] }}</h3>
                            <p><strong>Período:</strong> {{ $cardapios['dataInicio'] }} até
                                {{ $cardapios['dataFim'] }}</p>

                            @php
                                $horarioNomes = [
                                    'matutino' => 'Matutino',
                                    'almoco' => 'Almoço',
                                    'vespertino' => 'Vespertino',
                                    'noturno' => 'Noturno',
                                ];
                            @endphp

                            @foreach ($cardapios['refeicoes'] as $dia => $horarios)
                                <h4>{{ $dia }}</h4>
                                @foreach ($horarios as $horario => $alimentos)
                                    @if (count($alimentos) > 0)
                                        <p><strong>{{ $horarioNomes[$horario] ?? ucfirst($horario) }}:</strong>
                                            {{ implode(', ', $alimentos) }}</p>
                                    @endif
                                @endforeach
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = 'none';
        }
        window.onclick = function(event) {
            document.querySelectorAll('.modal').forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 12px;
            width: 60%;
            max-width: 700px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content h3 {
            margin-bottom: 10px;
        }

        .modal-content h4 {
            margin-top: 15px;
            color: #2a7b2e;
        }

        .modal-content ul {
            margin-left: 20px;
        }

        .close {
            float: right;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }

        .close:hover {
            color: red;
        }
    </style>
</x-layout.main_layout>
