<?php
// Función para validar email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para limpiar datos de entrada
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para verificar si el email ya existe
function emailExists($email, $pdo) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount() > 0;
}

// Función para verificar si la cédula ya existe
function cedulaExists($cedula, $pdo) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE cedula = ?");
    $stmt->execute([$cedula]);
    return $stmt->rowCount() > 0;
}

// Función para registrar usuario
function registerUser($nombres, $apellidos, $cedula, $email, $password, $pdo) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (nombres, apellidos, cedula, email, password_hash) VALUES (?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([$nombres, $apellidos, $cedula, $email, $passwordHash]);
        return true;
    } catch(Exception $e) {
        return false;
    }
}

// Función para autenticar usuario
function authenticateUser($email, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT id, nombres, apellidos, email, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password_hash'])) {
            return $user;
        }
    }
    return false;
}

// Función para verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Función para redirigir
function redirect($url) {
    header("Location: $url");
    exit();
}
?>