<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//registrar_mascota.php

// Conexión PDO
require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Visita.php';

$msg = '';

//Datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $especie = trim($_POST["especie"] ?? '');
    $raza = trim($_POST["raza"] ?? '');
    $edad = intval($_POST["edad"] ?? 0);
    $sexo = trim($_POST["sexo"] ?? '');
    $id_clientes = $_POST["id_clientes"] ?? [];

    if ($nombre == '' || $especie == '' || $raza == '' || $edad <= 0 || !in_array($sexo, ['Macho', 'Hembra']) || !is_array($id_clientes) || empty($id_clientes)) {
        $msg = "Por favor complete todos los campos y seleccione al menos un dueño.";
    } else {
        require_once __DIR__ . '/../../clases/Mascota.php';
        $mascota = new Mascota(null, $nombre, $especie, $raza, $edad, $sexo);
        $exito = $mascota->guardar($pdo, $id_clientes);

        if ($exito) {
            header("Location: ../Listar/listar_mascotas.php?mensaje=registrado");
            exit;
        } else {
            $msg = "Error al registrar la mascota.";
        }
    }
}

// Obtener clientes para selección
$stmtClientes = $pdo->query("SELECT id_cliente, nombre, apellido FROM clientes ORDER BY nombre");
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Mascota</title>
    <link rel="stylesheet" href="../../styles.css">
    <style>
        /* Tus estilos para buscador y lista de clientes */
        select[multiple] {
            height: 120px;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Veterinaria Doc. Tulip</h1>
    </header>

    <main>
        <div class="form-registrar-container">
            <h1>Registrar Mascota</h1>

            <?php if ($msg): ?>
                <div class="error-message"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <!--Formulario-->
            <form method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="especie">Especie:</label>
                <input type="text" id="especie" name="especie" required>

                <label for="raza">Raza:</label>
                <input type="text" id="raza" name="raza" required>

                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" min="0" required>

                <label for="sexo">Sexo:</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Seleccione...</option>
                    <option value="Macho">Macho</option>
                    <option value="Hembra">Hembra</option>
                </select>

                <label for="id_clientes">Seleccione uno o más dueños (Use Ctrl o Shift):</label>
                <select name="id_clientes[]" id="id_clientes" multiple required>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id_cliente'] ?>">
                            <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const select = document.getElementById("id_clientes");

                        select.addEventListener("change", () => {
                            const selectedValues = Array.from(select.selectedOptions).map(opt => opt.value);

                            if (selectedValues.includes("1")) {
                                // Si se selecciona el cliente con id=1, deseleccionamos los demás
                                Array.from(select.options).forEach(opt => {
                                    if (opt.value !== "1") {
                                        opt.selected = false;
                                    }
                                });
                            } else {
                                // Si se selecciona cualquier otro, deseleccionamos "Sin propietario"
                                const sinPropietario = select.querySelector("option[value='1']");
                                if (sinPropietario) {
                                    sinPropietario.selected = false;
                                }
                            }
                        });
                    });
                </script>

                <button type="submit">Registrar</button>
            </form>

            <a href="../Listar/listar_mascotas.php" class="cancelar-btn">Cancelar</a>
        </div>
    </main>

    <footer>
        <p>Desarrollado por: José Pablo Chinchilla 12-4 Desarrollo de Software</p>
    </footer>
</body>
</html>


