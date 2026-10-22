<?php
require_once __DIR__.'/config.php';
if (!empty($_SESSION['usuario_id'])) { header('Location: '.(($_SESSION['rol'] ?? '') === 'Administrador' ? 'admin.php' : 'estudiante.php')); exit; }
$error=''; verify_csrf();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $correo=trim($_POST['correo'] ?? ''); $contrasena=$_POST['contrasena'] ?? '';
    if (!filter_var($correo,FILTER_VALIDATE_EMAIL) || $contrasena==='') $error='Escribe un correo válido y tu contraseña.';
    else {
        $stmt=$conexion->prepare("SELECT u.id_usuario,u.nombre,u.apellido,u.correo,u.rol,u.estado,l.contrasena_hash FROM usuarios u JOIN usuarios_login l ON l.id_usuario=u.id_usuario WHERE u.correo=? LIMIT 1");
        $stmt->bind_param('s',$correo); $stmt->execute(); $u=$stmt->get_result()->fetch_assoc();
        if ($u && password_verify($contrasena,$u['contrasena_hash']) && $u['estado']==='Activo') {
            session_regenerate_id(true); $_SESSION['usuario_id']=(int)$u['id_usuario']; $_SESSION['nombre']=$u['nombre']; $_SESSION['apellido']=$u['apellido']; $_SESSION['correo']=$u['correo']; $_SESSION['rol']=$u['rol'];
            $up=$conexion->prepare('UPDATE usuarios_login SET ultimo_acceso=NOW() WHERE id_usuario=?'); $up->bind_param('i',$_SESSION['usuario_id']); $up->execute();
            header('Location: index.php'); exit;
        } else $error='No se pudo iniciar sesión. Revisa tus datos o el estado de la cuenta.';
    }
}
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ingresar | MokaSports</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-body"><button class="theme-toggle floating-theme" type="button" aria-label="Cambiar tema">◐</button><main class="login-shell"><section class="login-art"><div class="brand"><span class="brand-mark">M</span><span>MokaSports</span></div><div class="art-copy"><span class="eyebrow">DEPORTE · RESPETO · EQUIPO</span><h1>Competir mejor.<br><em>Crecer juntos.</em></h1><p>Un espacio para organizar encuentros deportivos, acompañar equipos y promover el juego limpio.</p></div><div class="art-footer"><span>FAIR PLAY, EVERY DAY</span><span>01 / GESTIÓN DEPORTIVA</span></div></section><section class="login-panel"><div class="login-card"><div class="mobile-brand brand"><span class="brand-mark">M</span><span>MokaSports</span></div><span class="eyebrow">BIENVENIDO/A DE NUEVO</span><h2>Inicia sesión</h2><p class="muted">Ingresa con tu cuenta para continuar.</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Correo electrónico<input type="email" name="correo" autocomplete="username" placeholder="tu@colegio.edu" required value="<?=e($_POST['correo']??'')?>"></label><label>Contraseña<input type="password" name="contrasena" autocomplete="current-password" placeholder="Escribe tu contraseña" required></label><button class="btn btn-primary btn-full" type="submit">Iniciar sesión <span>↗</span></button><a class="btn btn-secondary btn-full create-account-btn" href="registro.php">Crear cuenta <span>＋</span></a></form><div class="login-note"><span class="note-icon">i</span><p>Si aún no configuraste el sistema, abre <a href="setup.php">la configuración inicial</a>.</p></div><div class="login-bottom">MokaSports <span>·</span> Plataforma de gestión deportiva</div></div></section></main><script src="assets/app.js"></script></body></html>
