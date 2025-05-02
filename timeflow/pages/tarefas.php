<?php
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: index.php?page=login');
    exit;
}


$tarefas = $_SESSION['tarefas'] ?? [];


$filtroResponsavel = $_GET['responsavel'] ?? '';
$filtroStatus = $_GET['status'] ?? '';
$filtroPrazo = $_GET['prazo'] ?? '';


$tarefasFiltradas = array_filter($tarefas, function($tarefa) use ($filtroResponsavel, $filtroStatus, $filtroPrazo) {
    if ($filtroResponsavel && $tarefa['responsavel'] !== $filtroResponsavel) return false;
    if ($filtroStatus && $tarefa['status'] !== $filtroStatus) return false;
    if ($filtroPrazo && $tarefa['prazo'] !== $filtroPrazo) return false;
    return true;
});


$usuarios = $_SESSION['usuarios'] ?? [];
$responsaveis = array_merge(
    array_keys($usuarios),
    array_map(function($t) { return $t['responsavel']; }, $tarefas)
);


$responsaveis = array_unique($responsaveis);
sort($responsaveis);


$statusList = ['pendente', 'em andamento', 'concluída'];
?>

<div class="main-content">
  <h1>Todas Tarefas</h1>
</div>

<div class="tarefas-container">

        <?php if (empty($tarefasFiltradas)): ?>
            <p>Não há tarefas cadastradas com os filtros aplicados.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Responsável</th>
                        <th>Prazo</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tarefasFiltradas as $tarefa): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tarefa['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($tarefa['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($tarefa['responsavel']); ?></td>
                            <td><?php echo htmlspecialchars($tarefa['prazo']); ?></td>
                            <td><?php echo htmlspecialchars($tarefa['status']); ?></td>
                            <td>
                                <a href="index.php?page=detalhes&id=<?php echo $tarefa['id']; ?>">Ver Detalhes</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    
    <div class="form-container">
        <form method="GET" action="index.php" class="form">
            <input type="hidden" name="page" value="tarefas">

            <label for="responsavel">Responsável:</label>
            <select name="responsavel" id="responsavel">
                <option value="">Todos</option>
                <?php foreach ($responsaveis as $resp): ?>
                    <option value="<?php echo htmlspecialchars($resp); ?>" <?php echo ($resp === $filtroResponsavel) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($resp); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="">Todos</option>
                <?php foreach ($statusList as $status): ?>
                    <option value="<?php echo $status; ?>" <?php echo ($status === $filtroStatus) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($status); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="prazo">Prazo:</label>
            <input type="date" name="prazo" id="prazo" value="<?php echo htmlspecialchars($filtroPrazo); ?>">

            <button type="submit">Filtrar</button>
        </form>
        <p><a href="index.php?page=criar">Criar nova tarefa</a></p>
    </div>

    
