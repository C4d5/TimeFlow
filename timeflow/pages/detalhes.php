<?php

$tarefa = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    foreach ($_SESSION['tarefas'] as $key => $t) {
        if ($t['id'] === $id) {
            $tarefa = $t;
            $tarefaKey = $key;
            break;
        }
    }
}

if (!$tarefa) {
    echo "<div class=\"form-container\"><p>Tarefa não encontrada.</p></div>";
    exit;
}

$erroComentario = '';
$sucessoComentario = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'])) {
    $comentario = trim($_POST['comentario']);
    if (!empty($comentario)) {
        $_SESSION['tarefas'][$tarefaKey]['historico'][] = [
            'autor' => $_SESSION['usuario']['nome'],
            'mensagem' => $comentario,
            'data' => date('Y-m-d H:i:s')
        ];
        $sucessoComentario = 'Comentário adicionado com sucesso!';
    } else {
        $erroComentario = 'Comentário não pode estar vazio!';
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_tarefa']) && isset($_POST['titulo'])) {
    $alteracoes = [];
    $campos = ['titulo', 'descricao', 'responsavel', 'prazo', 'status'];

    foreach ($campos as $campo) {
        $novoValor = $_POST[$campo] ?? $tarefa[$campo];
        if ($novoValor !== $tarefa[$campo]) {
            $alteracoes[] = [
                'campo' => ucfirst($campo),
                'de' => $tarefa[$campo],
                'para' => $novoValor
            ];
            $tarefa[$campo] = $novoValor;
        }
    }

    $_SESSION['tarefas'][$tarefaKey] = $tarefa;

    foreach ($alteracoes as $alt) {
        $_SESSION['tarefas'][$tarefaKey]['historico'][] = [
            'autor' => $_SESSION['usuario']['nome'],
            'mensagem' => "{$alt['campo']} alterado de '{$alt['de']}' para '{$alt['para']}'",
            'data' => date('Y-m-d H:i:s')
        ];
    }

    if (count($alteracoes) > 0) {
        $sucessoComentario = 'Tarefa atualizada com sucesso!';
    }
}
?>

<div class="form-container">
    <h1>Detalhes da Tarefa</h1>

    <p><strong>Nome:</strong> <?= htmlspecialchars($tarefa['titulo']) ?></p>
    <p><strong>Descrição:</strong> <?= htmlspecialchars($tarefa['descricao']) ?></p>
    <p><strong>Responsável:</strong> <?= htmlspecialchars($tarefa['responsavel']) ?></p>
    <p><strong>Prazo:</strong> <?= htmlspecialchars($tarefa['prazo']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($tarefa['status']) ?></p>

    <h3>Histórico:</h3>
    <ul>
        <?php foreach ($tarefa['historico'] as $evento): ?>
            <li>
                <?= htmlspecialchars($evento['autor']) ?> comentou:
                "<em><?= htmlspecialchars($evento['mensagem']) ?></em>"
                (<?= htmlspecialchars($evento['data']) ?>)
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Adicionar Comentário:</h3>
    <?php if ($erroComentario): ?>
        <p class="error"><?= $erroComentario ?></p>
    <?php endif; ?>
    <?php if ($sucessoComentario): ?>
        <p class="success"><?= $sucessoComentario ?></p>
    <?php endif; ?>
    <form method="POST" class="form">
        <textarea name="comentario" rows="4" placeholder="Adicione seu comentário aqui..."></textarea>
        <button type="submit">Adicionar Comentário</button>
    </form>

    <h3>Editar Tarefa:</h3>
    <form method="POST" class="form-inline">
        <button type="submit" name="editar_tarefa">Editar Descrição</button>
    </form>

    <?php if (isset($_POST['editar_tarefa']) && !isset($_POST['titulo'])): ?>
        <h3>Formulário de Edição de Tarefa:</h3>
        <form method="POST" class="form">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required><?= htmlspecialchars($tarefa['descricao']) ?></textarea>

            <label for="responsavel">Responsável:</label>
            <input type="text" name="responsavel" id="responsavel" value="<?= htmlspecialchars($tarefa['responsavel']) ?>" required>

            <label for="prazo">Prazo:</label>
            <input type="date" name="prazo" id="prazo" value="<?= htmlspecialchars($tarefa['prazo']) ?>" required>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Pendente" <?= $tarefa['status'] === 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="Em Andamento" <?= $tarefa['status'] === 'Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
                <option value="Concluída" <?= $tarefa['status'] === 'Concluída' ? 'selected' : '' ?>>Concluída</option>
            </select>

            <button type="submit" name="editar_tarefa">Salvar Alterações</button>
        </form>
    <?php endif; ?>

    <p class="criar-link"><a href="index.php?page=tarefas">Voltar para a lista de tarefas</a></p>
</div>
