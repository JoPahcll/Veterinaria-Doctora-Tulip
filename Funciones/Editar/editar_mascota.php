<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//editar_mascota.php

require_once __DIR__ . '/../../Conexion.php';
require_once __DIR__ . '/../../clases/Mascota.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de mascota no proporcionado."); //Error al obtener el id
}

$id = (int) $_GET['id'];

// Obtener objeto Mascota con dueños
$mascota = Mascota::obtenerPorIdConDuenos($pdo, $id);

if (!$mascota) {
    die("Mascota no encontrada.");
}

// Obtener todos los clientes para selección
$stmtClientes = $pdo->query("SELECT id_cliente, nombre, apellido FROM clientes ORDER BY nombre");
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

// Array de IDs dueños actuales para marcar seleccionados
$idsDuenosActuales = [];
if (!empty($mascota->dueños) && is_array($mascota->dueños)) {
    $idsDuenosActuales = array_column($mascota->dueños, 'id_cliente');
}

$error = null;

//Busca constructores
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $especie = trim($_POST['especie'] ?? '');
    $raza = trim($_POST['raza'] ?? '');
    $edad = intval($_POST['edad'] ?? 0);
    $sexo = $_POST['sexo'] ?? '';
    $id_clientes = $_POST['id_clientes'] ?? [];

    //Detector de errores
    if ($nombre === '' || $especie === '' || $raza === '' || $edad < 0 || !in_array($sexo, ['Macho','Hembra'])) {
        $error = "Por favor complete todos los campos obligatorios.";
    } else {
        // $id_clientes puede estar vacío; actualizar() se encargará de asignar cliente dummy si es necesario
        $exito = Mascota::actualizar($pdo, $id, $nombre, $especie, $raza, $edad, $sexo, $_POST['id_clientes'] ?? []);
        
        if ($exito) {
            header("Location: ../Listar/listar_mascotas.php?mensaje=editado");
            exit;
        } else {
            $error = "Error al actualizar la mascota.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Editar Mascota</title>
<link rel="stylesheet" href="../../styles.css" />
<style>
    .clientes-lista {
        border: 1px solid #ccc;
        padding: 8px;
        max-height: 200px;
        overflow-y: auto;
        width: 280px;
        border-radius: 5px;
        background: #f9f9f9;
        margin-bottom: 15px;
    }
    .cliente-item {
        padding: 8px;
        cursor: pointer;
        border-radius: 4px;
        margin-bottom: 4px;
        user-select: none;
        transition: background-color 0.2s;
    }
    .cliente-item:hover {
        background-color: #ddd;
    }
    .cliente-item.selected {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }
    .error-message {
        color: red;
        margin-bottom: 15px;
    }
</style>
</head>
<body>
    <header><h1>Editar Mascota</h1></header>

    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <main>
        <!--Formulario-->
        <form method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($mascota->getNombre()) ?>" required><br>

            <label>Especie:</label>
            <input type="text" name="especie" value="<?= htmlspecialchars($mascota->getEspecie()) ?>" required><br>

            <label>Raza:</label>
            <input type="text" name="raza" value="<?= htmlspecialchars($mascota->getRaza()) ?>" required><br>

            <label>Edad:</label>
            <input type="number" name="edad" value="<?= htmlspecialchars($mascota->getEdad()) ?>" min="0" required><br>

            <label>Sexo:</label>
            <select name="sexo" required>
                <option value="Macho" <?= $mascota->getSexo() === 'Macho' ? 'selected' : '' ?>>Macho</option>
                <option value="Hembra" <?= $mascota->getSexo() === 'Hembra' ? 'selected' : '' ?>>Hembra</option>
            </select><br><br>

            <!--Editar el dueño de la mascota-->
            <label>Dueños (Clientes) — obligatorio:</label><br>
            <div class="clientes-lista" id="lista-clientes">
                <?php foreach ($clientes as $cliente):
                    $selected = in_array($cliente['id_cliente'], $idsDuenosActuales);
                ?>
                <div class="cliente-item <?= $selected ? 'selected' : '' ?>" data-id="<?= $cliente['id_cliente'] ?>">
                    <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- contenedor de inputs ocultos -->
            <div id="inputs-clientes"></div>
            <input type="hidden" name="id_clientes" id="id_clientes_input" value="<?= implode(',', $idsDuenosActuales) ?>">
            <button type="submit">Guardar cambios</button>
            <a href="../Listar/listar_mascotas.php"><button type="button">Cancelar</button></a>
        </form>
    </main>

    <script> //Script para la funcion para actualizar
        const clientesLista = document.getElementById('lista-clientes');
        const inputClientes = document.getElementById('id_clientes_input');

        function actualizarInput() {
            const seleccionados = [...clientesLista.querySelectorAll('.cliente-item.selected')]
                .map(div => div.dataset.id);
            inputClientes.value = seleccionados.join(',');
        }

        clientesLista.addEventListener('click', e => {
            if (e.target.classList.contains('cliente-item')) {
                e.target.classList.toggle('selected');
                actualizarInput();
            }
        });

        // Inicializa input con los seleccionados al cargar
        actualizarInput();
    </script>

</body>
</html>
