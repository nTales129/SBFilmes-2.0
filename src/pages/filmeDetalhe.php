<?php
// Verificar se o ID do filme foi passado na URL
if(isset($_GET['id'])) {
    // Conectar ao banco de dados
    require_once("conexao.php");

    session_start();

    // Verifica se o usuário está logado
    if (!isset($_SESSION['user_id'])) {
        // Se não estiver logado, redireciona para a página de login
        header("Location: index.php");
        exit;
    }

    // Preparar a consulta SQL para obter as informações do filme com base no ID fornecido
    $query = $pdo->prepare("SELECT * FROM filmes WHERE movie_id = :id");
    $query->bindParam(':id', $_GET['id']);
    $query->execute();

    // Verificar se o filme foi encontrado
    if($query->rowCount() > 0) {
        // Obter os dados do filme
        $filme = $query->fetch(PDO::FETCH_ASSOC);

    } else {
        // Se o filme não for encontrado, exibir uma mensagem de erro
        echo "Filme não encontrado.";
    }
} else {
    // Se nenhum ID de filme for fornecido na URL, exibir uma mensagem de erro
    echo "ID do filme não fornecido.";
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

// Se o formulário de comentário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filme_id'], $_POST['comentario'])) {
  // Conectar ao banco de dados
  require_once("conexao.php");

  // Verificar se o usuário está logado
  if (!isset($_SESSION['user_id'])) {
      // Redirecionar para a página de login se não estiver logado
      header("Location: index.php");
      exit;
  }

  // Recuperar dados do formulário
  $filme_id = $_POST['filme_id'];
  $comentario = $_POST['comentario'];
  $user_id = $_SESSION['user_id'];

  // Inserir o comentário no banco de dados
  $insert_comment_query = $pdo->prepare("INSERT INTO comentarios (user_id, movie_id, comentario_texto) VALUES (?, ?, ?)");
  $insert_comment_query->execute([$user_id, $filme_id, $comentario]);
}

// Recuperar os comentários do banco de dados para o filme atual
$comments_query = $pdo->prepare("SELECT comentarios.*, usuarios.username FROM comentarios INNER JOIN usuarios ON comentarios.user_id = usuarios.user_id WHERE comentarios.movie_id = ?");
$comments_query->execute([$filme['movie_id']]);
$comments = $comments_query->fetchAll(PDO::FETCH_ASSOC);
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
   <link rel="stylesheet" href="../css/filmeDetalhe.css">
   <title>SB Filmes - Detalhe do Filme</title>
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
            <h1></h1>
        </div>

        <!-- Aqui vai colocar as info do filme -->
        <div class="content-container">

            <div class="img-container">
                <!-- Adicione a tag PHP para exibir a imagem do filme -->
                <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>">
            </div>

            <div class="text-movie-container">

                <!-- Substitua o título estático pelo título dinâmico do filme -->
                <h2 class="title-movie-main"><?= $filme['titulo'] ?></h2>

                <!-- Substitua as informações estáticas pelas informações dinâmicas do filme -->
                <ul class="infos-movie-main">
                    <li>Ano de Lançamento: <?= $filme['ano'] ?></li>
                    <li>Diretor: <?= $filme['diretor'] ?></li>
                    <li>Duração: <?= $filme['duracao'] ?></li>
                </ul>

                <!-- Substitua a descrição estática pela descrição dinâmica do filme -->
                <p class="desc-movie-main"><?= $filme['descricao'] ?></p>

            </div>
        </div>

    </section>

    <section class="comentar" >
         <form class="form-container" action="#" method="post">
         <input type="hidden" name="filme_id" value="<?= $filme['movie_id'] ?>">
            <!-- <div class="input-field">
               <label for="avaliacao">Avaliar de 1 a 5</label>
               <input type="number" name="avaliacao" min="1" max="5" required>
            </div> -->
            <div class="input-field">
               <label for="comentario">Comentar</label>
               <textarea name="comentario"  cols="30" rows="5"></textarea>
            </div>
            <button type="submit">Enviar</button>
         </form>
      </section>

      <!-- Seção de Comentários -->
<section class="secundary-section coment-section">
    <h2>Comentários</h2>
    <div class="comments-container">
        <?php foreach ($comments as $comment) : ?>
            <div class="comment">
                <strong><i class="fa-solid fa-user"></i><?= $comment['username'] ?></strong><p> <?= $comment['comentario_texto'] ?></p>
                <span class="timestamp"><?= $comment['timestamp'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>

</main>

   <!-- Modal -->


    <script src="../js/sidebar.js"></script>
</body>
</html>