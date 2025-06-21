<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

// Proteger la página - solo usuarios autenticados
requireLogin();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Autenticación</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo">Sistema </div>
            <div class="user-info">
                <span>Hola, <?php echo htmlspecialchars($_SESSION['user_full_name']); ?></span>
                <a href="logout.php" class="btn btn-danger" style="width: auto; padding: 8px 16px;">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="welcome-card">
            <h1>¡Bienvenido al Dashboard!</h1>
            <p>Hola, <?php echo htmlspecialchars($_SESSION['user_full_name']); ?>. Es un placer tenerte aquí.</p>
        </div>
        
</body>
</html>