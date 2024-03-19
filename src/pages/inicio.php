<?php
require_once("conexao.php");


session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: index.php");
    exit;
}

// Função para lidar com a ação de curtir um filme
function likeMovie($pdo, $user_id, $movie_id)
{
    // Verifica se o usuário já curtiu este filme antes
    $query = $pdo->prepare("SELECT * FROM filmes_curtidos WHERE user_id = ? AND movie_id = ?");
    $query->execute([$user_id, $movie_id]);
    $result = $query->fetch();

    if ($result) {
        // Se o usuário já curtiu o filme, retorna uma mensagem de erro
       
    } else {
        // Se o usuário ainda não curtiu o filme, insere um novo registro na tabela de filmes curtidos
        $insert_query = $pdo->prepare("INSERT INTO filmes_curtidos (user_id, movie_id) VALUES (?, ?)");
        $insert_query->execute([$user_id, $movie_id]);

        // Atualizar a contagem de curtidas na tabela filmes
        $update_query = $pdo->prepare("UPDATE filmes SET curtidas = curtidas + 1 WHERE movie_id = ?");
        $update_query->execute([$movie_id]);
        
        header("Location: inicio.php");
    }
}

// Verifica se a requisição é do tipo POST e se o ID do filme foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['movie_id'])) {
    // Obtém o ID do filme e o ID do usuário da sessão
    $movie_id = $_POST['movie_id'];
    $user_id = $_SESSION['user_id'];

    // Lida com a ação de curtir o filme
    $like_message = likeMovie($pdo, $user_id, $movie_id);
    echo $like_message;
    exit;
}

// Consulta para selecionar todos os filmes ordenados por ordem alfabética do título
$query = $pdo->query("SELECT * FROM filmes ORDER BY titulo ASC");
$filmes = $query->fetchAll(PDO::FETCH_ASSOC);


// Consulta para obter o nome do usuário com base no ID de usuário da sessão
$user_query = $pdo->prepare("SELECT username, email, created_at, avatar_url FROM usuarios WHERE user_id = ?");
$user_query->execute([$_SESSION['user_id']]);
$user_result = $user_query->fetch();
$username = $user_result['username'];
$email = $user_result['email'];
$created_at = $user_result['created_at'];
$avatar_url = $user_result['avatar_url']; 


// Buscar os últimos 8 filmes adicionados
$query = $pdo->prepare("SELECT * FROM filmes ORDER BY movie_id DESC LIMIT 8");
$query->execute();
$filmes = $query->fetchAll(PDO::FETCH_ASSOC);

// Buscar o último filme adicionado
$queryUltimo = $pdo->prepare("SELECT * FROM filmes ORDER BY movie_id DESC LIMIT 1");
$queryUltimo->execute();
$ultimoFilme = $queryUltimo->fetch(PDO::FETCH_ASSOC);

// Buscar os filmes mais curtidos (ordenados pelo número de curtidas em ordem decrescente)
$query_top_filmes = $pdo->query("SELECT * FROM filmes ORDER BY curtidas DESC LIMIT 8");
$top_filmes = $query_top_filmes->fetchAll(PDO::FETCH_ASSOC);

?>












<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="../css/inicio.css">
   <link rel="stylesheet" href="../css/sidebar.css">
   <title>SB Filmes - Início</title>
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
            <li class="side-item active">
               <a href="./inicio.php">
                  <i class="fa-solid fa-house"></i>
                  <span class="item-description">Inicio</span>
               </a>
            </li>
            <li class="side-item">
               <a href="./filmes.php">
                  <i class="fa-solid fa-film"></i>
                  <span class="item-description">Filmes</span>
               </a>
            </li>
            <li class="side-item">
               <a href="./curtidos.php">
                  <i class="fa-solid fa-heart"></i>
                  <span class="item-description">Curtidos</span>
               </a>
            </li>

            <li class="side-item">
               <a href="./ajuda.php">
                  <i class="fa-solid fa-circle-question"></i>
                  <span class="item-description">Ajuda</span>
               </a>
            </li>
            <li class="side-item">
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
            <img src="../img/logo.jpg" alt="">
         </div>

         <!-- Aqui vai colocar as info do último filme adicionado -->
         <div class="content-container">
            <div class="img-container">
               <img src="<?= $ultimoFilme['imagem'] ?>" alt="<?= $ultimoFilme['titulo'] ?>">
            </div>

            <div class="text-movie-container">
               <h2 class="title-movie-main"><?= $ultimoFilme['titulo'] ?></h2>

               <ul class="infos-movie-main">
                  <li>Ano de Lançamento: <?= $ultimoFilme['ano'] ?></li>
                  <li>Diretor: <?= $ultimoFilme['diretor'] ?></li>
                  <li>Duração: <?= $ultimoFilme['duracao'] ?></li>
               </ul>

               <p class="desc-movie-main">
                  <?= $ultimoFilme['descricao'] ?>
               </p>

               <form class="action-box">
               <button class="like-btn" type="submit">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                  <a href="filmeDetalhe.php?id=<?= $ultimoFilme['movie_id'] ?>" class="coment-btn" type="button">
                     <i class="fa-solid fa-location-arrow"></i>Ver Mais
                  </a>
               </form>
            </div>
         </div>
      </section>

      <section class="secundary-section">

         <div class="title-secundary">
            <h2><i class="fa-solid fa-fire" style="color: red;"></i> Top 8 Filmes mais curtidos</h2>
         </div>

         <div class="content-secundary">
    <?php foreach ($top_filmes as $filme) : ?>
        <div class="movie-container">
            <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
            <form class="action-box">
                <!-- Botão de curtir -->
                <button class="like-btn" type="submit" data-movie-id="<?= $filme['movie_id'] ?>">
                    <i class="fa-regular fa-heart"></i>
                </button>
                <!-- Botão de ver mais -->
                <a href="filmeDetalhe.php?id=<?= $filme['movie_id'] ?>" class="coment-btn" type="button">
                    <i class="fa-solid fa-location-arrow"></i>Ver Mais
                </a>
            </form>
        </div>
    <?php endforeach; ?>
</div>
      </section>

      <section class="secundary-section">

         <div class="title-secundary">
            <h2><i class="fa-solid fa-circle-down" style="color: blue;"></i> Ultimos filmes adcionados</h2>
         </div>

         <div class="content-secundary">

            <?php foreach ($filmes as $filme) : ?>
               <div class="movie-container">
                  <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
                  <form class="action-box">
                     <button class="like-btn" type="submit" data-movie-id="<?= $filme['movie_id'] ?>">
                        <i class="fa-regular fa-heart"></i>
                     </button>

                     <a href="filmeDetalhe.php?id=<?= $filme['movie_id'] ?>" class="coment-btn" type="button">
                        <i class="fa-solid fa-location-arrow"></i>Ver Mais
                     </a>

                  </form>
               </div>
            <?php endforeach; ?>
         </div>
      </section>
   </main>


   <script src="../js/like.js"></script>
   <script src="../js/sidebar.js"></script>
   <script src="../js/curtir.js"></script>
</body>

</html>