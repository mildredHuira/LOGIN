<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';

// Si el usuario ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$database = new Database();
$pdo = $database->getConnection();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombres = cleanInput($_POST['nombres']);
    $apellidos = cleanInput($_POST['apellidos']);
    $cedula = cleanInput($_POST['cedula']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validaciones
    if (empty($nombres)) {
        $errors[] = "Los nombres son requeridos";
    }
    
    if (empty($apellidos)) {
        $errors[] = "Los apellidos son requeridos";
    }
    
    if (empty($cedula)) {
        $errors[] = "La cédula es requerida";
    } elseif (cedulaExists($cedula, $pdo)) {
        $errors[] = "Esta cédula ya está registrada";
    }
    
    if (empty($email)) {
        $errors[] = "El email es requerido";
    } elseif (!validateEmail($email)) {
        $errors[] = "Formato de email inválido";
    } elseif (emailExists($email, $pdo)) {
        $errors[] = "Este email ya está registrado";
    }
    
    if (empty($password)) {
        $errors[] = "La contraseña es requerida";
    } elseif (strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    // Si no hay errores, registrar usuario
    if (empty($errors)) {
        if (registerUser($nombres, $apellidos, $cedula, $email, $password, $pdo)) {
            $success = "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            $errors[] = "Error al registrar usuario. Intenta nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Autenticación</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Crear Cuenta</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombres">Nombres:</label>
                <input type="text" id="nombres" name="nombres" value="<?php echo isset($_POST['nombres']) ? htmlspecialchars($_POST['nombres']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="cedula">Cédula:</label>
                <input type="text" id="cedula" name="cedula" value="<?php echo isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Registrarse</button>
        </form>
        
        <div class="text-center" style="margin-top: 20px;">
            <a href="login.php" class="link">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>