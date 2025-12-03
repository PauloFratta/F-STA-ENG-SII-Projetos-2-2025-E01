<?php
session_start();
include('conexao.php');

if(isset($_POST['email']) && isset($_POST['password'])){

    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $mysqli->real_escape_string($_POST['password']);

    // validações simples
    if(strlen($email) == 0){
        $erro = "Preencha seu e-mail.";
    } elseif(strlen($senha) == 0){
        $erro = "Preencha sua senha.";
    } else {

        // busca usuário pelo email
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $resultado = $mysqli->query($sql);

        if($resultado->num_rows == 1){

            $usuario = $resultado->fetch_assoc();

            // verifica a senha criptografada
            if(password_verify($senha, $usuario['senha'])){

                // inicia sessão
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];

                header("Location: ../paginas/index.html");
                exit;

            } else {
                $erro = "Senha incorreta.";
            }

        } else {
            $erro = "E-mail não encontrado.";
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
    <link rel="icon" type="image/png" href="../assets/images/Group 1.png">
    <title>Login | Emplink</title>
</head>
<body>

    <main class="flex align-items-center justify-content-center">

        <section id="mobile" class="flex"></section>

        <section id="auth" class="flex direction-column">

            <div class="panel login flex direction-column">
                <h1 class="flex justify-content-center">
                    <img src="../assets/images/logoatualizada.png"
                        alt="Logo"
                        style="cursor:pointer;"
                        onclick="window.location.href='../paginas/index.html'">
                </h1>

                <?php if(isset($erro)): ?>
                    <div style="color:red; margin-bottom: 10px;">
                        <?= $erro ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['cadastrado'])): ?>
                    <div style="color:green; margin-bottom: 10px;">
                        Cadastro realizado com sucesso! Faça login.
                    </div>
                <?php endif; ?>

                <form action="" method="POST">

                    <input name="email" placeholder="E-mail" type="email" required>

                    <input name="password" type="password" placeholder="Senha" required>

                    <a href="recuperar_senha.php">Esqueceu sua senha?</a>

                    <button type="submit">Entrar</button>

                </form>

            </div>

            <div class="panel register flex justify-content-center">
                <p>Não tem uma conta?</p>
                <a href="cadastro.php">Cadastre-se</a>
            </div>

        </section>

    </main>

</body>
</html>
