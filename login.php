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
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    
    // Validaciones
    if (empty($email)) {
        $errors[] = "El email es requerido";
    } elseif (!validateEmail($email)) {
        $errors[] = "Formato de email inválido";
    }
    
    if (empty($password)) {
        $errors[] = "La contraseña es requerida";
    }
    
    // Si no hay errores, autenticar usuario
    if (empty($errors)) {
        $user = authenticateUser($email, $password, $pdo);
        
        if ($user) {
            loginUser($user);
            redirect('dashboard.php');
        } else {
            // Verificar si el email existe
            if (emailExists($email, $pdo)) {
                $errors[] = "Contraseña incorrecta";
            } else {
                $errors[] = "Email no registrado";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Autenticación</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['registered']) && $_GET['registered'] == '1'): ?>
            <div class="success">
                <p>Registro exitoso. Ahora puedes iniciar sesión.</p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['logout']) && $_GET['logout'] == '1'): ?>
            <div class="success">
                <p>Has cerrado sesión exitosamente.</p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Ingresar</button>
        </form>
        
        <div class="text-center" style="margin-top: 20px;">
            <a href="register.php" class="link">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>
</body>
</html>