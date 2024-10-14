<?php

    // Definindo as credenciais de conexão com o banco de dados
    $servername = 'localhost'; // Nome do servidor (geralmente 'localhost' para acesso local)
    $username = 'root'; // Nome de usuário do banco de dados
    $password = ''; // Senha do banco de dados (vazia, no caso do root em ambientes locais)
    $dbname = 'amopet_usuarios'; // Nome do banco de dados a ser acessado

    // Criar conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar se houve erro na conexão
    if ($conn->connect_error) {
        // Se a conexão falhar, exibe a mensagem de erro e encerra o script
        die("Conexão falhou: " . $conn->connect_error);
    } 

?>

