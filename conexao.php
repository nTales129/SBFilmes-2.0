<?php
// Definindo as informações de conexão
$banco = 'sbfilmes';
$usuario = 'root';
$senha = '';
$servidor = 'localhost';

// Tentando estabelecer a conexão com o banco de dados
try {
  // Criando uma instância da classe PDO para conectar ao banco de dados
  $pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $e) {
  // Capturando exceções em caso de falha na conexão
  echo 'Erro nos dados de conexão com o Banco!<br>' . $e;
}
?>