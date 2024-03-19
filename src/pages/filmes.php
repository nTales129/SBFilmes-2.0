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

// Função para lidar com a ação de curtir um filme
function likeMovie($pdo, $user_id, $movie_id)
{
    // Verifica se o usuário já curtiu este filme antes
    $query = $pdo->prepare("SELECT * FROM filmes_curtidos WHERE user_id = ? AND movie_id = ?");
    $query->execute([$user_id, $movie_id]);
    $result = $query->fetch();

    if ($result) {
        // Se o usuário já curtiu o filme, retorna uma mensagem de erro
        return "Você já curtiu este filme.";
    } else {
        // Se o usuário ainda não curtiu o filme, insere um novo registro na tabela de filmes curtidos
        $insert_query = $pdo->prepare("INSERT INTO filmes_curtidos (user_id, movie_id) VALUES (?, ?)");
        $insert_query->execute([$user_id, $movie_id]);

        // Atualizar a contagem de curtidas na tabela filmes
        $update_query = $pdo->prepare("UPDATE filmes SET curtidas = curtidas + 1 WHERE movie_id = ?");
        $update_query->execute([$movie_id]);
        
        header("Location: filmes.php");
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

// Consultas para selecionar filmes de cada gênero
$queryAcao = $pdo->query("SELECT * FROM filmes WHERE genero = 'acao' ORDER BY titulo ASC LIMIT 5");
$filmesAcao = $queryAcao->fetchAll(PDO::FETCH_ASSOC);
$queryDrama = $pdo->query("SELECT * FROM filmes WHERE genero = 'drama' ORDER BY titulo ASC LIMIT 5");
$filmesDrama = $queryDrama->fetchAll(PDO::FETCH_ASSOC);
$queryComedia = $pdo->query("SELECT * FROM filmes WHERE genero = 'comedia' ORDER BY titulo ASC LIMIT 5");
$filmesComedia = $queryComedia->fetchAll(PDO::FETCH_ASSOC);
$queryFiccao = $pdo->query("SELECT * FROM filmes WHERE genero = 'ficcao' ORDER BY titulo ASC LIMIT 5");
$filmesFiccao = $queryFiccao->fetchAll(PDO::FETCH_ASSOC);
$queryOutros = $pdo->query("SELECT * FROM filmes WHERE genero = 'outro' ORDER BY titulo ASC LIMIT 5");
$filmesOutros = $queryOutros->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter o nome do usuário com base no ID de usuário da sessão
$user_query = $pdo->prepare("SELECT username, email, created_at, avatar_url FROM usuarios WHERE user_id = ?");
$user_query->execute([$_SESSION['user_id']]);
$user_result = $user_query->fetch();
$username = $user_result['username'];
$email = $user_result['email'];
$created_at = $user_result['created_at'];
$avatar_url = $user_result['avatar_url']; 
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/filme.css">
    <title>SB Filmes - Filmes</title>
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
                <li class="side-item active">
                    <a href="./filmes.php">
                        <i class="fa-solid fa-film"></i>
                        <span class="item-description">Filmes</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="./curtidos.php">
                        <i class="fa-solid fa-heart"></i>
                        <span class="item-description">Curtidos<span></span></span>
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
                        <span class="item-description">Ver Perfis</span>
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
            <form class="logo-container" action="#" method="post">
                <div class="input-field">
                    <input type="search" name="buscar" placeholder="Buscar Filme">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>

            <div class="content-container">
                <div class="principal-text">
                    <h2>Veja os principais Filmes!</h2>
                    <p>Ai aqui coloca qualquer coisa so para complementar o título!</p>
                    <div class="categorias">
                        <a href="#acao" class="item-categoria">Ação e Aventura</a>
                        <a href="#drama" class="item-categoria">Drama</a>
                        <a href="#comedia" class="item-categoria">Comédia</a>
                        <a href="#ficcao" class="item-categoria">Ficção Científica e Fantasia</a>
                        <a href="#outros" class="item-categoria">Outros</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="secundary-section" id="acao">
            <div class="title-secundary">
                <h2>Ação e Aventura</h2>
            </div>
            <!-- Seção Ação e Aventura -->
            <div class="content-secundary">
                <?php foreach ($filmesAcao as $filme) : ?>
                    <div class="movie-container">
                        <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
                        <form class="action-box" method="post">
                            <!-- Campo oculto para armazenar o ID do filme -->
                            <input type="hidden" name="movie_id" value="<?= $filme['movie_id'] ?>">
                            <!-- Botão de curtir -->
                            <button class="like-btn" type="submit">
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

        <section class="secundary-section" id="drama">
            <div class="title-secundary">
                <h2> Drama</h2>
            </div>
            <!-- Seção Drama -->
            <div class="content-secundary">
                <?php foreach ($filmesDrama as $filme) : ?>
                    <div class="movie-container">
                        <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
                        <form class="action-box" method="post">
                            <!-- Campo oculto para armazenar o ID do filme -->
                            <input type="hidden" name="movie_id" value="<?= $filme['movie_id'] ?>">
                            <!-- Botão de curtir -->
                            <button class="like-btn" type="submit">
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

        <section class="secundary-section" id="comedia">
            <div class="title-secundary">
                <h2>Comédia</h2>
            </div>
            <!-- Seção Comédia -->
            <div class="content-secundary">
                <?php foreach ($filmesComedia as $filme) : ?>
                    <div class="movie-container">
                        <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
                        <form class="action-box" method="post">
                            <!-- Campo oculto para armazenar o ID do filme -->
                            <input type="hidden" name="movie_id" value="<?= $filme['movie_id'] ?>">
                            <!-- Botão de curtir -->
                            <button class="like-btn" type="submit">
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

        <section class="secundary-section" id="ficcao">
            <div class="title-secundary">
                <h2> Ficção Científica e Fantasia</h2>
            </div>
            <!-- Seção Ficção Científica e Fantasia -->
            <div class="content-secundary">
                <?php foreach ($filmesFiccao as $filme) : ?>
                    <div class="movie-container">
                        <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
                        <form class="action-box" method="post">
                            <!-- Campo oculto para armazenar o ID do filme -->
                            <input type="hidden" name="movie_id" value="<?= $filme['movie_id'] ?>">
                            <!-- Botão de curtir -->
                            <button class="like-btn" type="submit">
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

        <section class="secundary-section" id="outros">
            <div class="title-secundary">
                <h2> Outros</h2>
            </div>
            <!-- Seção Outros -->
            <div class="content-secundary">
                <?php foreach ($filmesOutros as $filme) : ?>
                    <div class="movie-container">
                        <img src="<?= $filme['imagem'] ?>" alt="<?= $filme['titulo'] ?>" class="img-movie">
                        <form class="action-box" method="post">
                            <!-- Campo oculto para armazenar o ID do filme -->
                            <input type="hidden" name="movie_id" value="<?= $filme['movie_id'] ?>">
                            <!-- Botão de curtir -->
                            <button class="like-btn" type="submit">
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
    </main>

    <!-- Modal -->

    <script src="../js/like.js"></script>
    <script src="../js/sidebar.js"></script>
    <script src="../js/curtir.js"></script>
</body>

</html>