<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//Logout.php

session_start();
session_destroy();
header('Location: login.php'); // Redirige al login
exit;
?>