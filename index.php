<?php
// Configuración de la base de datos
$dbhost = '40.117.148.233';
$dbname = 'gestion_de_reportes';
$dbuser = 'remote';
$dbpass = 'Nicol.225-linux';

try {
    // Conectar a la base de datos utilizando PDO
    $dsn = "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $dbuser, $dbpass, $options);

    echo "Conexión exitosa a la base de datos";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
