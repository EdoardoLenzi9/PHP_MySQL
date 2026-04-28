<?php
$user = "root";
$password = "";
$host = "localhost";
$dsn = "mysql:host=$host;port=3306;dbname=hotel;charset=utf8";

$pdo = new PDO($dsn, $user, $password);

echo "<h1>Dati nelle tabelle 'hotel'</h1>";

$tables = ['CAMERA', 'CLIENTE', 'HOTEL', 'PRENOTAZIONE'];

foreach ($tables as $table) {
    echo "<h2>$table</h2>";
    
    $stmt = $pdo->query("SELECT * FROM $table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($rows)) {
        echo "<p>Nessun dato in $table.</p>";
        continue;
    }
    
    echo "<table border='1' style='border-collapse: collapse; width:100%;'>";
    echo "<tr>";
    foreach (array_keys($rows[0]) as $col) {
        echo "<th style='padding:8px;'>$col</th>";
    }
    echo "</tr>";
    
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td style='padding:8px;'>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table><br><br>";
}
?>
