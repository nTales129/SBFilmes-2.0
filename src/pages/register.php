<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar se as senhas coincidem
    if ($password !== $confirm_password) {
        echo "<script>
        alert('As senhas não coincidem');
        </script>";
    } else {
        // Verificar se o email já está em uso
        $sql_check_email = "SELECT * FROM usuarios WHERE email = ?";
        $stmt_check_email = $pdo->prepare($sql_check_email);
        $stmt_check_email->execute([$email]);
        if ($stmt_check_email->fetch()) {
            echo "Este email já está em uso.";
        } else {
            // Criptografar a senha
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Inserir novo usuário no banco de dados
            $sql_insert_user = "INSERT INTO usuarios (username, password_hash, email) VALUES (?, ?, ?)";
            $stmt_insert_user = $pdo->prepare($sql_insert_user);
            $stmt_insert_user->execute([$username, $password_hash, $email]);

            // Redirecionar para a página de login após o registro bem-sucedido
            header("Location: index.php");
            exit;
        }
    }
}
?>









<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
      
      <link rel="stylesheet" href="../css/style.css">
   <title>SB Filmes - Cadastro</title>
</head>

<body>

   <main>
      <header>
         <div class="logo-caixa">
            <img src="../img/logo.jpg" alt="">
         </div>
         <div class="btn-cadastro">
            <span>Já tem uma Conta?</span>
            <a href="./index.php">Fazer Login</a>
         </div>
      </header>
      <div class="container-login-action">
         <div class="form-wrapper">
            <h2>Cadastro</h2>
            <form action="register.php" method="post">
                <div class="form-control">
                  <input type="text" name="email" required>
                     <label>Email ou número de telefone</label>
         </div>
         <div class="form-control">
            <input type="text" name="username" required>
             <label>Usuário</label>
          </div>
          <div class="form-control">
            <input type="password" name="password" required> 
              <label>Senha</label>
         </div>
        <div class="form-control">
           <input type="password" name="confirm_password" required>
             <label>Confirmar Senha</label>
         </div>
          <button type="submit">Entrar</button>


                <!-- <div class="form-help">
                   <div class="remember-me">
                    <input type="checkbox" id="remember-me">
                    <label for="remember-me">Me lembrar</label>
                   </div>
                   <a href="#">Precisa de ajuda?</a>
                </div> -->
            </form>
            <!-- <p>Novo no SB filmes? <a href="#">Se inscreva agora</a></p>
            <small>
               Esta página é protegida pelo Google reCAPTCHA para garantir que você não é um robô.
               <a href="#">Saiba mais</a>
            </small> -->
        </div>
      </div>
   </main>
   

   
  </body>
  </html>