<?php
// index.php

require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifique se todos os campos obrigatórios não estão vazios
    if (!empty($_POST["titulo_filme"]) && !empty($_POST["descricao_filme"]) && !empty($_POST["ano_filme"]) &&
            !empty($_POST["diretor_filme"]) && !empty($_POST["duracao_filme"]) &&
            !empty($_POST["imagem_filme"])) {

        // Obtenha os dados do formulário
        $titulo = $_POST["titulo_filme"];
        $descricao = $_POST["descricao_filme"];
        $ano = $_POST["ano_filme"];
        $diretor = $_POST["diretor_filme"];
        $duracao = $_POST["duracao_filme"];
        $imagem = $_POST["imagem_filme"];
        $genero = $_POST["genero_filme"]; // Alteração aqui

        try {
            // Inserir dados na tabela 'filmes'
            $query = $pdo->prepare("INSERT INTO filmes (titulo, descricao, ano, diretor, duracao, imagem, genero) 
                            VALUES (:titulo, :descricao, :ano, :diretor,  :duracao, :imagem, :genero)"); // Alteração aqui

            $query->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $query->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $query->bindParam(':ano', $ano, PDO::PARAM_STR);
            $query->bindParam(':diretor', $diretor, PDO::PARAM_STR);
            $query->bindParam(':duracao', $duracao, PDO::PARAM_STR);
            $query->bindParam(':imagem', $imagem, PDO::PARAM_STR);
            $query->bindParam(':genero', $genero, PDO::PARAM_STR); // Alteração aqui

            $query->execute();
            echo "Filme cadastrado com sucesso.";
        } catch (PDOException $e) {
            echo "Erro ao cadastrar filme: " . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos obrigatórios.";
    }
}
?>








<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/cadastroFilme.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Cadastro de Filmes</title>
    </head>
    <body>
        <main>
            <h2><i class="fa-solid fa-tv"></i> Cadastrar Filmes</h2>
            <form class="container" action="#" method="post">
                <div class="input-field">
                    <label for="titulo">Titulo:</label>
                    <input type="text" name="titulo_filme" id="titulo_filme" required>
                </div>
                <div class="input-field">
                    <label for="descricao">Descrição:</label>
                    <input type="text" name="descricao_filme" id="descricao_filme" required>
                </div>
                <div class="input-field">
                    <label for="ano">Ano:</label>
                    <input type="text" name="ano_filme" id="ano_filme" required>
                </div>
                <div class="input-field">
                    <label for="">Diretor:</label>
                    <input type="text" name="diretor_filme" id="diretor_filme" required>
                </div>
                <!-- <div class="input-field">
                    <label for="">Classificação:</label>
                    <input type="text" name="classificacao_filme" id="classificacao_filme" required>
                </div> -->
                <div class="input-field">
                    <label for="">Imagem:</label>
                    <input type="text" name="imagem_filme" id="imagem_filme" required>
                </div>
                <div class="input-field">
                    <label for="">Duração:</label>
                    <input type="text" name="duracao_filme" id="duracao_filme" required>
                </div>
                <div class="input-field">
                    <label for="">Gênero:</label>
                    <select name="genero_filme" id="genero_filme" required>
                        <option value="acao">Ação e Aventura</option>
                        <option value="drama">Drama</option>
                        <option value="comedia">Comédia</option>
                        <option value="ficcao">Ficção Científica e Fantasia</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
                <button type="submit">Enviar</button>
            </form>
        </main>

    </body>
</html>
