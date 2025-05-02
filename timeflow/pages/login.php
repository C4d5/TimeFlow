<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$erro = '';


function validateInput($input) {
    return !empty($input);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    
    if (validateInput($email) && validateInput($senha)) {
        
        if (isset($_SESSION['usuarios'])) {
            
            $email = strtolower(trim($email));

            
            if (array_key_exists($email, $_SESSION['usuarios'])) {
                $usuario = $_SESSION['usuarios'][$email];

                
                if (password_verify($senha, $usuario['senha'])) {
                    $_SESSION['logado'] = true;
                    $_SESSION['usuario'] = $usuario;

                   
                    header('Location: /timeflow/index.php?page=home');
                    exit;
                } else {
                    $erro = 'E-mail ou senha incorretos!';
                }
            } else {
                $erro = 'E-mail não registrado!';
            }
        } else {
            $erro = 'Erro na sessão, tente novamente mais tarde!';
        }
    } else {
        $erro = 'Preencha todos os campos corretamente!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - TimeFlow</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="login-container">
        <h1>Login</h1>

        <?php if ($erro): ?>
            <p class="error"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <form method="POST" action="" class="login-form">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit">Entrar</button>
        </form>

        <p>Não tem uma conta? <a href="index.php?page=registro">Cadastre-se aqui</a></p>
    </main>
</body>
</html>
