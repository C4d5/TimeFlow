<?php
session_start();
$page = $_GET["page"] ?? "home";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeFlow</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    require_once "./components/header.php";
    
    
    require_once match($page) {
        "login" => "./pages/login.php",
        "home" => "./pages/home.php",
        "criar" => "./pages/criar.php",
        "tarefas" => "./pages/tarefas.php",
        "detalhes" => "./pages/detalhes.php",
        "registro" => "./pages/registro.php",
        "editar" => "./pages/editar.php",
        "logout" => "./pages/logout.php",
        default => "./pages/404.php",
    };
   
    include_once "./components/footer.php";
?>
</body>
</html>
