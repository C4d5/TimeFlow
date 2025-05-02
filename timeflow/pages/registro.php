<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../helpers/funcoes.php';

$erro = '';


if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    
    if (validateInput($nome) && validateInput($email) && validateInput($senha) && validateInput($confirmar_senha)) {
        if ($senha === $confirmar_senha) {
            
            if (!array_key_exists($email, $_SESSION['usuarios'])) {
               
                $_SESSION['usuarios'][$email] = [
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => password_hash($senha, PASSWORD_DEFAULT),
                ];

               
                header('Location: /timeflow/index.php?page=login');
                exit;
            } else {
                $erro = 'Este e-mail já está cadastrado!';
            }
        } else {
            $erro = 'As senhas não coincidem!';
        }
    } else {
        $erro = 'Preencha todos os campos corretamente!';
    }
}
?>

<body>
    <main class="form-container">
        <h1>Cadastro de Usuário</h1>

        <?php if ($erro): ?>
            <p class="error"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <form method="POST" class="form">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <label for="confirmar_senha">Confirmar Senha:</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" required>

            <button type="submit">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="index.php?page=login">Faça login aqui</a></p>
    </main>
</body>
</html>
