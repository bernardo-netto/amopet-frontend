<?php
include 'config.php'; // Inclui o arquivo de configuração para conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura o e-mail do usuário
    $email = $conn->real_escape_string($_POST['email']);
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    // Atualiza a senha no banco de dados
    $sql = "UPDATE usuarios SET senha='$nova_senha' WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        echo "Senha redefinida com sucesso!";
    } else {
        echo "Erro ao redefinir a senha: " . $conn->error;
    }
}
?>

<!-- Formulário para redefinir a senha -->
<form action="redirecionar_redefinicao.php" method="POST">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
    <input type="password" name="nova_senha" placeholder="Nova Senha" required>
    <button type="submit">Redefinir Senha</button>
</form>
