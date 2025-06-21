<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

// Cerrar sesión
logoutUser();

// Redirigir al login con mensaje de éxito
redirect('login.php?logout=1');
?>