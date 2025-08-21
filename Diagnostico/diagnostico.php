<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//diagnostico.php

require_once __DIR__ . '/../Conexion.php';
require_once __DIR__ . '/../clases/Mascota.php';

// Cargar mascotas con al menos un dueño para el select
$mascotas = $pdo->query("
    SELECT m.id_mascota, m.nombre,
        (SELECT CONCAT(c.nombre, ' ', c.apellido)
         FROM mascotas_clientes mc2
         JOIN clientes c ON mc2.id_cliente = c.id_cliente
         WHERE mc2.id_mascota = m.id_mascota
         LIMIT 1) AS nombre_cliente
    FROM mascotas m
    ORDER BY m.nombre
")->fetchAll(PDO::FETCH_ASSOC);

//Variables de error y exito
$error = null;
$exito = null;

//Captura de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_visita = $_POST['id_visita'] ?? null;
    $peso = $_POST['peso'] ?? null;
    $altura = $_POST['altura'] ?? null;
    $observaciones = $_POST['observaciones'] ?? '';
    $tratamiento = $_POST['tratamiento'] ?? '';
    $costo_total = $_POST['costo_total'] ?? null;

    // Validar campos obligatorios
    if (!$id_visita || $peso === '' || $altura === '' || $costo_total === '') {
        $error = "Debe completar todos los campos obligatorios.";
    } else {
        try {//Insertar diagnóstico
            $sql = "INSERT INTO diagnostico (id_visita, peso, altura, observaciones, tratamiento, costo_total)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $guardado = $stmt->execute([
                $id_visita,
                $peso,
                $altura,
                $observaciones,
                $tratamiento,
                $costo_total
            ]);

            if ($guardado) {
                $sqlUpdateEstado = "UPDATE visitas SET estado = 'Concluida' WHERE id_visita = :id_visita";
                $stmtUpdate = $pdo->prepare($sqlUpdateEstado);
                $stmtUpdate->execute(['id_visita' => $id_visita]);

                $exito = "Diagnóstico guardado correctamente.";
            } else {
                $error = "Error al guardar diagnóstico.";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                // Manejo de error por duplicación
                $error = "La visita ya fue cancelada.";
            } else {
                $error = "Error en la base de datos: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Registrar Diagnóstico</title>
<link rel="stylesheet" href="../Styles.css">
<style>
    .hidden { display:none; }
    .form-step { margin-bottom: 2rem; }
</style>
</head>
    <body>
        <header><h1>Registrar Diagnóstico</h1></header>

        <main>

            <?php if ($error): ?> <!--Mensaje de error por duplicación-->
                <div style="background-color:#ffcccc; padding:10px; border:1px solid red; color:red; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($exito): ?> <!--Mensaje por guardado exitoso-->
                <div style="background-color:#ccffcc; padding:10px; border:1px solid green; color:green; margin-bottom:15px;">
                    <?= htmlspecialchars($exito) ?>
                </div>
            <?php endif; ?>

            <form id="formDiagnostico" method="POST" novalidate>

                <!-- Paso 1: Seleccionar Mascota -->
                <div class="form-step" id="stepMascota">
                    <label for="id_mascota">Mascota:</label>
                    <select id="id_mascota" name="id_mascota" required>
                        <option value="">Seleccione una mascota</option>
                        <?php foreach ($mascotas as $m): ?>
                            <option value="<?= (int)$m['id_mascota'] ?>">
                                <?= htmlspecialchars($m['nombre'] . ' (' . $m['nombre_cliente'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Paso 2: Seleccionar Visita -->
                <div class="form-step hidden" id="stepVisita">
                    <label for="id_visita">Visita:</label>
                    <select id="id_visita" name="id_visita" required>
                        <option value="">Seleccione una visita</option>
                        <!-- Se llena con AJAX -->
                    </select>
                </div>

                <!-- Paso 3: Mostrar Cliente -->
                <div class="form-step hidden" id="stepCliente">
                    <label>Cliente:</label>
                    <div id="datos_cliente" style="font-weight:bold; margin-bottom: 1rem;">(Seleccione visita primero)</div>
                </div>

                <!-- Paso 4: Datos diagnóstico -->
                <div class="form-step hidden" id="stepDiagnostico">
                    <label for="peso">Peso (kg):</label>
                    <input type="number" id="peso" name="peso" step="0.01" min="0" required />

                    <label for="altura">Altura (cm):</label>
                    <input type="number" id="altura" name="altura" step="0.01" min="0" required />

                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones"></textarea>

                    <label for="tratamiento">Tratamiento:</label>
                    <textarea id="tratamiento" name="tratamiento"></textarea>
                </div>

                <!-- Paso 5: Costo -->
                <div class="form-step hidden" id="stepCosto">
                    <label for="costo_total">Costo total (₡):</label>
                    <input type="number" id="costo_total" name="costo_total" step="0.01" min="0" value="0" required />
                </div>

                <button type="submit" id="btnSubmit" class="hidden">Guardar Diagnóstico</button>
            </form>
        </main>

        <script>
        // Paso a paso con JavaScript simple

       //Constantes
        const stepMascota = document.getElementById('stepMascota');
        const stepVisita = document.getElementById('stepVisita');
        const stepCliente = document.getElementById('stepCliente');
        const stepDiagnostico = document.getElementById('stepDiagnostico');
        const stepCosto = document.getElementById('stepCosto');
        const btnSubmit = document.getElementById('btnSubmit');

        const selectMascota = document.getElementById('id_mascota');
        const selectVisita = document.getElementById('id_visita');
        const datosCliente = document.getElementById('datos_cliente');

        //Oculta el select visita
        function resetSteps(fromStep) {
            if(fromStep <= 2) {
                stepVisita.classList.add('hidden');
                selectVisita.innerHTML = '<option value="">Seleccione una visita</option>';
            }
            //Oculta el mensaje de seleccionar visita primero
            if(fromStep <= 3) {
                stepCliente.classList.add('hidden');
                datosCliente.textContent = '(Seleccione visita primero)';
            }
            //Oculta los campos del ultimo paso
            if(fromStep <= 4) {
                stepDiagnostico.classList.add('hidden');
                stepCosto.classList.add('hidden');
                btnSubmit.classList.add('hidden');
            }
        }

        //Seleccionar mascota
        selectMascota.addEventListener('change', () => {
            resetSteps(2);
            if (!selectMascota.value) return;

            //Obtiene las visitas asociadas a las mascotas
            fetch(`visitas_mascotas.php?id_mascota=${selectMascota.value}`)
                .then(res => res.json())
                .then(data => {
                    if(data.error) {
                        alert(data.error);
                        return;
                    }

                    //Obtener datos de la visita(Usando el id mascota)
                    selectVisita.innerHTML = '<option value="">Seleccione una visita</option>';
                    data.visitas.forEach(v => {
                        const option = document.createElement('option');
                        option.value = v.id_visita;
                        option.textContent = `${v.asunto} (${v.fecha_asignada})`;
                        selectVisita.appendChild(option);
                    });
                    stepVisita.classList.remove('hidden');
                })
                .catch(() => alert('Error al cargar visitas'));
        });

        selectVisita.addEventListener('change', () => {
            resetSteps(3);
            if (!selectVisita.value) return;

                // Obtener datos cliente desde la mascota en visita
                fetch(`datos_visita.php?id_visita=${selectVisita.value}`)
                .then(res => res.json())
                .then(data => {
                    if(data.error) {
                        alert(data.error);
                        return;
                    }
                    datosCliente.textContent = `${data.cliente.nombre} ${data.cliente.apellido} - Tel: ${data.cliente.telefono} - Correo: ${data.cliente.correo}`;
                    stepCliente.classList.remove('hidden');
                    stepDiagnostico.classList.remove('hidden');
                    stepCosto.classList.remove('hidden');
                    btnSubmit.classList.remove('hidden');
                })
                .catch(() => alert('Error al cargar datos del cliente'));
        });
        </script>

        <style> /*Styles*/
            .btn {
                background-color: #0ec05eff;
                color: white;
                border: none;
                padding: 8px 14px;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 600;
                transition: background-color 0.3s ease;
                text-decoration: none;
                display: inline-block;
                text-align: center;
                font-size: 14px;
                margin-top: 15px;
            }
        </style>

        <!-- Botones para navegar el sitio-->
        <a href="../Index.php" class="btn">Inicio</a>
        <a href="../Funciones/Listar/listar_visitas.php" class="btn">Visitas Agendadas</a>
        <a href="diagnostico.php" class="btn">Reiniciar Formulario</a>

        <footer><p>Desarrollado por: José Pablo Chinchilla</p></footer>
    </body>
</html>