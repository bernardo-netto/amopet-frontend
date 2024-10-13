


// Mostrar o modal de cães
document.getElementById('showDogs').addEventListener('click', function() {
    document.getElementById('dogsModal').style.display = 'flex';
});

// Mostrar o modal de gatos
document.getElementById('showCats').addEventListener('click', function() {
    document.getElementById('catsModal').style.display = 'flex';
});

// Abrir modal de adoção
function openAdoptModal(animalName) {
    document.getElementById('animalName').innerText = animalName;
    document.getElementById('adoptModal').style.display = 'flex';
}

// Fechar modal de adoção
function closeAdoptModal() {
    document.getElementById('adoptModal').style.display = 'none';
}

// Fechar modal de cães
function closeDogsModal() {
    document.getElementById('dogsModal').style.display = 'none';
}

// Fechar modal de gatos
function closeCatsModal() {
    document.getElementById('catsModal').style.display = 'none';
}

// Confirmar adoção
function confirmAdoption() {
    alert("Adoção confirmada! A ONG irá entrar em contato com você o mais rápido possível.");
    closeAdoptModal();
    closeDogsModal();
    closeCatsModal();
}


        $(document).ready(function() {
        $('#mobile_btn').on('click', function () {
        $('#mobile_menu').toggleClass('active');
        $('#mobile_btn').find('i').toggleClass('fa-x');
        });

        /* Animação quando rolar a tela para baixo */
        const sections = $('section'); /* Pega os items no html */
        const navItems = $('.nav-item');

        $(window).on('scroll', function () { /* Animação assim que rolar a página */
            const header = $('header'); /* Adicionar a sombra */
            const scrollPosition = $(window).scroollTop() - header.outerHeight();

            let activeSectionIndex = 0;
            
            if (scrollPosition <= 0) {
                header.css('box-shadow', 'none');
            }  else {
                header.css('box-shadow', '5px 1px 5px rgba(0, 0, 0, 0.1');
            }

            sections.each(function(i) {
                const section = $(this);
                const sectionTop = section.offset().top - 96;
                const sectionBottom = sectionTop+ section.outerHeight();

                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    activeSectionIndex = i;
                    return false;
                }
            })
            
            navItems.removeClass('active');
            $(navItems[activeSectionIndex]).addClass('active');
        });

        ScrollReveal().reveal('#cta', {
            origin: 'left',
            duration: 2000,
            distance: '20%'
        })

    });
  


        document.addEventListener('DOMContentLoaded', () => {
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) { // Ajuste o valor conforme necessário
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
    });



    // Obtém referências aos elementos do DOM
  const btnDesktop = document.getElementById('botaoAbrirModalDesktop');
  const btnMobile = document.getElementById('botaoAbrirModalMobile')
  const modalLogin = document.getElementById('modalLogin');
  const botaoFechar = document.querySelector('.botao-fechar');
  const botaoCriarConta = document.getElementById('botaoCriarConta');
  const linkEsqueciSenha = document.getElementById('linkEsqueciSenha');
  const linksVoltar = document.querySelectorAll('.link-voltar');
  const formLogin = document.getElementById('formLogin');
  const formCriarConta = document.getElementById('formCriarConta');
  const formRedefinirSenha = document.getElementById('formRedefinirSenha');
  const botaoCriar = document.getElementById('botaoCriar');
  const botaoRedefinir = document.getElementById('botaoRedefinir');

  // Abre o modal ao clicar no botão de Entrar (Desktop)
  btnDesktop.addEventListener('click', () => {
    modalLogin.style.display = 'block';
  });

  // Abre o modal ao clicar no botão de entrar (Mobile)
  btnMobile.addEventListener('click', () => {
    modalLogin.style.display = 'block';
  });

  // Fecha o modal ao clicar no botão de fechar
  botaoFechar.addEventListener('click', () => {
    modalLogin.style.display = 'none';
  });

  // Fecha o modal ao clicar fora dele
  window.addEventListener('click', (event) => {
    if (event.target == modalLogin) {
      modalLogin.style.display = 'none';
    }
  });

  // Alterna para o formulário de criação de conta
  botaoCriarConta.addEventListener('click', () => {
    formLogin.style.display = 'none';
    formCriarConta.style.display = 'block';
  });

  // Alterna para o formulário de redefinição de senha
  linkEsqueciSenha.addEventListener('click', () => {
    formLogin.style.display = 'none';
    formRedefinirSenha.style.display = 'block';
  });

  // Retorna ao formulário de login a partir dos links de voltar
  linksVoltar.forEach(link => {
    link.addEventListener('click', () => {
      formRedefinirSenha.style.display = 'none';
      formCriarConta.style.display = 'none';
      formLogin.style.display = 'block';
    });
  });

  // Exibe mensagem e retorna ao login após enviar email de redefinição de senha
  botaoRedefinir.addEventListener('click', (event) => {
    event.preventDefault(); // Impede o envio real do formulário
    alert('Email de redefinição enviado com sucesso!');
    formRedefinirSenha.style.display = 'none'; // Oculta o formulário de redefinição
    formLogin.style.display = 'block'; // Exibe o formulário de login
  });

  // Exibe mensagem e retorna ao login após criar nova conta
  /*botaoCriar.addEventListener('click', (event) => {
    event.preventDefault(); // Impede o envio real do formulário
    alert('Conta criada com sucesso!');
    formCriarConta.style.display = 'none'; // Oculta o formulário de criação
    formLogin.style.display = 'block'; // Exibe o formulário de login
  });*/

  botaoCriar.addEventListener('click', (event) => {
    event.preventDefault(); // Impede o envio real do formulário

    // Cria um objeto FormData para enviar os dados do formulário
    const formData = new FormData(formCriarConta);

    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Exibe a resposta do servidor (mensagem de sucesso ou erro)
        formCriarConta.style.display = 'none'; // Oculta o formulário de criação
        formLogin.style.display = 'block'; // Exibe o formulário de login
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao criar a conta. Tente novamente.');
    });
});



  document.addEventListener("DOMContentLoaded", () => {
            const cards = document.querySelectorAll('.criadores-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('ativo');
                }, index * 300); // Delay de 300ms entre cada card
            });
        });