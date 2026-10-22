<?php
if (session_status() === PHP_SESSION_NONE) session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = 'localhost'; $user = 'root'; $pass = ''; $db = 'dashboard_deportes';
try {
    $conexion = new mysqli($host, $user, $pass, $db);
    $conexion->set_charset('utf8mb4');
    // Perfil extendido: se crea de forma segura sin borrar las tablas existentes.
    $conexion->query("CREATE TABLE IF NOT EXISTS perfil_usuario (id_usuario INT PRIMARY KEY, biografia VARCHAR(500) NULL, foto_perfil VARCHAR(255) NULL, banner_perfil VARCHAR(255) NULL, actualizado TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, CONSTRAINT fk_perfil_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB");
} catch (Throwable $e) {
    http_response_code(500);
    exit('No se pudo conectar con la base de datos dashboard_deportes. Verifica que MySQL esté iniciado en XAMPP y que la base de datos exista.');
}
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf_token(): string { if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
function verify_csrf(): void { if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) { http_response_code(403); exit('Solicitud no válida. Recarga la página e inténtalo de nuevo.'); } }
function require_login(): void { if (empty($_SESSION['usuario_id'])) { header('Location: login.php'); exit; } }
function require_admin(): void { require_login(); if (($_SESSION['rol'] ?? '') !== 'Administrador') { http_response_code(403); exit('Acceso denegado: esta sección es solo para administradores.'); } }
function flash(string $message, string $type='success', bool $celebrate=false): void { $_SESSION['flash'] = ['message'=>$message, 'type'=>$type, 'celebrate'=>$celebrate]; }
function show_flash(): void { if (!empty($_SESSION['flash'])) { $f=$_SESSION['flash']; if (!empty($f['celebrate']) && ($f['type'] ?? '') === 'success') { echo '<div class="celebration-overlay" id="celebrationOverlay" role="dialog" aria-modal="true" aria-labelledby="celebrationTitle"><div class="celebration-confetti" aria-hidden="true"></div><div class="celebration-card"><button class="celebration-close" type="button" aria-label="Cerrar">×</button><img class="celebration-gif" src="assets/registro-ok.gif" alt="Gatito celebrando"><h2 id="celebrationTitle">¡Registro correcto!</h2><p>'.e($f['message']).'</p><button class="btn btn-primary celebration-ok" type="button">¡Genial!</button></div></div>'; } else { echo '<div class="alert '.e($f['type']).'">'.e($f['message']).'</div>'; } unset($_SESSION['flash']); } }
