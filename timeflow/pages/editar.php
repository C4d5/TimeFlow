<?php
require_once __DIR__ . '/../helpers/funcoes.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php?page=tarefas');
    exit;
}

$id = $_GET['id'];
$tarefa = null;


foreach ($_SESSION['tarefas'] as &$t) {
    if ($t['id'] == $id) {
        $tarefa = &$t;
        break;
    }
}

if (!$tarefa) {
    header('Location: index.php?page=tarefas');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $responsavel = $_POST['responsavel'];
    $prazo = $_POST['prazo'];

    if (validateInput($titulo) && validateInput($descricao) && validateInput($responsavel)) {
      
        $tarefa['historico'][] = [
            'data' => date('Y-m-d H:i:s'),
            'descricao' => 'Tarefa editada por ' . $_SESSION['usuario']['email'],
            'autor' => $_SESSION['usuario']['email']
        ];

        
        $tarefa['titulo'] = $titulo;
        $tarefa['descricao'] = $descricao;
        $tarefa['responsavel'] = $responsavel;
        $tarefa['prazo'] = $prazo;

        header('Location: index.php?page=detalhes&id=' . $id);
        exit;
    } else {
        $erro = "Todos os campos devem ser preenchidos corretamente.";
    }
}
?>

<h1>Editar Tarefa</h1>

<?php if (isset($erro)): ?>
    <p class="error"><?php echo $erro; ?></p>
<?php endif; ?>

<form method="post" action="?page=editar&id=<?php echo urlencode($id); ?>">
    <label for="nome">Nome da tarefa:</label>
    <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($tarefa['titulo']); ?>" required><br><br>

    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea><br><br>

    <label for="responsavel">Responsável:</label>
    <input type="text" name="responsavel" id="responsavel" value="<?php echo htmlspecialchars($tarefa['responsavel']); ?>" required><br><br>

    <label for="prazo">Prazo Final:</label>
    <input type="date" name="prazo" id="prazo" value="<?php echo htmlspecialchars($tarefa['prazo']); ?>" required><br><br>

    <button type="submit">Salvar Alterações</button>
</form>

<p><a href="index.php?page=detalhes&id=<?php echo urlencode($id); ?>">Cancelar e voltar</a></p>
