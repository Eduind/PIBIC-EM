<x-layout.main-layout>
    <x-slot:title>Relatórios</x-slot:title>
    <div class="container">
        <div class="cards">
            <div class="card active" data-content="1">Relatórios de estoque</div>
            <div class="card" data-content="2">Relatórios financeiros</div>
        </div>
        <div class="content">
            <div class="content-item active" id="content-1">
                <div class="table-container">
                    <form action="{{route('intermediario')}}" method="POST">
                        @csrf
                        <div class="periodo">
                            <label for="periodo">Tempo de análise:</label>
                            <input name="periodo_estoque" type="date">
                            @error('periodo_estoque')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                        <table class="table-style">
                            <thead>
                                <tr>
                                    <th>Relatório</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Relatório de Inventario</td>
                                    <td><button class="visualizar" name="tipo_relatorio" value="inventario">visualizar</button></td>
                                </tr>
                                <tr>
                                    <td>Relatório de Movimentação de estoque</td>
                                    <td><button class="visualizar" name="tipo_relatorio" value="movimentacao">visualizar</button></td>
                                </tr>
                                <tr>
                                    <td>Relatório de Produtos Proximos do Vencidos</td>
                                    <td><button class="visualizar" name="tipo_relatorio" value="vencido">visualizar</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Relatório de Nivel de Reposição</td>
                                    <td><button class="visualizar" name="tipo_relatorio" value="nivel">visualizar</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <div class="content-item" id="content-2">
                <div class="table-container">
                    <table class="table-style">
                        <form action="{{route('intermediario')}}" method="POST">
                            @csrf
                            <div class="periodo">
                                <label for="periodo">Tempo de análise:</label>
                                <input name="periodo_financeiro" type="date">
                                @error('periodo_financeiro')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>
                            <thead>
                                <tr>
                                    <th>Relatório</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Relatório de Compras</td>
                                    <td><button class="visualizar" name="tipo_relatorio" value="compra">visualizar</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Relatório Financeiro</td>
                                    <td><button class="visualizar" name="tipo_relatorio" value="financeiro">visualizar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout.main-layout>
