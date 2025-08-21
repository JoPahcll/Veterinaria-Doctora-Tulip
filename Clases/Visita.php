<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//Clase Visitas.php

class Visita {

    private $id_visita;
    private $fecha_asignada;
    private $hora;
    private $asunto;
    private $id_mascota;
    private $fecha_asignacion;
    private $nombre_mascota;
    private $estado;

    //Constructores
    public function __construct(
        ?int $id_visita,
        string $fecha_asignada,
        string $hora,
        string $asunto,
        int $id_mascota,
        ?string $fecha_asignacion = null,
        ?string $nombre_mascota = null,
        ?string $estado = null 
    ) {
        $this->id_visita = $id_visita;
        $this->fecha_asignada = $fecha_asignada;
        $this->hora = $hora;
        $this->asunto = $asunto;
        $this->id_mascota = $id_mascota;
        $this->fecha_asignacion = $fecha_asignacion;
        $this->nombre_mascota = $nombre_mascota;
        $this->estado = $estado;
    }

    //Getters
    public function getIdVisita(): ?int { return $this->id_visita; }
    public function getFechaAsignada(): string { return $this->fecha_asignada; }
    public function getHora(): string { return $this->hora; }
    public function getAsunto(): string { return $this->asunto; }
    public function getIdMascota(): int { return $this->id_mascota; }
    public function getFechaAsignacion(): ?string { return $this->fecha_asignacion; }
    public function getNombreMascota(): ?string { return $this->nombre_mascota; }
    public function getEstado(): ?string {return $this->estado;}


    //Método obtenerTodas
    public static function obtenerTodas(PDO $pdo): array {
        $sql = "SELECT v.id_visita, v.fecha_asignada, v.hora, v.asunto, v.fecha_asignacion, v.estado,
                       m.id_mascota, m.nombre AS nombre_mascota
                FROM visitas v
                JOIN mascotas m ON v.id_mascota = m.id_mascota
                ORDER BY v.fecha_asignada DESC, v.hora DESC";
        $stmt = $pdo->query($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $visitas = [];
        foreach ($resultados as $fila) {
            $visitas[] = new self(
                isset($fila['id_visita']) ? (int)$fila['id_visita'] : null,
                $fila['fecha_asignada'],
                $fila['hora'],
                $fila['asunto'],
                isset($fila['id_mascota']) ? (int)$fila['id_mascota'] : 0,
                $fila['fecha_asignacion'] ?? null,
                $fila['nombre_mascota'] ?? null,
                $fila['estado'] ?? null
            );
        }
        return $visitas;
    }

    //Busca usando el filtro
    public static function buscarConFiltro(PDO $pdo, string $filtro, string $valor): array {
        $permitidos = [
            'id_visita' => 'v.id_visita',
            'fecha' => 'v.fecha_asignada',
            'asunto' => 'v.asunto',
            'nombre_mascota' => 'm.nombre',
            'estado' => 'v.estado'
        ];

        $sql = "SELECT v.id_visita, v.fecha_asignada, v.hora, v.asunto, v.fecha_asignacion, v.estado,
                    m.id_mascota, m.nombre AS nombre_mascota
                FROM visitas v
                JOIN mascotas m ON v.id_mascota = m.id_mascota";

        if (!empty($filtro) && !empty($valor) && isset($permitidos[$filtro])) {
            $campo = $permitidos[$filtro];

            if ($filtro === 'estado') {
                // Para estado, comparación exacta
                $sql .= " WHERE $campo = :valor";
            } else {
                // Para otros campos, búsqueda con LIKE
                $sql .= " WHERE $campo LIKE :valor";
                $valor = "%$valor%";
            }
        }

        //Ordena por fecha
        $sql .= " ORDER BY v.fecha_asignada DESC, v.hora DESC";

        $stmt = $pdo->prepare($sql);

        if (!empty($filtro) && !empty($valor) && isset($permitidos[$filtro])) {
            $stmt->bindValue(':valor', $valor, PDO::PARAM_STR);
        }

        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Carga una nueva visita
        $visitas = [];
        foreach ($resultados as $fila) {
            $visitas[] = new self(
                isset($fila['id_visita']) ? (int)$fila['id_visita'] : null,
                $fila['fecha_asignada'],
                $fila['hora'],
                $fila['asunto'],
                isset($fila['id_mascota']) ? (int)$fila['id_mascota'] : 0,
                $fila['fecha_asignacion'] ?? null,
                $fila['nombre_mascota'] ?? null,
                $fila['estado'] ?? null
            );
        }
        return $visitas;
    }

    //Método guardar
    public function guardar(PDO $pdo): bool {
        if ($this->id_visita !== null) {
            throw new LogicException("La visita ya tiene un ID. Use actualizar para modificarla.");
        }
        $sql = "INSERT INTO visitas (fecha_asignada, hora, asunto, id_mascota) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([
            $this->fecha_asignada,
            $this->hora,
            $this->asunto,
            $this->id_mascota
        ]);

        if ($resultado) {
            $this->id_visita = (int)$pdo->lastInsertId();
        }
        return $resultado;
    }

    // Método guardar
    public function actualizar(PDO $pdo): bool {
        if ($this->id_visita === null) {
            throw new LogicException("La visita no tiene ID para actualizar.");
        }

        $sql = "UPDATE visitas SET fecha_asignada = ?, hora = ?, asunto = ?, id_mascota = ? WHERE id_visita = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $this->getFechaAsignada(),
            $this->getHora(),
            $this->getAsunto(),
            $this->getIdMascota(),
            $this->getIdVisita()
        ]);
    }

    //Eliminar visita
    public function eliminar(PDO $pdo): bool {
        if ($this->id_visita === null) {
            throw new LogicException("La visita no tiene ID para eliminar.");
        }

        $stmt = $pdo->prepare("DELETE FROM visitas WHERE id_visita = ?");
        $resultado = $stmt->execute([$this->id_visita]);

        if ($resultado) {
            $this->id_visita = null;
        }
        return $resultado;
    }

    //Obtiene la visita de forma común, sin filtrar por datos
    public static function obtenerConDatosCompletos(PDO $pdo, int $id_visita): ?self {
        $sql = "SELECT v.id_visita, v.fecha_asignada, v.hora, v.asunto, v.fecha_asignacion, v.estado,
                    m.id_mascota, m.nombre AS nombre_mascota,
                    c.id_cliente, c.nombre AS nombre_cliente, c.apellido AS apellido_cliente,
                    d.id_diagnostico, d.diagnostico, d.tratamiento
                FROM visitas v
                JOIN mascotas m ON v.id_mascota = m.id_mascota
                JOIN mascotas_clientes mc ON m.id_mascota = mc.id_mascota
                JOIN clientes c ON mc.id_cliente = c.id_cliente
                LEFT JOIN diagnosticos d ON d.id_visita = v.id_visita
                WHERE v.id_visita = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_visita]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        //Carga la visita
        $visita = new self(
            (int)$fila['id_visita'],
            $fila['fecha_asignada'],
            $fila['hora'],
            $fila['asunto'],
            (int)$fila['id_mascota'],
            $fila['fecha_asignacion'],
            $fila['nombre_mascota'],
            $fila['estado'] ?? null

        );

        //Muestra la visita
        return ['visita' => $visita,
                'cliente' => [ //Carga los datos del cliente
                    'id_cliente' => $fila['id_cliente'],
                    'nombre_cliente' => $fila['nombre_cliente'],
                    'apellido_cliente' => $fila['apellido_cliente']
                ],
                'diagnostico' => [//Carga los datos de la visita
                    'id_diagnostico' => $fila['id_diagnostico'],
                    'diagnostico' => $fila['diagnostico'],
                    'tratamiento' => $fila['tratamiento']
                ]
        ];
    }

}
?>
