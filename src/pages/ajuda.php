<?php
// Inicia a sessão para gerenciar a autenticação do usuário
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: index.php");
    exit;
}

// Requer o arquivo de conexão com o banco de dados
require_once("conexao.php");

// Consulta para obter o nome do usuário com base no ID de usuário da sessão
$user_query = $pdo->prepare("SELECT username, email, created_at, avatar_url FROM usuarios WHERE user_id = ?");
$user_query->execute([$_SESSION['user_id']]);
$user_result = $user_query->fetch();
$username = $user_result['username'];
$email = $user_result['email'];
$created_at = $user_result['created_at'];
$avatar_url = $user_result['avatar_url']; // Adicione esta linha para obter o URL da imagem de perfil do usuário

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="../css/inicio.css">
   <link rel="stylesheet" href="../css/sidebar.css">
   <link rel="stylesheet" href="../css/ajuda.css">
   <title>SB Filmes - Ajuda</title>
</head>
<body>
   <nav id="sidebar">
      <div id="sidebar_content">
        <div id="user">
        <img src="<?= isset($avatar_url) ? $avatar_url : '../img/avatar.png' ?>" alt="avatar" id="user_avatar" />
          <p id="user_infos">
          <span class="item-description"><?php echo $username; ?></span>
          <span class="item-description"><?php echo $email; ?></span>
          </p>
        </div>
        <ul id="side_items">
          <li class="side-item ">
            <a href="./inicio.php">
               <i class="fa-solid fa-house"></i>
              <span class="item-description">Inicio</span>
            </a>
          </li>
          <li class="side-item ">
            <a href="./filmes.php">
               <i class="fa-solid fa-film"></i>
               <span class="item-description">Filmes</span>
            </a>
          </li>
          <li class="side-item ">
            <a href="./curtidos.php">
               <i class="fa-solid fa-heart"></i>
              <span class="item-description">Curtidos</span>
            </a>
          </li>
          
          <li class="side-item active">
            <a href="./ajuda.php">
               <i class="fa-solid fa-circle-question"></i>
              <span class="item-description">Ajuda</span>
            </a>
          </li>
          <li class="side-item ">
            <a href="./usuario.php">
               <i class="fa-solid fa-user"></i>
              <span class="item-description">Ver Perfil</span>
            </a>
          </li>
        </ul>
        <button id="open_btn">
          <i class="fa-solid fa-chevron-right" id="open_btn_icon"></i>
        </button>
      </div>
      <div id="logout">
        <a href="./index.php" id="logout_btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="item-description">Sair</span>
        </a>
      </div>
    </nav>

    <main>
      <section class="container-main">
         <div class="logo-container">
            <h1><img src="../img/logo.jpg" alt=""></h1>
         </div>
         <section class="faq">
      <h3>Ajuda <i class="fa-solid fa-circle-question"></i></h3>
      <div class="faq-container" id="container-faq">
         <div class="accordion">
            <button class="accordion-header">
              <!-- pergunta 1 -->
               <span>Como faço para trocar minha foto de perfil?</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Para trocar sua foto de perfil, vá para a página de perfil e clique no botão "Enviar" ao lado do campo "Selecionar nova Imagem". Escolha a imagem que deseja usar e clique em "Enviar" para atualizar sua foto de perfil.</p>
            </div>
         </div>
         <div class="accordion">
          <!-- pergunta 2 -->
            <button class="accordion-header">
               <span>Como faço para assistir a um filme?</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Para assistir a um filme, vá para a página de filmes, encontre o filme que deseja assistir e clique no botão "Ver Mais". Isso abrirá uma página com mais detalhes sobre o filme, onde você pode encontrar opções para assistir.</p>
            </div>
         </div>
         <!-- pergunta 3 -->
         <div class="accordion">
            <button class="accordion-header">
               <span>Como faço para fazer o meu cadastro?</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Para fazer o cadastro no site, no início do site clique em "Cadastrar", insira o seu email,nome,e crie uma senha e novamente confime sua senha. </p>
            </div>
         </div>
         <div class="accordion">
          <!-- pergunta 4 -->
            <button class="accordion-header">
               <span>Como é a política de preços para acessar filmes ou planos premium?
</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Assinatura Mensal/Anual: Os usuários podem pagar uma taxa mensal ou anual para ter acesso ilimitado a todos os filmes e funcionalidades premium do site.</p>
              </p>Acesso Gratuito com Anúncios: Nesta opção é oferecemos acesso gratuito aos filmes, mas com a exibição de anúncios. Os usuários podem optar por uma assinatura premium para assistir aos filmes sem interrupções publicitárias.
               </p>
            </div>
         </div>
      </div>
   </section>
      </section>
    </main>
   <!-- Modal -->
    <script src="../js/sidebar.js"></script>
    <script src="../js/accordion.js"></script>
</body>
</html>
