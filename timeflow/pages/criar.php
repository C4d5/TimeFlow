<?php

require_once __DIR__ . '/../helpers/funcoes.php';


if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$erro = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $responsavel = $_POST['responsavel'] ?? '';
    $prazo = $_POST['prazo'] ?? '';

   
    if (
        validateInput($nome) &&
        validateInput($descricao) &&
        validateInput($responsavel) &&
        validateInput($prazo)
    ) {
        
        $novaTarefa = [
            'id' => uniqid(),
            'titulo' => $nome,
            'descricao' => $descricao,
            'responsavel' => $responsavel,
            'prazo' => $prazo,
            'status' => 'pendente',
            'criado_em' => date('Y-m-d H:i:s'),
            'historico' => [
                [
                    'mensagem' => 'Tarefa criada',
                    'autor' => $_SESSION['usuario']['nome'] ?? 'Sistema',
                    'data' => date('Y-m-d H:i:s')
                ]
            ]
        ];

        $_SESSION['tarefas'][] = $novaTarefa;

       
        header('Location: index.php?page=tarefas');
        exit;
    } else {
        $erro = 'Por favor, preencha todos os campos corretamente.';
    }
}


$usuarios = $_SESSION['usuarios'] ?? [];

?>



<?php if ($erro): ?>
    <p class="error"><?php echo $erro; ?></p>
<?php endif; ?>

<div class="form-container">
<h1>Criar Tarefa</h1>
    <form method="post" action="?page=criar" class="form">
        <label for="nome">Nome da tarefa:</label>
        <input type="text" name="nome" id="nome" required><br><br>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" required></textarea><br><br>

        <label for="responsavel">Responsável:</label>
        <select name="responsavel" id="responsavel" required>
            <option value="">Selecione</option>
            <?php foreach ($usuarios as $email => $usuario): ?>
                <option value="<?php echo htmlspecialchars($email); ?>">
                    <?php echo htmlspecialchars($usuario['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="prazo">Prazo Final:</label>
        <input type="date" name="prazo" id="prazo" required><br><br>

        <button type="submit">Criar Tarefa</button>
    </form>
</div>
