### Nombre del Estudiante: José Pablo Chinchilla Chinchilla
### Sección: 12_4           
### Especialidad: Informática Desarrollo de Software

<br>

# Veterinaria - Prueba Técnica

## Introducción:

Este proyecto busca crear una basé solida para una aplicación web, diseñada para suplir las necesidades de una pequeña veterinaria, algunos de estos requisitos son:

- Crear una interfaz intuitiva.
- Capacidad de registrar mascotas.    
- Capacidad de registrar clientes y asociarlos con mascotas.
- Registrar visitas médicas.
- Guardar el historial de una mascotas.
- Generar un reporte.

Se debe entregar el código fuente con instrucciones de ejecución (en archivo comprimido o repo).

<br>

## Instrucciones específicas:

### Parte 1: Modelaje de Clases (20 pts)

- Define al menos tres clases principales: Mascota, Dueño, Visita Médica. Cada clase debe tener:

- Atributos adecuados

- Constructores

- Métodos getters y setters

Relaciones:

- Una mascota puede tener uno o varios dueños

- Una mascota puede tener varias visitas médicas registradas

- Extra (opcional): uso de colecciones para las relaciones.

<br>

### Parte 2: Funcionalidades Básicas (50 pts)

Crea un programa que permita:

1. Registrar nuevas mascotas

2. Registrar dueños y asociarlos a mascotas

3. Registrar visitas médicas (fecha, diagnóstico, tratamiento)

4. Mostrar un listado de mascotas con sus datos, dueños y últimas visitas

5. Buscar mascota por nombre y mostrar su historial

<br>

### Parte 3: Manejo de Errores y Validaciones (10 pts)

- Incluye validación de entradas y mensajes claros en caso de error.

<br>

### Parte 4: Documentación y Presentación (10 pts)

- Comentarios en el código, archivo README con instrucciones de ejecución y breve explicación.

<br>

### Parte 5: BONUS – Funcionalidad Extra (10 pts)

Opcional:

- Exportar datos a archivo .txt o .csv

- Generar estadísticas básicas

- Sistema de login simple

<br>

## Instrucciones de Instalación y Preparación

### Instalación y activación:

- Abrí es explorador de  archivos, descomprimir el archivo .zip **Veterinaria-Prueba Técnica**

- Buscar en la raíz el Installer de xampp, llamado: **xampp-windows-x64-8.2.12-0-VS16-installer**

- Instalar **xampp** usando el installer guardado en la carpeta Prueba Técnica.

- Seguir las instrucciones del installer, especificar la creación de la carpeta xampp en el **disco local**, solicitar el servicio de **Apache** y **MySQL**, o todos los servicios si es deseado.

- Una vez terminado el proceso de instalación, activar **MySQL** desde **XAMPP Control Panel**.

- Mover o copiar la carpeta **Veterinaria** en la carpeta **Veterinaria-Prueba Técnica** hacia la carpeta **htdocs**, dentro de la carpeta **xampp** en el **disco local**.
Esta deberia ser la ruta: **Disco local(C:)/xampp/htdocs/Veterinaria**

<br>

### Creación de la base de datos:

- Acceder al phpMyAdmin mediante el botón **Admin** en el panel de control, que te enviara al dashboard de **XAMPP**, en el presionar el botón **phpMyAdmin**.

- Acceder a la pestaña de **SQL** mediante la respectiva opción en el menú superior de phpMyAdmin.

- Insertar el código fuente del archivo **Database.sql**, (**SIN LOS COMENTARIOS, PREFERIBLEMENTE**) y presionar el botón **Continuar**.

- La base de datos completa se creara de forma automática, junto con todas las tablas y llaves.

<br>

## Instrucciones de uso

### Ingreso:

Para ingresar a la página, utilizar este enlace **http://localhost/Veterinaria/login.php**, el sistema solicitara dos datos, **Usuario** y **Contraseña**

- Usuario: 'Admin'
- Contraseño: '12345678'

<br>

### Inicio:

- Desde el menú de Inicio, se puede navegar a través de las diferentes listas almacenadas en la base de datos, **Clientes**, **Mascotas** y **Visitas**, aparte de eso existen dos botones adicionales, **Diagnóstico** y **Cerrar Sesión** uno para ingresar un diagnóstico y otro para cerrar la sesión, en el header de inicio hay otro botón para cerrar sesión.

<br>

### Menú:

- Aparece un menú en la parte superior de la página, posee acceso a todas las lista y al inicio, además de un botón para registrar datos específicos para la lista de la página actual. 

<br>

### Clientes: 

- En esta área se encuentra la lista de clientes, con una barra de búsqueda para filtrar por diferentes tipos de datos, la lista muestra: **id del clientes, nombre, apellidos, cédula, número de teléfono y correo**, además de dos botones por fila: **Editar** y **Eliminar**. 

- En el menú superior existe un botón para registrar un nuevo cliente (**Registrar**), los datos requeridos son: **Nombre, apellidos, cédula, número de teléfono y correo**. 

- Es posible editar los datos del cliente tras registrarlo, usando el botón **Editar** en la tabla, te permitirá alterar los datos antes mencionados. 

- El botón **Eliminar** permite eliminar todos los datos referentes a un cliente usando su id como guía.

<br>

### Mascotas: 

- En esta área se encuentra la lista de mascotas, con una barra de búsqueda para filtrar por diferentes tipos de datos, la lista muestra: **id de la mascota, nombre, especie, raza, edad, sexo y nombre del dueño(Dueños)**  además de tres botones por fila: **Editar**, **Historial** y **Eliminar**. 

- En el menú superior existe un botón para registrar una nueva mascota (**Registrar**), los datos requeridos son: **Nombre, especie, raza, edad, sexo y dueño o múltiples dueños**, para este se debe mantener presionada la tecla **Ctrl** o **Shift + Clic** izquierdo al nombre del cliente registrado. 

- Es posible editar los datos de la mascota tras registrarla, usando el botón **Editar** en la tabla, te permitirá alterar los datos antes mencionados. 

- El botón **Historial** permite revisar los diagnósticos medicos de la mascota. Un registro completo de cada una de sus citas realizadas. Hablaremos más de el historial en otro punto de la guía. 

- El botón **Eliminar** permite eliminar todos los datos referentes a una mascota usando su id como guía, excepto los del cliente.

<br>

### Visitas: 

- En esta área se encuentra la lista de las visitas médicas, registradas, en tres estados diferentes: **Vigentes, Concluidad y Caducadas** donde vigente es una cita pendiente a realizar, concluida es una cita ya realizada y caducada es una que paso su fecha agendada y por lo tanto fue cancelada.

- Posee una barra de búsqueda que filtra por diferentes tipos de datos guardados en la lista, los cuales son: **id de la visita, fecha de registro, fecha, hora, asunto,id de la mascota, nombre de la mascota y estado**, cada fila posee los botones: **Editar** y **Eliminar**.

- Es posible editar algunos datos de la visita tras registrarla, usando el botón **Editar** en la tablaestos son: **Fecha, hora y asunto**.

- El botón **Eliminar** permite eliminar todos los datos referentes a una visita usando su id como guía, excepto los de la mascota.

- En la parte superior a la tabla se visualizan la cantidad de visitas referentes a sus estados.

<br>

- Nota adicional, el botón **Mostrar todo**, muestra todas las filas sin flitro, en todas las listas

<br>

### Diagnóstico:

- Permite registrar el diagnóstico de una cita con un formulario siguiendo estos pasos:

1. Ingresar la **mascota (Nombre y dueño)**.
2. Ingresar la **visita** / **cita** de esa **mascota (Fecha y asunto)**.
3. Digitar el **peso, la altura, las observaciones y tratamiento**.
4. Digitar el **costo (Usando un punto para los decimales, ejem: 1000.00)**.
5. Presionar **Guardar diagnóstico** para guardarlo.

- Una vez guardado el diagnóstico, el estado de la visita cambiara a **Concluida** de forma automatica.

- En la parte inferior estan unos botones para desplazarse al **Inicio** y a la lista de **Visitas**, aparte de otro para **Reiniciar** el formulario.

<br>

### Historial:

- Se accede por el botón **Historial** en la lista de **Mascotas**,  aquí es posible revisar de manera superficial algunos datos de la visita que fueron guardados en el diagnóstico, en otras palabras, es posible revisar todos los diagnósticos que una mascota allá recibido, estos datos son: **id del diagnostico, id de la visita, fecha, clientes, costo** y un campo adicional para un botón de detalles aparte del botón **Volver** para regresar.

- El botón de detalles redirige a una a un archivo con **TODOS** (Con una pequeña excepción, solo se mostrara el primer dueño de la mascota guardado en la DB y sus respectivos datos) los datos relacionados a la **Mascota, Cliente , Visita, Diagnóstico** y incluso el local donde se llevo a cabo, como si fuera una factura.

- Hay dos botones en la parte inferior de la factura, **Volver al Historial** y **Imprimir**. El primero, regresa al **Historial**, el segundo inserta los datos en un archivo TXT, en el **Bloc de notas**.

### Final:

Con esto concluye la guía de uso, **Muchas gracias por leerlo**

