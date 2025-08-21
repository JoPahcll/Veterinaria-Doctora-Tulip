<?php
//Veterinaria
//José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4
//Clase Cliente.php

class Cliente {
    private $id;
    private $nombre;
    private $apellido;  
    private $telefono;
    private $cedula;
    private $correo;

    //Contructor
    public function __construct($nombre, $apellido, $telefono, $cedula, $correo = null, $id = null) {
        $this->id = $id; 
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->cedula = $cedula;
        $this->correo = $correo;
    }

    //Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getTelefono() { return $this->telefono; }
    public function getCedula() { return $this->cedula; }
    public function getCorreo() { return $this->correo; }

    //Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setCedula($cedula) { $this->cedula = $cedula; }
    public function setCorreo($correo) { $this->correo = $correo; }

    //Guardar
    public function guardar(PDO $pdo) {
        try {//Itera os campos a llenar
            $sql = "INSERT INTO clientes (nombre, apellido, telefono, cedula, correo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $exito = $stmt->execute([
                $this->nombre,
                $this->apellido,
                $this->telefono,
                $this->cedula,
                $this->correo
            ]);
            if ($exito) {
                $this->id = $pdo->lastInsertId();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "Error: Ya existe un cliente con esa cédula.";
            }
            return "Error en el registro: " . $e->getMessage();
        }
    }

    //Actualizar cliente
    public function actualizar(PDO $pdo) {
        if ($this->id === null) {
            throw new Exception("No se puede actualizar cliente sin ID.");//Mensaje de error, no se encuentra el id
        }
        $sql = "UPDATE clientes SET nombre = ?, apellido = ?, telefono = ?, cedula = ?, correo = ?
                WHERE id_cliente = ?";//Actualiza los campos guiandose por el id
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([//Devuelve los campos con los nuevos datos
            $this->nombre,
            $this->apellido,
            $this->telefono,
            $this->cedula,
            $this->correo,
            $this->id
        ]);
    }

    //Obtener todos los clientes mediante un array
    public static function obtenerTodos(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM clientes");
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];
        foreach ($filas as $fila) {
            $clientes[] = new self(
                $fila['nombre'],
                $fila['apellido'],
                $fila['telefono'],
                $fila['cedula'],
                $fila['correo'],
                $fila['id_cliente']
            );
        }
        return $clientes;
    }

    //Obtener cliente por su ID
    public static function obtenerPorId(PDO $pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$fila) return null;
        return new self(
            $fila['nombre'],
            $fila['apellido'],
            $fila['telefono'],
            $fila['cedula'],
            $fila['correo'],
            $fila['id_cliente']
        );
    }

    //Buscar clientes usando un filtro
    public static function buscarConFiltro(PDO $pdo, $filtro, $valor) {
        $columnasPermitidas = ['id_cliente', 'nombre', 'apellido', 'cedula'];

        if (!in_array($filtro, $columnasPermitidas)) {
            return self::obtenerTodos($pdo);
        }

        //Selecciona los clientes usando el filtro elegido y el valor de búsqueda
        $sql = "SELECT * FROM clientes WHERE $filtro LIKE :valor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['valor' => '%' . $valor . '%']);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clientes = [];
        foreach ($filas as $fila) {
            $clientes[] = new self(
                $fila['nombre'],
                $fila['apellido'],
                $fila['telefono'],
                $fila['cedula'],
                $fila['correo'],
                $fila['id_cliente']
            );
        }
        return $clientes;
    }

    //Eliminar cliente
    public static function eliminarPorId(PDO $pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id_cliente = ?");//Utiliza el id del cliente
        return $stmt->execute([$id]);
    }

}
?>
