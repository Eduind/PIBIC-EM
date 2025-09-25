<x-layout.main_layout>
    <x-slot:title>Visualizar alimentos</x-slot:title>
    <x-slot:css>{{ asset('assets/css/home.css') }}</x-slot:css>
    <div class="search-container">
        <form>
            <div class="search-bar">
                <input type="text" name="text_pesquisa" placeholder="Pesquisar alimento..."  value="{{ request('text_pesquisa') }}">
                <button class="btn-search"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table class="food-table">
            <thead>
                <tr>
                    <th>Nome do alimento</th>
                    <th>Ingredientes</th>
                    <th>Valores nutricionais</th>
                    <th>Alergênicos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['alimentos'] as $alimentos)
                    <tr>
                        <td>{{ $alimentos['nomeAlimento'] }}</td>
                        <td>{{ $alimentos['ingredientes'] }}</td>
                        <td>{{ $alimentos['calorias'] }}Kcal|{{ $alimentos['carboidratos'] }}g|{{ $alimentos['proteinas'] }}g|{{ $alimentos['gorduras_totais'] }}g
                        </td>
                        <td>
                            @if ($alimentos['contem_gluten'])
                                <span class="badge badge-danger">Contém Glúten</span>
                            @else
                                <span class="badge badge-success">Sem Glúten</span>
                            @endif

                            @if (!empty($alimentos['alergicos']))
                                @foreach ($alimentos['alergicos'] as $alergicos)
                                    <span class="badge badge-warning">{{ $alergicos }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td class="actions">
                            <a class="btn-edit" href="{{route('EditarAlimento', ['id' => Crypt::encrypt($alimentos['idAlimento'])])}}"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn-delete"  href="{{route('DeletarAlimento', ['id' => Crypt::encrypt($alimentos['idAlimento'])])}}"><i class="bi bi-trash-fill"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="btn-container">
        <a href="{{ route('CriarAlimentos') }}" class="btn-create">+ Criar Novo Alimento</a>
    </div>

    <script>
        const mobileMenu = document.querySelector('.mobile-menu');
        if (mobileMenu) {
            mobileMenu.addEventListener('click', function() {
                document.querySelector('.nav-list').classList.toggle('active');
            });
        }

        document.querySelectorAll('.nav-list a').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.querySelector('.nav-list').classList.remove('active');
                }
            });
        });
    </script>
</x-layout.main_layout>
