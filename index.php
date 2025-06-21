<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

// Si el usuario está logueado, redirigir al dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
} else {
    redirect('login.php');
}
?>