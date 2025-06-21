<?php
session_start();

// Función para iniciar sesión del usuario
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_nombres'] = $user['nombres'];
    $_SESSION['user_apellidos'] = $user['apellidos'];
    $_SESSION['user_full_name'] = $user['nombres'] . ' ' . $user['apellidos'];
}

// Función para cerrar sesión
function logoutUser() {
    session_unset();
    session_destroy();
}

// Función para proteger páginas
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}
?>