<?php

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$tarefas = $_SESSION['tarefas'] ?? [];

$tarefasDoUsuario = array_filter($tarefas, function($tarefa) {
    return $tarefa['responsavel'] === $_SESSION['usuario']['email'];
});
?>

<div class="main-content">
  <h1>Minhas Tarefas</h1>
  
</div>

<?php if (empty($tarefasDoUsuario)): ?>
    <p>Você ainda não tem tarefas atribuídas.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Responsável</th>
                <th>Prazo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tarefasDoUsuario as $tarefa): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tarefa['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($tarefa['descricao']); ?></td>
                    <td><?php echo htmlspecialchars($tarefa['responsavel']); ?></td>
                    <td><?php echo htmlspecialchars($tarefa['prazo']); ?></td>
                    <td><?php echo htmlspecialchars($tarefa['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
