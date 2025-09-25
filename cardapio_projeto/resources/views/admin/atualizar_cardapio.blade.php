<x-layout.main_layout>
    <x-slot:title>Atualizar Cardápio</x-slot:title>
    <x-slot:css>{{ asset('assets/css/criar_cardapio.css') }}</x-slot:css>

    <h2>Atualizar Cardápio Semanal</h2>

    @if (session('criacao_valida'))
        <div class="alert alert-success text-center px-4 py-3">
            {{ session('criacao_valida') }}
        </div>
    @endif

    <form class="food-form" method="POST" action="{{ route('EditarCardapiosSubmit', $dados['cardapio']['idCardapio']) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nomeCardapio">Nome do Cardápio</label>
            <input type="text" id="nomeCardapio" name="text_name"
                value="{{ old('text_name', $dados['cardapio']['nomeCardapio']) }}"
                placeholder="Digite o nome do cardápio">
            @error('text_name')
                <small class="text-danger d-block ms-2">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="data_inicio">Data Início</label>
            <input type="date" name="text_data_ini" id="data_inicio"
                value="{{ old('text_data_ini', $dados['cardapio']['dataInicio']) }}">
            @error('text_data_ini')
                <small class="text-danger d-block ms-2">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="data_fim">Data Fim</label>
            <input type="date" name="text_data_fim" id="data_fim"
                value="{{ old('text_data_fim', $dados['cardapio']['dataFim']) }}">
            @error('text_data_fim')
                <small class="text-danger d-block ms-2">{{ $message }}</small>
            @enderror
        </div>

        <div class="table-container">
            <table class="food-table">
                <thead>
                    <tr>
                        <th>Dia</th>
                        <th>Matutino</th>
                        <th>Almoço</th>
                        <th>Vespertino</th>
                        <th>Noturno</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diasSemana as $dia)
                        @php $diaKey = strtolower($dia); @endphp
                        <tr>
                            <td>{{ ucfirst($dia) }}</td>

                            @if ($dia == 'Sabado')
                                @foreach (['matutino'] as $horario)
                                    <td>
                                        <select name="refeicoes[{{ $dia }}][{{ $horario }}]">
                                            <option value="">Selecione</option>
                                            @foreach ($dados['alimentos'] as $alimentos)
                                                <option value="{{ $alimentos['idAlimento'] }}"
                                                    {{ old("refeicoes.$diaKey.$horario", $dados['cardapio']['refeicoes'][$diaKey][$horario] ?? '') == $alimentos['idAlimento'] ? 'selected' : '' }}>
                                                    {{ $alimentos['nomeAlimento'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endforeach
                            @else
                                @foreach (['matutino', 'almoco', 'vespertino', 'noturno'] as $horario)
                                    <td>
                                        <select name="refeicoes[{{ $dia }}][{{ $horario }}]">
                                            <option value="">Selecione</option>
                                            @foreach ($dados['alimentos'] as $alimentos)
                                                <option value="{{ $alimentos['idAlimento'] }}"
                                                    {{ old("refeicoes.$diaKey.$horario", $dados['cardapio']['refeicoes'][$diaKey][$horario] ?? '') == $alimentos['idAlimento'] ? 'selected' : '' }}>
                                                    {{ $alimentos['nomeAlimento'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="btn-container">
            <a href="{{ route('ExibirCardapios') }}" class="btn-cancel">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit" class="btn-create">
                <i class="bi bi-check-circle"></i> Atualizar Cardápio
            </button>
        </div>
    </form>
    <script>
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            document.querySelector('.nav-list').classList.toggle('active');
        });

        document.querySelectorAll('.nav-list a').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.querySelector('.nav-list').classList.remove('active');
                }
            });
        });
    </script>
</x-layout.main_layout>
