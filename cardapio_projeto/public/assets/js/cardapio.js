document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.nav-link');
    const diaLinks = document.querySelectorAll('.dia-link');
    const periodoAtualElement = document.getElementById('periodo-atual');
    const diaAtualElement = document.getElementById('dia-atual');
    const backToTopButton = document.querySelector('.back-to-top');
    const cardsContainer = document.querySelector('.cards-container');

    function criarCard(alimento) {
        const card = document.createElement('div');
        card.classList.add('card');
        card.innerHTML = `
            <div class="card-imagem">${alimento.nomeAlimento}</div>
            <div class="card-conteudo">
                <h3 class="card-titulo">${alimento.nomeAlimento}</h3>
                <div class="card-info">
                    <h4><i class="fas fa-list"></i> Ingredientes</h4>
                    <p>${alimento.ingredientes}</p>
                </div>
                <div class="card-info">
                    <h4><i class="fas fa-fire"></i> Informações Nutricionais</h4>
                    <p>Calorias: ${alimento.calorias}kcal | Carboidratos: ${alimento.carboidratos}g | Proteínas: ${alimento.proteinas}g | Gorduras: ${alimento.gorduras_totais}g</p>
                </div>
                <div class="alergia">
                    ${alimento.contem_gluten
                        ? '<i class="fas fa-exclamation-triangle"></i> Contém glúten'
                        : '<i class="fas fa-check-circle"></i> Sem glúten'}
                </div>
                <div class="alergia">
                    ${alimento.alergicos && alimento.alergicos.length > 0
                        ? '<i class="fas fa-exclamation-triangle"></i> Contém: ' + alimento.alergicos.join(', ')
                        : '<i class="fas fa-check-circle"></i> Sem alergênicos comuns'}
                </div>
            </div>
        `;
        return card;
    }

    function atualizarCardapio(periodo, dia) {
        cardsContainer.innerHTML = '';
        const alimentos = cardapioData[periodo]?.[dia] || [];
        alimentos.forEach(alimento => {
            cardsContainer.appendChild(criarCard(alimento));
        });

        periodoAtualElement.textContent = periodo.charAt(0).toUpperCase() + periodo.slice(1);
        diaAtualElement.textContent = document.querySelector(`.dia-link[data-dia="${dia}"]`).textContent;
    }

    navLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove('ativo'));
            link.classList.add('ativo');

            const periodo = link.getAttribute('data-periodo');
            const dia = document.querySelector('.dia-link.ativo').getAttribute('data-dia');
            atualizarCardapio(periodo, dia);
        });
    });

    diaLinks.forEach(link => {
        link.addEventListener('click', () => {
            diaLinks.forEach(l => l.classList.remove('ativo'));
            link.classList.add('ativo');

            const periodo = document.querySelector('.nav-link.ativo').getAttribute('data-periodo');
            const dia = link.getAttribute('data-dia');
            atualizarCardapio(periodo, dia);
        });
    });

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) backToTopButton.classList.add('visible');
        else backToTopButton.classList.remove('visible');
    });

    backToTopButton.addEventListener('click', e => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    atualizarCardapio('matutino', 'segunda');
});
