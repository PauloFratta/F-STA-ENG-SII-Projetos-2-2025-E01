<?php
session_start();
include('conexao.php');

// Se o formulário foi enviado
if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['cpf']) && isset($_POST['senha'])){

    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $cpf = $mysqli->real_escape_string($_POST['cpf']);
    $senha = $mysqli->real_escape_string($_POST['senha']);

    // Verificações simples
    if(strlen($nome) == 0){
        $erro = "Preencha seu nome.";
    } elseif(strlen($email) == 0){
        $erro = "Preencha seu email.";
    } elseif(strlen($cpf) == 0){
        $erro = "Preencha seu CPF.";
    } elseif(strlen($senha) == 0){
        $erro = "Preencha sua senha.";
    } else {

        // Verifica se o email já existe
        $check = $mysqli->query("SELECT id FROM usuarios WHERE email = '$email'");
        if($check->num_rows > 0){
            $erro = "E-mail já cadastrado.";
        } else {

            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere no banco
            $sql = "INSERT INTO usuarios (nome, email, cpf, senha)
                    VALUES ('$nome', '$email', '$cpf', '$senha_hash')";

            if($mysqli->query($sql)){
                header("Location: login.php?cadastrado=1");
                exit;
            } else {
                $erro = "Erro ao cadastrar: " . $mysqli->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="icon" type="image/png" href="../assets/engrenagem.png" />
    <title>Cadastro | EngFlow</title>
</head>
<body>

    <main class="flex align-items-center justify-content-center">
        <section id="mobile" class="flex"></section>

        <section id="auth" class="flex direction-column">
            <div class="panel login flex direction-column">

                <h1 title="Logo" class="flex justify-content-center">
                    <img src="../assets/images/logoatualizada.png" alt="logo">
                </h1>

                <?php if(isset($erro)): ?>
                    <div style="color: red; margin-bottom: 10px;">
                        <?= $erro ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">

                    <input name="nome" placeholder="Nome completo" required>
                    <input name="cpf" placeholder="CPF" required maxlength="14">
                    <input name="email" type="email" placeholder="Email" required>
                    <input name="senha" type="password" placeholder="Senha" required>

                    <button type="submit">Cadastrar</button>
                </form>
            </div>

            <div class="panel register flex justify-content-center">
                <p>Já tem uma conta?</p>
                <a href="login.php">Conecte-se</a>
            </div>
        </section>
    </main>

</body>
</html>
