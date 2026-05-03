<?php 
$host = "127.0.0.1";
$port = "3306";
$user = "user";
$password = "password";
$dsn = "mysql:host=$host;port=$port;charset=utf8";
$pdo = new PDO($dsn, $user, $password);

$pdo->exec("CREATE DATABASE IF NOT EXISTS scuola");
$pdo->exec("USE scuola");

$pdo->exec("
CREATE TABLE IF NOT EXISTS Studente (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50),
    Cognome VARCHAR(50)
)");

$pdo->exec("
CREATE TABLE IF NOT EXISTS Corso (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Materia VARCHAR(50),
    Anno INT
)");

$pdo->exec("
CREATE TABLE IF NOT EXISTS Docente (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50),
    Cognome VARCHAR(50)
)");

$pdo->exec("
CREATE TABLE IF NOT EXISTS Frequenta (
    ID_S INT,
    ID_C INT
)");

$pdo->exec("
CREATE TABLE IF NOT EXISTS Insegna (
    ID_C INT,
    ID_D INT
)");

$check = $pdo->query("SELECT COUNT(*) FROM Studente")->fetchColumn();
if ($check == 0) {

    $file = fopen("scuola.csv", "r");
    fgetcsv($file);

    while (($r = fgetcsv($file, 1000, ",")) !== false) {

        [$nomeS, $cognomeS, $materia, $cognomeD, $anno] = $r;

        $pdo->prepare("INSERT IGNORE INTO Studente (Nome, Cognome) VALUES (?, ?)")
            ->execute([$nomeS, $cognomeS]);

        $pdo->prepare("INSERT IGNORE INTO Corso (Materia, Anno) VALUES (?, ?)")
            ->execute([$materia, $anno]);

        $pdo->prepare("INSERT IGNORE INTO Docente (Nome, Cognome) VALUES (?, ?)")
            ->execute(["", $cognomeD]);

        $idS = $pdo->query("
            SELECT ID FROM Studente
            WHERE Nome='$nomeS' AND Cognome='$cognomeS'
        ")->fetchColumn();

        $idC = $pdo->query("
            SELECT ID FROM Corso
            WHERE Materia='$materia' AND Anno='$anno'
        ")->fetchColumn();

        $idD = $pdo->query("
            SELECT ID FROM Docente
            WHERE Cognome='$cognomeD'
        ")->fetchColumn();

        $pdo->prepare("INSERT IGNORE INTO Frequenta (ID_S, ID_C) VALUES (?, ?)")
            ->execute([$idS, $idC]);

        $pdo->prepare("INSERT IGNORE INTO Insegna (ID_C, ID_D) VALUES (?, ?)")
            ->execute([$idC, $idD]);
    }

    fclose($file);
}

// $cognome = $_POST['cognome'] ?? "";
$cognome = $_GET['cognome'] ?? "";

// default form behavior, send GET request with http://localhost:8000/...?cognome=Rossi
echo "
<h2>Ricerca studente per cognome</h2>
<form>
    <input name='cognome'>
    <button>Cerca</button>
</form>
";

$stmt = $pdo->prepare("
SELECT Studente.Nome, Studente.Cognome, Corso.Materia, Corso.Anno
FROM Studente
JOIN Frequenta ON Studente.ID = Frequenta.ID_S
JOIN Corso ON Corso.ID = Frequenta.ID_C
WHERE Studente.Cognome LIKE ?
");

$stmt->execute(["%$cognome%"]);

echo "<table border='1'>
<tr><th>Nome</th><th>Cognome</th><th>Materia</th><th>Anno</th></tr>";

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
        <td>{$r['Nome']}</td>
        <td>{$r['Cognome']}</td>
        <td>{$r['Materia']}</td>
        <td>{$r['Anno']}</td>
    </tr>";
}
echo "</table>";
$stmtDocenti = $pdo->query("SELECT * FROM Docente");

echo "<h2>Docenti</h2>";
echo "<table border='1'>
<tr>
    <th>ID</th>
    <th>Cognome</th>
</tr>";

while ($r = $stmtDocenti->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
        <td>{$r['ID']}</td>
        <td>{$r['Cognome']}</td>
    </tr>";
}
echo "</table>";
$stmtCorsi = $pdo->query("SELECT * FROM Corso");

echo "<h2>Corsi</h2>";
echo "<table border='1'>
<tr>
    <th>Materia</th>
    <th>Anno</th>
</tr>";

while ($r = $stmtCorsi->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
        <td>{$r['Materia']}</td>
        <td>{$r['Anno']}</td>
    </tr>";
}
echo "</table>";

?>