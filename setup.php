<?php
require_once __DIR__.'/config.php';
$done=false; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf();
 try {
  // Solo crea las cuentas de demostración si aún no existen. No elimina ni recrea tablas.
  $accounts=[['Demo','Administrador','admin@mokasports.test','MokaAdmin123!','Administrador'],['Demo','Estudiante','estudiante@mokasports.test','MokaEstudiante123!','Estudiante']];
  foreach($accounts as $a){
   [$nombre,$apellido,$correo,$plain,$rol]=$a;
   $s=$conexion->prepare('SELECT id_usuario FROM usuarios WHERE correo=? LIMIT 1'); $s->bind_param('s',$correo); $s->execute(); $existing=$s->get_result()->fetch_assoc();
   if($existing){$id=(int)$existing['id_usuario']; $hash=password_hash($plain,PASSWORD_DEFAULT); $l=$conexion->prepare('INSERT INTO usuarios_login (id_usuario,contrasena_hash) VALUES (?,?) ON DUPLICATE KEY UPDATE contrasena_hash=VALUES(contrasena_hash)'); $l->bind_param('is',$id,$hash); $l->execute(); $up=$conexion->prepare("UPDATE usuarios SET rol=?,estado='Activo' WHERE id_usuario=?"); $up->bind_param('si',$rol,$id); $up->execute();}
   else {$s=$conexion->prepare("INSERT INTO usuarios (nombre,apellido,correo,rol,estado) VALUES (?,?,?,?,'Activo')"); $s->bind_param('ssss',$nombre,$apellido,$correo,$rol); $s->execute(); $id=$conexion->insert_id; $hash=password_hash($plain,PASSWORD_DEFAULT); $l=$conexion->prepare('INSERT INTO usuarios_login (id_usuario,contrasena_hash) VALUES (?,?)'); $l->bind_param('is',$id,$hash); $l->execute();}
  }
  $count=(int)$conexion->query('SELECT COUNT(*) c FROM equipo')->fetch_assoc()['c'];
  if($count===0){$conexion->query("INSERT INTO equipo (nombre_equipo,disciplina,seccion,entrenador) VALUES ('Cóndores','Fútbol','Secundaria','Por asignar'),('Halcones','Fútbol','Secundaria','Por asignar'),('Panteras','Vóley','Secundaria','Por asignar')");}
  $done=true;
 } catch(Throwable $e){$error='No se pudo completar la configuración. Confirma que la base de datos y sus tablas existan. Detalle: '.$e->getMessage();}
}
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Configuración | MokaSports</title><link rel="stylesheet" href="assets/style.css"></head><body class="setup-body"><main class="setup-card"><div class="brand"><span class="brand-mark">M</span><span>MokaSports</span></div><?php if($done): ?><div class="setup-symbol">✓</div><span class="eyebrow">CONFIGURACIÓN COMPLETADA</span><h1>¡Todo listo!</h1><p>Se prepararon las cuentas de demostración y se conservaron las tablas y los datos que ya existían.</p><div class="demo-account"><b>Administrador</b><span>admin@mokasports.test</span><code>MokaAdmin123!</code></div><div class="demo-account"><b>Estudiante</b><span>estudiante@mokasports.test</span><code>MokaEstudiante123!</code></div><a class="btn btn-primary btn-full" href="login.php">Ir al inicio de sesión ↗</a><p class="small-warning">Por seguridad, elimina o cambia el acceso a setup.php después de configurar la aplicación.</p><?php else: ?><span class="eyebrow">PRIMERA CONFIGURACIÓN</span><h1>Prepara tu plataforma</h1><p>Este asistente no elimina la base de datos. Solo crea o actualiza las cuentas demo y añade equipos de ejemplo si no existen.</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><button class="btn btn-primary btn-full" type="submit">Configurar MokaSports</button></form><a class="text-link" href="login.php">Volver al inicio de sesión</a><?php endif; ?></main><script src="assets/app.js"></script></body></html>
