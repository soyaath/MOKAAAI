<?php
require_once __DIR__.'/config.php';
if (!empty($_SESSION['usuario_id'])) { header('Location: '.(($_SESSION['rol'] ?? '') === 'Administrador' ? 'admin.php' : 'estudiante.php')); exit; }
header('Location: login.php'); exit;
