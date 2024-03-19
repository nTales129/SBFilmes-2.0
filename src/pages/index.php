<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar o banco de dados para obter os dados do usuário
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verificar se o usuário existe e a senha está correta
    if ($user && password_verify($password, $user['password_hash'])) {
        // Iniciar a sessão para o usuário logado
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Redirecionar para a página de início após o login bem-sucedido
        header("Location: inicio.php");
        exit;
    } else {
        echo "<script>alert('Credenciais inválidas. Tente novamente.')</script>";
        
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>SB Filmes</title>
</head>

<body>

   <main>
      <header>
         <div class="logo-caixa">
            <img src="../img/logo.jpg" alt="">
         </div>
         <div class="btn-cadastro">
            <span>Não tem um Login?</span>
            <a href="./register.php">Cadastrar</a>
         </div>
      </header>
      <div class="container-login-action">
         <div class="form-wrapper">
            <h2>Login</h2>
            <form action="index.php" method="post">
    <div class="form-control">
        <input type="text" name="email" required>
        <label>Email ou número de telefone</label>
    </div>
    <div class="form-control">
        <input type="password" name="password" required>
        <label>Senha</label>
    </div>
    <button type="submit">Entrar</button>
    <div class="form-help">
       <div class="remember-me">
        <input type="checkbox" id="remember-me">
        <label for="remember-me">Me lembrar</label>
       </div>
       <a href="#">Precisa de ajuda?</a>
    </div>
</form>
            <!-- <p>Novo no SB filmes? <a href="#">Se inscreva agora</a></p>
            <small>
               Esta página é protegida pelo Google reCAPTCHA para garantir que você não é um robô.
               <a href="#">Saiba mais</a>
            </small> -->
        </div>
      </div>
   </main>
   
   <section class="info-container">
      <div class="img">
         <img src="../img/bg-main-removebg-preview.png" alt="">
      </div>
      <div class="text-container">
         <h3>O melhor site de Gerenciamento de Filmes!!</h3>
         <p>Desfrute de uma ampla variedade de filmes, cobrindo todos os gêneros e épocas, em uma biblioteca cinematográfica vasta e envolvente.</p>
      </div>
   </section>


   <!-- <section class="gallery">
      <div class="text-container">
         <h3>Os melhores filmes para você ver,avaliar e comentar!</h3>
         <p>Descubra novos filmes e histórias que vão fazer parte do seu dia a dia!</p>
      </div>
      <div class="gallery-container">
         <img src="../img/piratas-do-caribe.jpg" alt="">
         <img src="..img/avengers.jpg" alt="">
         <img src="..img/avatar.jpg" alt="">
         <img src="../img/kny-mugen-train.png" alt="">
         <img src="../img/toy-story-3.jpg" alt="">
         <img src="../img/Interstellar.png" alt="">
         <img src="../img/barbie.jpg" alt="">
         <img src="../img/o-auto-da-compadecida.jpg" alt="">
         <img src="../tropa-de-elite.jpg" alt="">
      </div>
   </section> -->

   <section class="faq">
      <h3>Perguntas Frequentes <i class="fa-solid fa-circle-question"></i></h3>
      <div class="faq-container" id="container-faq">
         <div class="accordion">
            <button class="accordion-header">
               <span>É possível fazer o download de filmes para assistir offline?</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Essa função infelizmente não esta disponivel no momento, talvez em atualizações futuras essa função seja adicionada :-)</p>
            </div>
         </div>
         <div class="accordion">
            <button class="accordion-header">
               <span>Como faço para assistir a filmes online no site?</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Basta acessar a página do filme desejado e clicar em "Reproduzir" para streaming direto ou verificar opções de download ou aluguel.</p>
            </div>
         </div>
         <div class="accordion">
            <button class="accordion-header">
               <span>Onde encontro informações sobre classificação indicativa e conteúdo dos filmes?</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Detalhes sobre classificação e conteúdo estão disponíveis na página de cada filme, ajudando na escolha informada.</p>
            </div>
         </div>
         <div class="accordion">
            <button class="accordion-header">
               <span>Quais são os filmes mais recentes adicionados?</span>
               <i class="fa-solid fa-chevron-down arrow"></i>
            </button>
            <div class="accordion-body">
               <p>Confira a seção "Novidades" em nossa página inicial para ver os últimos filmes incluídos em nossa biblioteca.</p>
            </div>
         </div>
      </div>
   </section>

   <footer>
      <div class="footer-content">
        <div class="footer-section">
          <h3>Siga-nos</h3>
        </div>
        <div class="footer-section">
          <h3>Suporte ao Cliente</h3>
          <p>(37)4002-8922</p>
        </div>
        <div class="footer-section">
          <h3>Política de Privacidade</h3>
        </div>
        <div class="footer-section">
          <h3>Sobre Nós</h3>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2024 SBfilmes. Todos os direitos reservados.</p>
      </div>
    </footer>
  
  </body>
  </html>

   <script src="../js/accordion.js"></script>
</body>

</html>