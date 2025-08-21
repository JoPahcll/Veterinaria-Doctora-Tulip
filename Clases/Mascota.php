<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//Clase Mascota.php

class Mascota {
    private ?int $id;
    private string $nombre;
    private string $especie;
    private string $raza;
    private int $edad;
    private string $sexo;
    public array $dueños = [];


    public function __construct(?int $id, string $nombre, string $especie, string $raza, int $edad, string $sexo) {
        $this->id = $id;
        $this->setNombre($nombre);
        $this->setEspecie($especie);
        $this->setRaza($raza);
        $this->setEdad($edad);
        $this->setSexo($sexo);
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getEspecie(): string { return $this->especie; }
    public function getRaza(): string { return $this->raza; }
    public function getEdad(): int { return $this->edad; }
    public function getSexo(): string { return $this->sexo; }

    // Setters
    public function setNombre(string $nombre): void {
        $nombre = trim($nombre);
        if ($nombre === '') {
            throw new InvalidArgumentException("El nombre no puede estar vacío.");
        }
        $this->nombre = $nombre;
    }

    public function setEspecie(string $especie): void {
        $especie = trim($especie);
        if ($especie === '') {
            throw new InvalidArgumentException("La especie no puede estar vacía.");
        }
        $this->especie = $especie;
    }

    public function setRaza(string $raza): void {
        $raza = trim($raza);
        if ($raza === '') {
            throw new InvalidArgumentException("La raza no puede estar vacía.");
        }
        $this->raza = $raza;
    }

    public function setEdad(int $edad): void {
        if ($edad < 0) {
            throw new InvalidArgumentException("La edad no puede ser negativa.");
        }
        $this->edad = $edad;
    }

    public function setSexo(string $sexo): void {
        $sexo = ucfirst(strtolower(trim($sexo)));
        if (!in_array($sexo, ['Macho', 'Hembra'])) {
            throw new InvalidArgumentException("El sexo debe ser 'Macho' o 'Hembra'.");
        }
        $this->sexo = $sexo;
    }

    //Guardar nueva mascota (si id es null)
    public function guardar(PDO $pdo, array $array_clientes): bool {
        if ($this->id !== null) {
            throw new LogicException("La mascota ya tiene un ID. Use actualizar() para modificarla.");
        }

        //Insertar en tabla mascotas
        $sql = "INSERT INTO mascotas (nombre, especie, raza, edad, sexo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $exito = $stmt->execute([
            $this->getNombre(),
            $this->getEspecie(),
            $this->getRaza(),
            $this->getEdad(),
            $this->getSexo()
        ]);

        if ($exito) {
            $this->id = (int)$pdo->lastInsertId();

            //Solo insertar relaciones si hay clientes asignados
            if (!empty($array_clientes)) {
                $stmtRelacion = $pdo->prepare("INSERT INTO mascotas_clientes (id_mascota, id_cliente) VALUES (?, ?)");
                foreach ($array_clientes as $id_cliente) {
                    $stmtRelacion->execute([$this->id, $id_cliente]);
                }
            }
        }

        return $exito;
    }

    //Actualizar mascota existente
        public static function actualizar(PDO $pdo, int $id, string $nombre, string $especie, string $raza, int $edad, string $sexo, $id_clientes = []): bool {
        // Si llega como string (por ejemplo "1,3"), convertir a array
        if (is_string($id_clientes)) {
            $id_clientes = $id_clientes !== '' ? explode(',', $id_clientes) : [];
        }

        // Si no hay clientes seleccionados, asignar cliente dummy por defecto (id_cliente = 1)
        if (empty($id_clientes)) {
            $id_clientes = [1];
        }

        // Actualiza datos básicos de la mascota
        $sql = "UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, edad = ?, sexo = ? WHERE id_mascota = ?";
        $stmt = $pdo->prepare($sql);
        $res = $stmt->execute([$nombre, $especie, $raza, $edad, $sexo, $id]);

        if ($res) {
            // Eliminar relaciones antiguas
            $stmtDel = $pdo->prepare("DELETE FROM mascotas_clientes WHERE id_mascota = ?");
            $stmtDel->execute([$id]);

            // Insertar nuevas relaciones
            $stmtIns = $pdo->prepare("INSERT INTO mascotas_clientes (id_mascota, id_cliente) VALUES (?, ?)");
            foreach ($id_clientes as $id_cliente) {
                $stmtIns->execute([$id, $id_cliente]);
            }
        }

        return $res;
    }



    // Eliminar mascota
    public function eliminar(PDO $pdo): bool {
        if ($this->id === null) {
            throw new LogicException("La mascota no tiene un ID para eliminar.");
        }
        $stmt = $pdo->prepare("DELETE FROM mascotas WHERE id_mascota = ?");
        $exito = $stmt->execute([$this->id]);
        if ($exito) {
            $this->id = null; // Marca como eliminada o no persistente
        }
        return $exito;
    }

    // Obtener todas las mascotas (retorna array de objetos)
    public static function obtenerTodas(PDO $pdo): array {
        $sql = "SELECT * FROM mascotas";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mascotas = [];
        foreach ($filas as $fila) {
            $mascotas[] = new self(
                (int)$fila['id_mascota'],
                $fila['nombre'],
                $fila['especie'],
                $fila['raza'],
                (int)$fila['edad'],
                $fila['sexo']
            );
        }
        return $mascotas;
    }

    // Obtener mascota por id (retorna objeto o null)
    public static function obtenerPorId(PDO $pdo, int $id): ?self {
        $sql = "SELECT * FROM mascotas WHERE id_mascota = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$fila) {
            return null;
        }
        return new self(
            (int)$fila['id_mascota'],
            $fila['nombre'],
            $fila['especie'],
            $fila['raza'],
            (int)$fila['edad'],
            $fila['sexo']
        );
    }

    public static function obtenerConDueños(PDO $pdo, string $filtro = '', string $valor = ''): array {
        $valor = trim($valor);
        $filtro = trim($filtro);

        // Base de la consulta con LEFT JOIN para dueños
        $sql = "
            SELECT 
                m.id_mascota, m.nombre, m.especie, m.raza, m.edad, m.sexo,
                c.id_cliente, c.nombre AS nombre_cliente, c.apellido AS apellido_cliente
            FROM mascotas m
            LEFT JOIN mascotas_clientes mc ON m.id_mascota = mc.id_mascota
            LEFT JOIN clientes c ON mc.id_cliente = c.id_cliente
        ";

        $params = [];
        $whereClauses = [];

        if ($filtro !== '' && $valor !== '') {
            if (in_array($filtro, ['id_mascota', 'nombre', 'especie', 'raza'])) {
                // Búsqueda en tabla mascotas
                $whereClauses[] = "m.$filtro LIKE :valor";
                $params['valor'] = "%$valor%";
            } elseif ($filtro === 'dueños') {
                //Busca los clientes por sus atributos
                $whereClauses[] = "CONCAT_WS(' ', c.nombre, c.apellido) LIKE :valor";
                $params['valor'] = "%$valor%";
            }
        }

        if (count($whereClauses) > 0) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $sql .= " ORDER BY m.id_mascota";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar dueños por mascota
        $mascotas = [];
        foreach ($filas as $fila) {
            $id = $fila['id_mascota'];
            if (!isset($mascotas[$id])) {
                $mascotas[$id] = [
                    'id_mascota' => $fila['id_mascota'],
                    'nombre' => $fila['nombre'],
                    'especie' => $fila['especie'],
                    'raza' => $fila['raza'],
                    'edad' => $fila['edad'],
                    'sexo' => $fila['sexo'],
                    'dueños' => []
                ];
            }
            if ($fila['id_cliente']) {
                $mascotas[$id]['dueños'][] = [
                    'id_cliente' => $fila['id_cliente'],
                    'nombre_completo' => $fila['nombre_cliente'] . ' ' . $fila['apellido_cliente']
                ];
            }
        }

        // Convertir array asociativo a indexado
        return array_values($mascotas);
    }

    //Buscar mascotas con filtro
    public static function buscarConFiltro(PDO $pdo, string $filtro, string $valor): array {
        $mapaColumnas = [
            'id_mascota' => 'id_mascota',
            'nombre' => 'nombre',
            'especie' => 'especie',
            'raza' => 'raza'
        ];

        if (!array_key_exists($filtro, $mapaColumnas) || empty($valor)) {
            return self::obtenerTodas($pdo);
        }

        $columna = $mapaColumnas[$filtro];
        $sql = "SELECT * FROM mascotas WHERE $columna LIKE :valor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['valor' => "%$valor%"]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Carga las mascota
        $mascotas = [];
        foreach ($filas as $fila) {
            $mascotas[] = new self(
                (int)$fila['id_mascota'],
                $fila['nombre'],
                $fila['especie'],
                $fila['raza'],
                (int)$fila['edad'],
                $fila['sexo']
            );
        }
        return $mascotas;
    }

    public static function obtenerPorIdConDuenos(PDO $pdo, int $id): ?self {
        //Obtener datos de mascota
        $sql = "SELECT * FROM mascotas WHERE id_mascota = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        //Crear objeto Mascota
        $mascota = new self(
            (int)$fila['id_mascota'],
            $fila['nombre'],
            $fila['especie'],
            $fila['raza'],
            (int)$fila['edad'],
            $fila['sexo']
        );

        //Obtener dueños
        $sql2 = "SELECT c.id_cliente, c.nombre, c.apellido FROM clientes c
                INNER JOIN mascotas_clientes mc ON c.id_cliente = mc.id_cliente
                WHERE mc.id_mascota = ?";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$id]);
        $dueñosFilas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return $mascota;
    }

}
?>
