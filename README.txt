MOKASPORTS - INSTALACIÓN LOCAL EN XAMPP
======================================

REQUISITOS
- XAMPP con Apache, PHP y MySQL/MariaDB.
- Base de datos dashboard_deportes con las tablas definidas en database/dashboard_deportes.sql.

INSTALACIÓN
1. Descomprime la carpeta mokasports en C:\xampp\htdocs\
   Debe quedar C:\xampp\htdocs\mokasports\index.php
2. Abre XAMPP Control Panel e inicia Apache y MySQL.
3. Comprueba que exista la base de datos dashboard_deportes en phpMyAdmin.
   Si ya existe, NO necesitas importar nada. Si no existe, abre phpMyAdmin > Importar y selecciona database/dashboard_deportes.sql.
   El SQL usa CREATE TABLE IF NOT EXISTS y no elimina tablas ni registros.
4. Abre http://localhost/mokasports/setup.php y pulsa Configurar MokaSports.
5. Inicia sesión en http://localhost/mokasports/login.php

CUENTAS DE DEMOSTRACIÓN
Administrador
Correo: admin@mokasports.test
Contraseña: MokaAdmin123!

Estudiante
Correo: estudiante@mokasports.test
Contraseña: MokaEstudiante123!

FUNCIONES
- Login con contraseñas guardadas como hash, sesiones y roles.
- Panel administrador con resumen y creación/eliminación de equipos, jugadores y partidos.
- Marcadores, estado de partido y puntuación Fair Play (0 a 100).
- Panel de estudiante para consultar partidos, equipos y jugadores.
- Diseño adaptable y modo claro/oscuro.
- Consultas preparadas, protección CSRF en formularios y escape de salida HTML.

IMPORTANTE
- La configuración usa el usuario MySQL root sin contraseña, que es la configuración habitual de XAMPP local. Si tu instalación tiene otra contraseña, cambia $user y $pass en config.php.
- setup.php vuelve a establecer las contraseñas demo al ejecutarse. Después de configurarlo, elimina setup.php o cámbiale el nombre.
- Cambia las credenciales de demostración antes de publicar la aplicación en internet.
- El indicador Fair Play es una puntuación ingresada por el administrador por partido, no una evaluación automática de conducta.


NUEVAS FUNCIONES
- Enlace Crear una cuenta en el inicio de sesión (registro público como Estudiante).
- Estudiantes pueden proponer partidos y agregar jugadores desde su panel.
- Administradores y estudiantes pueden editar nombre, apellido, biografía, foto y banner desde perfil.php.
- La tabla perfil_usuario se crea automáticamente al conectar, sin eliminar tablas anteriores.
- Las imágenes permitidas son JPG, PNG y WEBP, con máximo de 3 MB cada una.


ACTUALIZACIÓN: Se agregó la sección “Recomendaciones para un juego limpio”, con GIF e imagen decorativos, y el tema “Modo rosita”. El botón de tema alterna entre claro, rosita y oscuro.
