<?php
include 'config.php'; // Inclui o arquivo de configuração para conexão com o banco de dados

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifica se o botão de envio do formulário de redefinição de senha foi pressionado
    if (isset($_POST['campoEmailRedefinir'])) {
        $email = $conn->real_escape_string($_POST['campoEmailRedefinir']);

        // Verifica se o e-mail existe no banco de dados
        $sql = "SELECT * FROM usuarios WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Gera um link para redefinir a senha (simples, sem token)
            $link = "http://localhost/bernardo/amopet/redirecionar_redefinicao.php?email=" . urlencode($email);

            // Ou esse para o ambiente de produção no Netlify:
            // $link = "https://670ad5af3bff200008609850--amopet-front-end.netlify.app/#/redirecionar_redefinicao.php?email=" . urlencode($email);

            $mensagem = "Clique no seguinte link para redefinir sua senha: " . $link;

            // Envio do e-mail
            mail($email, "Redefinir Senha", $mensagem);

            echo "Um e-mail foi enviado para você com instruções para redefinir sua senha.";
        } else {
            echo "E-mail não encontrado.";
        }
    }

    // Verifica se o botão de envio do formulário de criação de conta foi pressionado
    if (isset($_POST['submit'])) {
        // Escapa os dados de entrada para prevenir SQL Injection
        $email = $conn->real_escape_string($_POST['campoNovoEmail']);
        $nome_usuario = $conn->real_escape_string($_POST['campoNovoUsuario']);
        // Criptografa a senha antes de armazená-la
        $senha = password_hash($_POST['campoNovaSenha'], PASSWORD_DEFAULT);
        $data_nascimento = $_POST['campoDataNascimento']; // Captura a data de nascimento
        $genero = $conn->real_escape_string($_POST['campoGenero']); // Escapa o gênero

        // Cria a consulta SQL para inserir dados na tabela usuarios
        $sql_usuarios = "INSERT INTO usuarios (email, nome_usuario, senha, data_nascimento, genero) VALUES ('$email', '$nome_usuario', '$senha', '$data_nascimento', '$genero')";

        // Executa a consulta para inserir o usuário
        if ($conn->query($sql_usuarios) === TRUE) {
            // Se a inserção for bem-sucedida, insere também na tabela logins
            $sql_login = "INSERT INTO logins (email, senha) VALUES ('$email', '$senha')";
            // Executa a consulta para inserir o login
            if ($conn->query($sql_login) === TRUE) {
                // echo "Conta criada com sucesso!"; // Removido para não exibir mensagem
            } else {
                // Exibe mensagem de erro caso a inserção na tabela logins falhe
                echo "Erro ao inserir no logins: " . $conn->error;
            }
        } else {
            // Exibe mensagem de erro caso a inserção na tabela usuarios falhe
            echo "Erro ao inserir no usuários: " . $conn->error;
        }
    }
}

$conn->close(); // Fecha a conexão com o banco de dados
?>






<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="style.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/scrollreveal"></script>

    <title>Amo Pet</title>

    
</head>
<body>
    <!-- BARRA DE MENU E NAVEGAÇÃO -->
<header>
    <nav id="navbar">
        <!-- logo -->
         <a href="#home">
            <i class="fa-solid fa-paw" id="nav_logo">AMOPET</i>
         </a>
        

        <!-- lista com 3 opções no menu -->
        <ul id="nav_list">
            <li class="nav-item active">
                <a href="#home">Home</a>
            </li>
            <li class="nav-item">
                <a href="#sobre-nos">Sobre Nós</a>
            </li>
            <div class="nav-item dropdown">
                <a href="#adocao">Adoção</a>
            </div>
            <li class="nav-item">
                <a href="#contato">Contato</a>
            </li>
        </ul>

        <!-- Botão de login para desktop -->
        <button class="btn-default" id="botaoAbrirModalDesktop">
            Entrar
        </button>
        
        <!-- 3 risco opção de MENU -->
        <button id="mobile_btn">
            <i class="fa-solid fa-bars"></i>
        </button>
    </nav>

    <!-- Esse menu irá aparecer só na opção MOBILE (Celular)-->
    <div id="mobile_menu">
        <ul id="mobile_nav_list">
            <li class="nav-item active">
                <a href="#home">Home</a>
            </li>
            <li class="nav-item">
                <a href="#sobre-nos">Sobre Nós</a>
            </li>
            <div class="nav-item dropdown">
                <a href="#adocao">Adoção</a>
            </div>
            <li class="nav-item">
                <a href="#contato">Contato</a>
            </li>
        </ul>

        <!-- Botão de login para mobile -->
        <button class="btn-default" id="botaoAbrirModalMobile">
            Entrar
        </button>
    </div>
</header>

    <!-- Modal -->
    <div id="modalLogin" class="modal-login">
        <div class="conteudo-modal">
            <span class="botao-fechar">&times;</span>
            <h2 class="fa-solid fa-paw" id="nav_logo"> AMOPET</h2>

            <!-- Formulário de login -->
            <form id="formLogin" action="index.php" method="POST">
                <input type="text" class="campo-texto" id="campoEmail" name="campoEmail" placeholder="Email ou telefone" required>
                <input type="password" class="campo-senha" id="campoSenha" name="campoSenha" placeholder="Senha" required>
                <button type="submit" class="botao-entrar">Entrar</button>
                <a href="#" class="link-esqueci-senha" id="linkEsqueciSenha">Esqueceu a senha?</a>
                <div class="divisor">ou</div>
                <button type="button" class="botao-criar-conta" id="botaoCriarConta">Criar nova conta</button>
            </form>

            <!-- Formulário de criação de conta -->
            <form id="formCriarConta" style="display: none;" action="index.php" method="POST">
                <br>
                <h2>Criar nova conta</h2>
                <br>
                <input type="text" class="campo-texto" id="campoNovoEmail" name="campoNovoEmail" placeholder="Email" required>
                <input type="text" class="campo-texto" id="campoNovoUsuario" name="campoNovoUsuario" placeholder="Nome de usuário" required>
                <input type="password" class="campo-senha" id="campoNovaSenha" name="campoNovaSenha" placeholder="Senha" required>
                <input type="date" class="campo-data" id="campoDataNascimento" name="campoDataNascimento" required>
                <select class="campo-select" id="campoGenero" name="campoGenero" required>
                    <option value="" disabled selected>Gênero</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                </select>
                <button type="submit" name="submit" id="submit" class="botao-entrar" id="botaoCriar">Criar conta</button>
                <a href="#" class="link-voltar" id="linkVoltar">Voltar</a>
            </form>

            <!-- Formulário para redefinir a senha -->
            <form id="formRedefinirSenha" style="display: none;" action="index.php" method="POST">
                <br>
                <h2>Redefinir Senha</h2>
                <br>
                <input type="text" class="campo-texto" id="campoEmailRedefinir" name="campoEmailRedefinir" placeholder="Digite seu email" required>
                <button type="submit" class="botao-entrar" id="botaoRedefinir">Enviar email de redefinição de senha</button>
                <a href="#" class="link-voltar" id="linkVoltarRedefinir">Voltar</a>
            </form>
        </div>
    </div>



    <!-- -TODO CONTEUDO DA PAGINA -->
    <main id="content">
        <section id="home">
            <div class="shape"></div>
            <div id="cta">
                <h1 class="title">
                    <span>Leve um amor para casa</span>
                </h1>

                <p class="description"><b>Encontre seu novo amigo aqui!</b></p>
                <p>Bem-vindo ao <b>AmoPet!</b> Nosso site foi desenvolvido com carinho por três alunos da Escola Senac, com o objetivo de apoiar e promover a adoção de animais através das ONGs de São Paulo.</p>
                <p class="description2">Se você está procurando um novo amigo de quatro patas, chegou ao lugar certo! Aqui, você encontrará uma seleção de cães e gatos adoráveis disponíveis para adoção, todos prontos para encontrar um lar amoroso.</p>

                <p class="description2"><b>Adotar um pet é muito mais do que ganhar um companheiro; é fazer a diferença na vida de um animal que precisa de amor e cuidado.</b> Navegue pelas opções, conheça os animais e dê a eles a chance de um novo começo.</p>

                <p><b>Explore, escolha e transforme vidas. Juntos, podemos fazer a diferença!</b></p>



                <div id="cta_buttons">
                    <!-- LINK PARA SEÇÃO ADOTAR -->
                    <a href="#adocao" class="btn-default">
                        Adote Agora
                    </a>

                    <!-- TELEFONE -->
                    <a href="tel:+55555555555" id="phone_button">
                        <button class="btn-default">
                            <i class="fa-solid fa-phone"></i>
                        </button>
                        (11) 3355-9911
                    </a>
                </div>

                <!-- REDES SOCIAIS -->
                <div class="social-media-buttons">
                    <!-- WHATSAPP -->
                    <a href="https://web.whatsapp.com/" class="footer-link" id="whatsapp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <!-- INSTAGRAM -->
                    <a href="https://www.instagram.com/" class="footer-link" id="instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <!-- FACEBOOK -->
                    <a href="https://www.facebook.com/" class="footer-link" id="facebook">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                </div>
            </div>

            <div class="fundo-imagem">
                <img src="imagem/imagem-dogs01.png" alt="Descrição da imagem" class="imagem">
            </div>

        </section>

        <!-- Seção 'Sobre Nós' -->
        <section id="sobre-nos" class="secao">
            <div class="container">
                <br>
                <h2 class="titulo-secao" style="color: #e9a209;">Sobre Nós</h2>
                <br>
                <div class="container-sobre">
                    <div class="container-imagens">
                        <img src="imagem.png" alt="Equipe AMOPET">
                    </div>

                    
                    
                    
                    <div class="bloco-texto">
                        <br>
                        <p>Bem-vindo ao <strong>AMOPET!</strong><br>
                            Nosso site foi desenvolvido com carinho por três alunos da Escola Senac, com o objetivo de apoiar e promover a adoção de animais através das ONGs de São Paulo. Se você está procurando um novo amigo de quatro patas, chegou ao lugar certo! Aqui, você encontrará uma seleção de cães e gatos adoráveis disponíveis para adoção, todos prontos para encontrar um lar amoroso.</p>
                        <p>Adotar um pet é muito mais do que ganhar um companheiro; é fazer a diferença na vida de um animal que precisa de amor e cuidado. Nós da <strong>AMOPET</strong> estamos aqui para ajudar você nesse processo!</p>
                        <br>
                        <a href="#adocao" class="botao-mais-info">Ver Animais para Adoção</a>
                    </div>
                </div>

                <!-- 'Nossos Objetivos' -->
                 <br>
                <div class="objectives-section">
                    <h3>Nossos Objetivos São:</h3>
        
                    <div class="objectives-cards">
                        <div class="objective-card">
                            <h4>Aumentar a Visibilidade</h4>
                            <p>Destacar os animais que precisam de um lar, garantindo que eles recebam a atenção e o carinho que merecem.</p>
                        </div>
                        <div class="objective-card">
                            <h4>Facilitar Adoções</h4>
                            <p>Oferecer uma interface amigável para que você possa encontrar e adotar seu novo amigo com facilidade.</p>
                        </div>
                        <div class="objective-card">
                            <h4>Promover a Conscientização</h4>
                            <p>Educar o público sobre a importância da adoção responsável e o impacto positivo de resgatar um animal.</p>
                        </div>
                        <div class="objective-card">
                            <h4>Conectar com ONGs</h4>
                            <p>Facilitar a comunicação entre você e as ONGs que trabalham para ajudar os animais.</p>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </section>

        <!-- Seção 'Criadores'-->
        <section id="criadores">
            <div class="criadores-titulo">
                <h1>Criadores</h1>
            </div>
            <div class="criadores-container">
                <div class="criadores-card" id="card-sheylandia">
                    <h2>Sheylandia</h2>
                    <p>Designer Gráfico</p>
                    <p><strong>Especialidades:</strong> <br>Branding, <br>UI/UX, Ilustração</p>
                    <p><strong>Projetos:</strong> Criação de identidade visual para startups</p>
                    <a href="https://exemplo.com/sheylandia" target="_blank">Portfolio de Sheylandia    </a>
                </div>
                <div class="criadores-card" id="card-bernardo">
                    <h2>Bernardo</h2>
                    <p>Dev Front-End</p>
                    <p><strong>Especialidades:</strong>React, JavaScript, CSS</p>
                    <p><strong>Projetos:</strong> Desenvolvimento de interfaces responsivas</p>
                    <a href="https://exemplo.com/bernardo" target="_blank">Portfolio de Bernardo</a>
                </div>
                <div class="criadores-card" id="card-douglas">
                    <h2>Douglas</h2>
                    <p>Dev Web</p>
                    <p><strong>Especialidades:</strong> PHP, MySQL, WordPress</p>
                    <p><strong>Projetos:</strong> Criação de sites e sistemas personalizados</p>
                    <a href="https://exemplo.com/douglas" target="_blank">Portfolio de Douglas</a>
                </div>
                <div class="criadores-card" id="card-gabriel">
                    <h2>Gabriel</h2>
                    <p>Dev Mobile</p>
                    <p><strong>Especialidades:</strong> Flutter, React Native</p>
                    <p><strong>Projetos:</strong> Aplicativos para iOS e Android</p>
                    <a href="https://exemplo.com/gabriel" target="_blank">Portfolio de Gabriel</a>
                </div>
            </div>
        </section>

        <!-- Seção 'Animais Disponíveis para Adoção' -->
        <section id="adocao" class="secao">
            <div class="container">
                <h2>
                    <h2 id="adotar" style="color: #e9a209;">Adotar</h2>
                </h2>
            
                <div class="container-animais-adotaveis">
                    <div class="bloco-texto">
                        
                        <p>Conheça alguns dos nossos adoráveis animais que estão prontos para encontrar um novo lar. Aqui você pode ver informações sobre cães e gatos disponíveis para adoção. Se você está interessado em adotar, não hesite em clicar nos <strong>"botões ao lado"</strong> para obter mais detalhes sobre cada animal.</p>
                        <p><strong>Não perca a oportunidade de fazer um novo amigo! Clique nos botões ao lado e descubra as opções de adoção.</strong></p>
                        <!-- <a href="pagina-de-adocao.html" class="botao-mais-info">VER MAIS</a> -->
                    </div>


                    <div class="container-imagens">
                        <div class="adopt-card">
                            <!--<img src="dog.webp" alt="Cão">-->
                            <div class="risco-imagem"></div>
                            <h4 class="titulo-card">Conheça Nossos Cães</h4>
                            <p class="descricao-card">Adote um Cão!<br>Descubra todos os cães adoráveis que estão prontos para encontrar um lar amoroso.</p>
                            <a href="#" class="botao-adocao" id="showDogs">Ver Cães para Adoção</a>
                            
                        </div>

                        <div class="adopt-card">
                            <!--<img src="dog.webp" alt="Gato">-->
                            <div class="risco-imagem"></div>
                            <h4 class="titulo-card">Encontre o Gato Perfeito</h4>
                            <p class="descricao-card">Adote um Gato!<br>Explore nossos felinos encantadores que estão esperando para fazer parte da sua família.</p>
                            <a href="#" class="botao-adocao" id="showCats">Ver Gatos para Adoção</a>
                        </div>
                    </div>


                    <!-- Cães Disponíveis Modal -->
                    <div class="modal" id="dogsModal">
                        <div class="modal-content">
                            <span class="close" onclick="closeDogsModal()">&times;</span>
                            <h2>Cães Disponíveis para Adoção</h2>
                            <div class="card-container">
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 1">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Rex</div>
                                        <div class="details">Idade: 3 anos<br>Sexo: Masculino<br>ONG: Cão Feliz</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Rex')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 2">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Bobby</div>
                                        <div class="details">Idade: 2 anos<br>Sexo: Masculino<br>ONG: Amigos dos Animais</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Bobby')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 3">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Fido</div>
                                        <div class="details">Idade: 4 anos<br>Sexo: Masculino<br>ONG: Patas e Focinhos</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Fido')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 4">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Luna</div>
                                        <div class="details">Idade: 1 ano<br>Sexo: Fêmea<br>ONG: Protetores de Animais</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Luna')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 5">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Zeus</div>
                                        <div class="details">Idade: 5 anos<br>Sexo: Masculino<br>ONG: Adote um Amigo</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Zeus')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 6">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Bella</div>
                                        <div class="details">Idade: 3 anos<br>Sexo: Fêmea<br>ONG: Cão Amigo</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Bella')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 7">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Max</div>
                                        <div class="details">Idade: 6 anos<br>Sexo: Masculino<br>ONG: Amor de Patas</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Max')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Cão 8">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Sophie</div>
                                        <div class="details">Idade: 2 anos<br>Sexo: Fêmea<br>ONG: Cães do Amanhã</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Sophie')">Adotar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gatos Disponíveis Modal -->
                    <div class="modal" id="catsModal">
                        <div class="modal-content">
                            <span class="close" onclick="closeCatsModal()">&times;</span>
                            <h2>Gatos Disponíveis para Adoção</h2>
                            <div class="card-container">
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 1">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Mimi</div>
                                        <div class="details">Idade: 2 anos<br>Sexo: Fêmea<br>ONG: Amigos dos Gatos</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Mimi')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 2">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Oliver</div>
                                        <div class="details">Idade: 1 ano<br>Sexo: Masculino<br>ONG: Protetores de Gatos</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Oliver')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 3">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Lili</div>
                                        <div class="details">Idade: 3 anos<br>Sexo: Fêmea<br>ONG: Gatos da Rua</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Lili')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 4">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Simba</div>
                                        <div class="details">Idade: 4 anos<br>Sexo: Masculino<br>ONG: Resgate Animal</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Simba')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 5">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Nina</div>
                                        <div class="details">Idade: 2 anos<br>Sexo: Fêmea<br>ONG: Gatos do Parque</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Nina')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 6">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Tico</div>
                                        <div class="details">Idade: 5 anos<br>Sexo: Masculino<br>ONG: Adoção Felina</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Tico')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 7">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Gato</div>
                                        <div class="details">Idade: 3 anos<br>Sexo: Masculino<br>ONG: Projeto Gato</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Gato')">Adotar</button>
                                    </div>
                                </div>
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" alt="Gato 8">
                                    <div class="color-bar"></div> <!-- Risco colorido na parte inferior da imagem -->
                                    <div class="info">
                                        <div class="name">Flora</div>
                                        <div class="details">Idade: 4 anos<br>Sexo: Fêmea<br>ONG: Felinos de São Paulo</div>
                                        <button class="adopt-button" onclick="openAdoptModal('Flora')">Adotar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de Confirmação de Adoção -->
                    <div class="modal" id="adoptModal">
                        <div class="modal-content2">
                            <span class="close" onclick="closeAdoptModal()">&times;</span>
                            <h2>Confirmação de Adoção</h2>
                            <br>
                            <p>Obrigado por querer adotar um animal! A ONG entrará em contato com você para os próximos passos.</p>
                            <p>Você está adotando: <strong id="animalName"></strong></p>
                            <br>
                            <button class="submit-btn" onclick="confirmAdoption()">Confirmar Adoção</button>
                        </div>
                    </div>


                </div>
            </div>
        </section>

        <!-- Seção 'Contato' -->
        <section id="contato" class="secao">
            <div class="container">
                <h2>Contato</h2>
                <div class="container-contato">
                    <div class="informacoes">
                        <p><b>Informações de Contato</b></p>
                        <p>Para qualquer dúvida ou assistência, você pode entrar em contato conosco através dos seguintes meios:</p>
                        <p><strong>E-mail:</strong> contato@amopet.com.br</p>
                        <p><strong>Telefone:</strong> (11) 99999-9999 - (Segunda a Sexta, das 9h às 17h)</p>
                        <p><strong>WhatsApp:</strong> (11) 98888-8888</p>
                        <p>Estamos aqui para ajudar você a encontrar seu novo amigo de quatro patas!</p>
                        <p><strong>Siga-nos:</strong> 
                            <a href="https://web.whatsapp.com/">WhatsApp</a> | 
                            <a href="https://www.instagram.com/">Instagram</a> | 
                            <a href="https://www.facebook.com/">Facebook</a>
                        </p>
                    </div>
                    <div class="formulario">
                        <h3>Envie uma Mensagem</h3>
                        <form>
                            <div class="grupo-formulario">
                                <label for="nome">Nome:</label>
                                <input type="text" id="nome" name="nome">
                            </div>
                            <div class="grupo-formulario">
                                <label for="email">E-mail:</label>
                                <input type="email" id="email" name="email">
                            </div>
                            <div class="grupo-formulario">
                                <label for="mensagem">Mensagem:</label>
                                <textarea id="mensagem" name="mensagem" rows="4"></textarea>
                            </div>
                            <button type="submit" class="botao-enviar">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>



        

    <!-- Botão de voltar para o topo -->
    <a href="#home" class="back-to-top" id="backToTop">
        <i class="fa-solid fa-arrow-up"></i>
    </a>
    </main>

    <!-- Rodapé -->
    <footer>
        <p>&copy; 2024 AMOPET. Todos os direitos reservados.</p>
    </footer>

    

    

    <script src="script.js">
        
    
    </script>
</body>
</html>
