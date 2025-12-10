<?php
require_once 'conexion.php';
$db = (new Database())->getConnection();

echo "<h1>Iniciando migración de contraseñas...</h1>";

// 1. Obtener todos los usuarios
$stmt = $db->query("SELECT id, contrasena FROM USUARIO");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;

foreach ($usuarios as $user) {
    // Verificar si YA está hasheada (los hash de PHP empiezan por $2y$)
    if (strpos($user['contrasena'], '$2y$') === 0) {
        continue; // Saltar si ya es hash
    }

    // 2. Crear el Hash
    $hash = password_hash($user['contrasena'], PASSWORD_DEFAULT);

    // 3. Actualizar en la BD
    $update = $db->prepare("UPDATE USUARIO SET contrasena = :hash WHERE id = :id");
    $update->execute([':hash' => $hash, ':id' => $user['id']]);
    
    echo "Usuario ID {$user['id']} actualizado.<br>";
    $count++;
}

echo "<h3>Total actualizados: $count</h3>";
echo "<p>Borra este archivo después de usarlo.</p>";
?>