<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio Semanal - Refeitório IFBA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/cardapio.css') }}">
</head>

<body>

    <header>
        <div class="header-content">
            <div class="logo-container">
                <div class="logo"><i class="fas fa-utensils"></i></div>
            </div>
            <h1>Cardápio Semanal</h1>
            <p>Refeitório do IFBA Campus Eunápolis</p>
        </div>
        <div class="header-decoration"></div>
    </header>

    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="nav-link ativo" data-periodo="matutino"><i class="fas fa-coffee"></i> Matutino</a>
            <a href="#" class="nav-link" data-periodo="almoco"><i class="fas fa-utensils"></i> Almoço</a>
            <a href="#" class="nav-link" data-periodo="vespertino"><i class="fas fa-cloud-sun"></i> Vespertino</a>
            <a href="#" class="nav-link" data-periodo="noturno"><i class="fas fa-moon"></i> Noturno</a>
        </div>
    </nav>

    <div class="dias-navegacao">
        <div class="dia-link ativo" data-dia="segunda">Segunda-feira</div>
        <div class="dia-link" data-dia="terca">Terça-feira</div>
        <div class="dia-link" data-dia="quarta">Quarta-feira</div>
        <div class="dia-link" data-dia="quinta">Quinta-feira</div>
        <div class="dia-link" data-dia="sexta">Sexta-feira</div>
        <div class="dia-link" data-dia="sabado">Sábado</div>
    </div>

    <div class="container">
        <div class="info-selecao">
            Visualizando o cardápio do <span id="periodo-atual">Intervalo Matutino</span> para <span
                id="dia-atual">Segunda-feira</span>
        </div>

        <div id="cards-container" class="cards-container">
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3><i class="fas fa-utensils"></i> Refeitório IFBA</h3>
                <p>Oferecendo alimentação saudável e balanceada para a comunidade acadêmica.</p>
                <p>Comprometidos com a qualidade e satisfação.</p>
            </div>

            <div class="footer-section">
                <h3><i class="fas fa-clock"></i> Horários</h3>
                <p><strong>Intervalo Matutino:</strong> 9h30min às 9h50min</p>
                <p><strong>Intervalo Almoço:</strong> 12h30min às 13h30min</p>
                <p><strong>Intervalo Vespertino:</strong> 15h às 15h30min</p>
                <p><strong>Intervalo Noturno:</strong> 18h30min às 19h</p>
                <p><strong>Intervalo Sabados Letivos:</strong> 9h45min às 10h05min</p>
            </div>

            <div class="footer-section">
                <h3><i class="fas fa-map-marker-alt"></i> Localização</h3>
                <p>IFBA Campus Eunápolis</p>
                <p>Av. David Jonas Fadini, s/n</p>
                <p>Eunápolis - BA, 45823-431</p>
            </div>

            <div class="footer-section">
                <h3><i class="fas fa-envelope"></i> Contato</h3>
                <p>Email: nutricao.eun@ifba.edu.br</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} IFBA Campus Eunápolis - Todos os direitos reservados</p>
        </div>
        </div>
    </footer>

    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const refeicoes = @json($refeicoes);
            const navLinks = document.querySelectorAll('.nav-link');
            const diaLinks = document.querySelectorAll('.dia-link');
            const periodoAtualElement = document.getElementById('periodo-atual');
            const diaAtualElement = document.getElementById('dia-atual');
            const cardsContainer = document.getElementById('cards-container');
            const backToTopButton = document.querySelector('.back-to-top');

            let periodoAtual = 'matutino';
            let diaAtual = 'segunda';

            const periodoNomes = {
                matutino: 'Intervalo Matutino',
                almoco: 'Intervalo do Almoço',
                vespertino: 'Intervalo Vespertino',
                noturno: 'Intervalo Noturno'
            };

            function criarCards() {
                cardsContainer.innerHTML = '';
                if (!refeicoes[periodoAtual] || !refeicoes[periodoAtual][diaAtual]) return;

                refeicoes[periodoAtual][diaAtual].forEach(alimento => {
                    const card = document.createElement('div');
                    card.classList.add('card');

                    const imgDiv = document.createElement('div');
                    imgDiv.classList.add('card-imagem');
                    imgDiv.textContent = alimento.nomeAlimento;

                    const conteudo = document.createElement('div');
                    conteudo.classList.add('card-conteudo');

                    const titulo = document.createElement('h3');
                    titulo.classList.add('card-titulo');
                    titulo.textContent = alimento.nomeAlimento;

                    const ingredientes = document.createElement('div');
                    ingredientes.classList.add('card-info');
                    ingredientes.innerHTML =
                        `<h4><i class="fas fa-list"></i> Ingredientes</h4><p>${alimento.ingredientes}</p>`;

                    const infoNutri = document.createElement('div');
                    infoNutri.classList.add('card-info');
                    infoNutri.innerHTML =
                        `<h4><i class="fas fa-fire"></i> Informações Nutricionais</h4>
            <p>Calorias: ${alimento.calorias} kcal | Carboidratos: ${alimento.carboidratos} g | Proteínas: ${alimento.proteinas} g | Gorduras: ${alimento.gorduras_totais} g</p>`;

                    const alergiaDiv = document.createElement('div');
                    alergiaDiv.classList.add('alergia');
                    alergiaDiv.innerHTML = alimento.contem_gluten ?
                        `<i class="fas fa-exclamation-triangle"></i> Contém glúten` :
                        `<i class="fas fa-check-circle"></i> Sem glúten`;

                    const alergicosDiv = document.createElement('div');
                    alergicosDiv.classList.add('alergia');
                    if (alimento.alergicos.length) {
                        alergicosDiv.innerHTML =
                            `<i class="fas fa-exclamation-triangle"></i> Contém: ${alimento.alergicos.join(', ')}`;
                    }

                    conteudo.appendChild(titulo);
                    conteudo.appendChild(ingredientes);
                    conteudo.appendChild(infoNutri);
                    conteudo.appendChild(alergiaDiv);
                    if (alimento.alergicos.length) conteudo.appendChild(alergicosDiv);

                    card.appendChild(imgDiv);
                    card.appendChild(conteudo);
                    cardsContainer.appendChild(card);
                });
            }

            function atualizarSelecao() {
                periodoAtualElement.textContent = periodoNomes[periodoAtual];
                const diaTexto = document.querySelector(`.dia-link[data-dia="${diaAtual}"]`).textContent;
                diaAtualElement.textContent = diaTexto;
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    navLinks.forEach(l => l.classList.remove('ativo'));
                    this.classList.add('ativo');
                    periodoAtual = this.getAttribute('data-periodo');
                    atualizarSelecao();
                    criarCards();
                });
            });

            diaLinks.forEach(link => {
                link.addEventListener('click', function() {
                    diaLinks.forEach(l => l.classList.remove('ativo'));
                    this.classList.add('ativo');
                    diaAtual = this.getAttribute('data-dia');
                    atualizarSelecao();
                    criarCards();
                });
            });

            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) backToTopButton.classList.add('visible');
                else backToTopButton.classList.remove('visible');
            });

            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            criarCards();
            atualizarSelecao();
        });
    </script>

</body>

</html>
