document.addEventListener('DOMContentLoaded', () => {
    // 1) Toggle da sidebar
    const btnMenu = document.querySelector('#btn');
    const sidebar = document.querySelector('.sidebar');
    if (btnMenu && sidebar) {
      btnMenu.addEventListener('click', () => {
        sidebar.classList.toggle('active');
      });
    }

    // 2) Lógica dos cards (já existente)
    const cards = document.querySelectorAll('.card');
    const contentItems = document.querySelectorAll('.content-item');
    const cardsContainer = document.querySelector('.cards');
    if (cardsContainer) {
      cardsContainer.addEventListener('click', event => {
        const clickedCard = event.target.closest('.card');
        if (!clickedCard) return;
        cards.forEach(c => c.classList.remove('active'));
        contentItems.forEach(i => i.classList.remove('active'));
        clickedCard.classList.add('active');
        const contentId = clickedCard.dataset.content;
        const related = document.querySelector(`#content-${contentId}`);
        if (related) related.classList.add('active');
      });
    }

    // 3) Toggle dos lotes
    document.querySelectorAll('.expandBtn').forEach(expandBtn => {
      expandBtn.addEventListener('click', () => {
        const productId = expandBtn.dataset.productId;
        // monta o seletor corretamente, sem escapes desnecessários
        const selector = `.lot-row[data-product-id="${productId}"]`;
        document.querySelectorAll(selector).forEach(row => {
          row.style.display = row.style.display === 'none' ? '' : 'none';
        });
      });
    });
  });
