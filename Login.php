<?php
// login.php
// Login simple para el proyecto Veterinaria

session_start();

// Conexión a base de datos (Alterada)
$host = 'localhost';
$db   = 'sttinternacional_Veterinaria1';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Error al conectar con la base de datos: ' . $e->getMessage());
}

$error = null;

// Procesar formulario si se envía vía POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['contra'] ?? '');

    if ($usuario === '' || $password === '') {
        $error = "Por favor complete ambos campos.";
    } else {
        // Buscar usuario en base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $userData = $stmt->fetch();

        if ($userData) {
            // Aquí asumo contraseña en texto plano, compara directamente
            if ($password === $userData['contra']) {
                // Usuario autenticado, crear sesión
                $_SESSION['usuario'] = $usuario;
                header('Location: Index.php'); // Redirigir tras login exitoso
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Login - Veterinaria</title>
<style>
    /* Diseño simple y limpio para el login */
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #197a36, #145a32);
        height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-container {
        background: white;
        padding: 2rem 3rem;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        width: 320px;
        text-align: center;
    }
    h2 {
        margin-bottom: 1.5rem;
        color: #197a36;
    }
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 0.6rem;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }
    button {
        width: 100%;
        padding: 0.7rem;
        background-color: #197a36;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #197a36;
    }
    .error {
        background-color: #f8d7da;
        color: #842029;
        padding: 0.7rem;
        border-radius: 5px;
        margin-bottom: 1rem;
    }
</style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
        <input type="text" name="usuario" placeholder="Usuario" required autofocus>
        <input type="password" name="contra" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>

