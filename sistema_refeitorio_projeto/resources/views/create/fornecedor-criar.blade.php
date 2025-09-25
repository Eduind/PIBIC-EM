<x-layout.layout-secundario>
    <x-slot:title>Novo fornecedor</x-slot:title>
    <div class="form-container">
        @if(session('criacao_valida'))
            <div class="alert alert-success text-center px-4 py-3">
                {{ session('criacao_valida') }}
            </div>
        @endif
        <h2>Registrar Fornecedor</h2>
        <div class="botao">
            <form action="{{route('novoFornecedorSubmit')}}" method="post">
                @csrf
                <input type="text" name="text_name" value="{{old('text_name')}}" placeholder="Nome do fornecedor" required>
                @error('text_name')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_cnpj" value="{{old('text_cnpj')}}" placeholder="CNPJ do fornecedor" required>
                @error('text_cnpj')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_email" value="{{old('text_email')}}" placeholder="Email" required>
                @error('text_email')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_endereco" value="{{old('text_endereco')}}" placeholder="Endereço" required>
                @error('text_endereco')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_phone" value="{{old('text_phone')}}" placeholder="Telefone" required>
                @error('text_phone')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <input type="text" name="text_num_empenho" value="{{old('text_num_empenho')}}" placeholder="Número de empenho" required>
                @error('text_num_empenho')
                        <small class="text-danger d-block ms-2">{{ $message }}</small>
                @enderror
                <div class="button-group">
                    <a href="{{route('home')}}" class="btn-cancel"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
                    <button type="submit" class="btn-submit"><i class="fa-regular fa-circle-check me-2"></i>Criar</button>
                </div>
            </form>
        </div>
    </div>
</x-layout.layout-secundario>
