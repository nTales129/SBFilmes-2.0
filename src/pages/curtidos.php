<?php
require_once("conexao.php");

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
  // Se não estiver logado, redireciona para a página de login
  header("Location: index.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Buscar os IDs dos filmes que o usuário curtiu
$stmt = $pdo->prepare("SELECT movie_id FROM filmes_curtidos WHERE user_id = ?");
$stmt->execute([$user_id]);
$filmes_curtidos = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Buscar os detalhes dos filmes curtidos
$filmesAcao = [];
foreach ($filmes_curtidos as $filme_id) {
  $stmt = $pdo->prepare("SELECT * FROM filmes WHERE movie_id = ?");
  $stmt->execute([$filme_id]);
  $filme = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($filme) {
    $filmesAcao[] = $filme;
  }
}

// Consulta para obter o nome do usuário com base no ID de usuário da sessão
$user_query = $pdo->prepare("SELECT username, email, created_at, avatar_url FROM usuarios WHERE user_id = ?");
$user_query->execute([$_SESSION['user_id']]);
$user_result = $user_query->fetch();
$username = $user_result['username'];
$email = $user_result['email'];
$created_at = $user_result['created_at'];
$avatar_url = $user_result['avatar_url']; // Adicione esta linha para obter o URL da imagem de perfil do usuário

if (!isset($_SESSION['user_id'])) {
  // Se não estiver logado, redireciona para a página de login
  header("Location: index.php");
  exit;
}

// Verifique se o ID do filme foi recebido na solicitação POST
if (isset($_POST['movieId'])) {
  // Obtenha o ID do usuário atualmente logado (substitua por sua lógica)
  $userId = 6;

  // Obtenha o ID do filme da solicitação POST
  $movieId = $_POST['movieId'];

  // Verifique se o usuário já curtiu o filme
  $stmt = $pdo->prepare("SELECT * FROM filmes_curtidos WHERE user_id = ? AND movie_id = ?");
  $stmt->execute([$userId, $movieId]);
  $curtidaExistente = $stmt->fetch();

  // Se o usuário ainda não curtiu o filme, adicione a curtida
  if (!$curtidaExistente) {
    $stmt = $pdo->prepare("INSERT INTO filmes_curtidos (user_id, movie_id) VALUES (?, ?)");
    $stmt->execute([$userId, $movieId]);



    // Responda com sucesso
    http_response_code(200);
  } else {
    // Responda que o usuário já curtiu o filme
    http_response_code(409);
  }
} else {
  // Responda com erro
  http_response_code(400);
}

?>








<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../css/sidebar.css">
  <link rel="stylesheet" href="../css/curtidos.css">
  <title>SB Filmes - Curtidos</title>
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
        <li class="side-item">
          <a href="./filmes.php">
            <i class="fa-solid fa-film"></i>
            <span class="item-description">Filmes</span>
          </a>
        </li>
        <li class="side-item active">
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
        <h2>Seus Filmes Curtidos</h2>
        <p><i class="fa-solid fa-user"></i><?php echo $username; ?></p>
      </div>

      <div class="content-container">
        <?php foreach ($filmesAcao as $filme) : ?>
          <div class="movie-like-container">
            <div class="img-container">
              <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
            </div>
            <div class="text-movie-container">
              <h2 class="title-movie-main"><?= $filme['titulo'] ?></h2>
              <form class="action-box" method="POST" action="">
                <input type="hidden" name="movieId" value="<?= $filme['movie_id'] ?>">
                <button class="like-btn" type="submit">
                  <i class="fa-solid fa-heart"></i>
                </button>
                <a href="filmeDetalhe.php?id=<?= $filme['movie_id'] ?>" class="coment-btn" type="button">
                  <i class="fa-solid fa-location-arrow"></i>Ver Mais
                </a>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <script src="../js/sidebar.js"></script>
</body>

</html>