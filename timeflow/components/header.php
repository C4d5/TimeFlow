<header>
    <img src="timeflow.png" alt="TimeFlow Logo" class="imagem">

    <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
        <nav>
            <a href="index.php?page=home">Minhas Tarefas</a>
            <a href="index.php?page=criar">Criar Tarefa</a>
            <a href="index.php?page=tarefas">Todas as Tarefas</a>

            <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?></span>

            <form action="index.php?page=logout" method="post" style="display:inline;">
                <button type="submit">Sair</button>
            </form>
        </nav>
    <?php endif; ?>
</header>
