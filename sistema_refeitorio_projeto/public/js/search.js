document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.btn-pesquisa');

    buttons.forEach(btn => {
        const inputId = btn.dataset.input;
        const input = document.getElementById(inputId);

        if (!input) return;

        // --- 🔑 ENTER DISPARA A PESQUISA ---
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault(); // impede submit do form
                btn.click(); // dispara o clique no botão
            }
        });

        // --- CLICK NO BOTÃO ---
        btn.addEventListener('click', () => {
            const valorDigitado = input.value.trim();
            const route = btn.dataset.route;

            if (!valorDigitado) {
                window.location.href = route;
                return;
            }

            // Monta a URL com o parâmetro
            const url = `${route}?${inputId}=${encodeURIComponent(valorDigitado)}`;
            window.location.href = url;
        });
    });
});
