<?php
require_once("conexao.php");

session_start();

// Verifica se o usuário está logado
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
