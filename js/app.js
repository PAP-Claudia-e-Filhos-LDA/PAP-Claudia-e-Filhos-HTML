const sections = document.querySelectorAll('.section');
const sectBtns = document.querySelectorAll('.control');

function PageTransitions() {
  sectBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');

      // Remove a classe 'active-btn' de todos os botões
      document.querySelectorAll('.active-btn').forEach(activeBtn => {
        activeBtn.classList.remove('active-btn');
      });

      // Adiciona a classe 'active-btn' ao botão clicado
      this.classList.add('active-btn');

      // Remove a classe 'active' de todas as seções
      sections.forEach(section => {
        section.classList.remove('active');
      });

      // Adiciona a classe 'active' à seção correspondente ao botão clicado
      const selectedSection = document.getElementById(id);
      selectedSection.classList.add('active');
    });
  });
}

PageTransitions();
