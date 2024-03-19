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

// Verifica se o formulário de atualização de imagem foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['img'])) {
  $target_dir = "../img/avatars/";
  $imageFileType = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
  $new_filename = $target_dir . $_SESSION['user_id'] . '_' . uniqid() . '.' . $imageFileType;

  // Verifica se o diretório de destino existe e cria-o se não existir
  if (!file_exists($target_dir)) {
    if (!mkdir($target_dir, 0777, true)) {
      echo "Falha ao criar o diretório de upload.";
      exit;
    }
  }

  // Verifica se o arquivo é uma imagem real
  $check = getimagesize($_FILES["img"]["tmp_name"]);
  if ($check === false) {
    echo "O arquivo não é uma imagem válida.";
    exit;
  }

  // Verifica o tamanho da imagem (limite de 5MB neste exemplo)
  if ($_FILES["img"]["size"] > 5000000) {
    echo "Desculpe, o arquivo é muito grande. Por favor, escolha um arquivo menor.";
    exit;
  }

  // Move o arquivo para o diretório de destino com um nome único
  if (!move_uploaded_file($_FILES["img"]["tmp_name"], $new_filename)) {
    echo "Erro ao fazer upload do arquivo.";
    exit;
  }

  // Atualiza o caminho da imagem do usuário no banco de dados
  $update_query = $pdo->prepare("UPDATE usuarios SET avatar_url = ? WHERE user_id = ?");
  if ($update_query->execute([$new_filename, $_SESSION['user_id']])) {
    // Atualiza a variável de sessão com o novo caminho da imagem
    $_SESSION['avatar_url'] = $new_filename;
    header("Location: usuario.php");
    exit;
  } else {
    echo "Erro ao atualizar a imagem do perfil.";
    exit;
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../css/inicio.css">
  <link rel="stylesheet" href="../css/sidebar.css">
  <link rel="stylesheet" href="../css/usuario.css">
  <title>SB Filmes - Ver Perfil</title>
</head>

<body>
  <nav id="sidebar">
    <div id="sidebar_content">
      <div id="user">
        <img src="<?= isset($avatar_url) ? $avatar_url : '../img/avatar.png' ?>" alt="avatar" id="user_avatar" />
        <p id="user_infos">
          <!-- Exibe o nome do usuário -->
          <span class="item-description"><?php echo $username; ?></span>
          <span class="item-description"><?php echo $email; ?></span>
        </p>
      </div>
      <ul id="side_items">
        <!-- Itens do menu lateral -->
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

        <li class="side-item">
          <a href="./ajuda.php">
            <i class="fa-solid fa-circle-question"></i>
            <span class="item-description">Ajuda</span>
          </a>
        </li>
        <li class="side-item active">
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

      </div>

      <div class="usuario-container">
        <div class="avatar-img-container">
          <img src="<?= isset($avatar_url) ? $avatar_url : '../img/avatar.png' ?>">
        </div>
        <div class="infos-user">
          <div class="info-field">
            <h3>Nome:</h3>
            <p><?= $username ?></p>
          </div>
          <div class="info-field">
            <h3>Email:</h3>
            <p><?= $email ?></p>
          </div>
          <div class="info-field">
            <h3>Data de Criação do Perfil:</h3>
            <p><?= date('d/m/Y', strtotime($created_at)) ?></p>
          </div>
        </div>
        <div class="input-field">
          <form action="" method="post" enctype="multipart/form-data">
            <label for="img">Imagem:</label>
            <input type="file" id="img" name="img" accept="image/*" required>
            <button type="submit">Trocar Foto</button>
          </form>
        </div>
      </div>



    </section>

  </main>

  <!-- Modal -->


  <script src="../js/sidebar.js"></script>
</body>

</html>