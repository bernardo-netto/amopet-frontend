# Documentação PHP com HTML

Este documento fornece uma visão geral sobre como integrar PHP com HTML para interação com bancos de dados. Inclui exemplos práticos para redefinição de senha e criação de contas.

## Sumário

1. [Introdução](#introdução)
2. [Requisitos](#requisitos)
3. [Código PHP](#código-php)
4. [Instruções de Uso](#instruções-de-uso)
5. [Considerações Finais](#considerações-finais)

## Introdução

A integração de PHP com HTML permite criar aplicações web dinâmicas. Neste exemplo, abordamos a redefinição de senha e o registro de novos usuários.

## Requisitos

- Servidor web com suporte a PHP
- Banco de dados MySQL
- Biblioteca de envio de e-mails configurada

## Código PHP

```php
<?php
include 'config.php'; // Conexão com o banco de dados

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Redefinição de senha
    if (isset($_POST['campoEmailRedefinir'])) {
        $email = $conn->real_escape_string($_POST['campoEmailRedefinir']);
        // Verifica se o e-mail existe
        $sql = "SELECT * FROM usuarios WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Link para redefinir a senha
            $link = "http://localhost/redefinicao.php?email=" . urlencode($email);
            $mensagem = "Clique no seguinte link para redefinir sua senha: " . $link;
            mail($email, "Redefinir Senha", $mensagem);
            echo "Um e-mail foi enviado com instruções.";
        } else {
            echo "E-mail não encontrado.";
        }
    }

    // Criação de conta
    if (isset($_POST['submit'])) {
        $email = $conn->real_escape_string($_POST['campoNovoEmail']);
        $nome_usuario = $conn->real_escape_string($_POST['campoNovoUsuario']);
        $senha = password_hash($_POST['campoNovaSenha'], PASSWORD_DEFAULT);
        $data_nascimento = $_POST['campoDataNascimento'];
        $genero = $conn->real_escape_string($_POST['campoGenero']);

        $sql_usuarios = "INSERT INTO usuarios (email, nome_usuario, senha, data_nascimento, genero) VALUES ('$email', '$nome_usuario', '$senha', '$data_nascimento', '$genero')";
        
        if ($conn->query($sql_usuarios) === TRUE) {
            echo "Conta criada com sucesso!";
        } else {
            echo "Erro ao criar conta: " . $conn->error;
        }
    }
}

$conn->close(); // Fecha a conexão
?>
