<!--Veterinaria-->
<!--José Pablo Chinchilla Chinchilla - Desarrollo de Software- Sección 12-4-->
<!--Index.php-->

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <title>Veterinaria - Inicio</title>
        <link rel="stylesheet" href="Styles.css" />
        <style>
            /* Diseño header */
            header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 1rem;
                background-color: #197a36;
                color: white;
                font-weight: bold;
                font-size: 1.2rem;
            }
            
            .btn-logout {
                background-color: #069e13ff;
                border: none;
                color: white;
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .btn-logout:hover {
                background-color: #055c05ff;
            }

            h2 {
                color: #197a36;          /* Verde oscuro, mismo color que el header */
                font-size: 2rem;         /* Tamaño destacado */
                font-weight: 700;        /* Negrita */
                margin-bottom: 0.5rem;   /* Separación con el párrafo */
                text-align: center;      /* Centrado */
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                text-shadow: 1px 1px 2px rgba(0,0,0,0.1); /* Suave sombra para darle profundidad */
            }

            p {
                color: #555;             /* Gris medio para no competir con el título */
                font-size: 1.1rem;       /* Tamaño legible */
                text-align: center;      /* Centrado para coherencia */
                margin-top: 0;
                margin-bottom: 1.5rem;   /* Espacio extra debajo */
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

        </style>
    </head>
    <body>
        <header>
            Veterinaria Doc. Tulip - Panel Principal

            <!-- Formulario para cerrar sesión -->
            <form action="Logout.php" method="post" style="margin:0;">
                <button type="submit" class="btn-logout" aria-label="Cerrar sesión">Cerrar sesión</button>
            </form>
        </header>
        <main>

            <h2>Bienvenido a la Veterinaria</h2>
            <p>Usa el menú para navegar entre las opciones.</p>

            <!--Botones del menu en fila con diseño cuadrado-->
            <nav class="nav-menu">
                <form class="form-menu" action="Funciones/Listar/listar_clientes.php" method="get">
                    <button type="submit" aria-label="Clientes">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor" >
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
                            1.79-4 4 1.79 4 4 4zM6 18v-2c0-2.21 3-3 
                            6-3s6 .79 6 3v2H6z"/>
                        </svg>
                        Clientes
                    </button>
                </form>
                <form class="form-menu" action="Funciones/Listar/listar_mascotas.php" method="get">
                    <button type="submit" aria-label="Mascotas">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c1.3 0 2.4.84 2.82 2.02.21.6-.05 1.27-.6 1.59-.55.32-1.27.23-1.71-.22l-.51-.51-.51.51c-.44.45-1.16.54-1.71.22-.55-.32-.81-.99-.6-1.59A3.002 3.002 0 0 1 12 12zM8.5 8C9.33 8 10 8.67 10 9.5S9.33 11 8.5 11 7 10.33 7 9.5 7.67 8 8.5 8zm7 0c.83 0 1.5.67 1.5 1.5S16.33 11 15.5 11 14 10.33 14 9.5 14.67 8 15.5 8zm-6.1-3.8c.7.4.94 1.28.54 1.98s-1.28.94-1.98.54-.94-1.28-.54-1.98 1.28-.94 1.98-.54zm6.2 0c.7.4.94 1.28.54 1.98s-1.28.94-1.98.54-.94-1.28-.54-1.98 1.28-.94 1.98-.54z"/>
                        </svg>
                        Mascotas
                    </button>
                </form>
                <form class="form-menu" action="Funciones/Listar/listar_visitas.php" method="get">
                    <button type="submit" aria-label="Cita médica">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
                            <path d="M7 11h5v5H7z" />
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1 .9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM19 20H5V9h14v11z"/>
                        </svg>
                        Visitas
                    </button>
                </form>
                <form class="form-menu" action="Diagnostico/diagnostico.php" method="get">
                    <button type="submit" aria-label="Diagnostico">
                        <svg class="icon" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
                            <path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1 .9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                        </svg>
                        Diagnóstico
                    </button>
                </form>
                <form class="form-menu" action="Logout.php" method="get">
                    <button type="submit" aria-label="Cerrar Sesión">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8a2 2 0 0 0-2 2v4h2V5h8v14h-8v-4h-2v4a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </nav>


            
        </main>

        <footer>
            Desarrollado por: José Pablo Chinchilla 12-4 Desarrollo de Software
        </footer>
    </body>
</html>