<?php
$host = "127.0.0.1";
$port = "3306";
$user = "user";
$password = "password";
$dsn = "mysql:host=$host;port=$port;charset=utf8";
$pdo = new PDO($dsn, $user, $password);

$message = '';
if ($_POST) {
    try {
        $table = $_POST['table'];
        $columns = [];
        $values = [];
        
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'col_') === 0) {
                $colName = substr($key, 4);
                $columns[] = $colName;
                $values[] = $pdo->quote($_POST[$key]);
            }
        }
        
        $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ")";
        $pdo->exec($sql);
        $message = "<div style='color:green;'>Dato aggiunto a $table!</div>";
    } catch (Exception $e) {
        $message = "<div style='color:red;'>Errore: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Aggiungi Dato</title></head>
<body>
<h1>Aggiungi Dato</h1>
<?php echo $message; ?>

<form method="POST">
    <label>Tabella:</label>
    <select name="table" onchange="loadColumns(this.value)">
        <option value="CAMERA">CAMERA</option>
        <option value="CLIENTE">CLIENTE</option>
        <option value="HOTEL">HOTEL</option>
        <option value="PRENOTAZIONE">PRENOTAZIONE</option>
    </select><br><br>
    
    <div id="columns"></div>
    <br>
    <button type="submit">INSERISCI</button>
</form>

<script>
function loadColumns(table) {
    const columnsDiv = document.getElementById('columns');
    let html = '';
    
    if (table === 'CAMERA') {
        html = `
            Piano: <input name="col_Piano" type="number" required><br>
            Prezzo: <input name="col_Prezzo" type="number" step="0.01" required><br>
            Metratura: <input name="col_Metratura" type="number" required><br>
            NLetti: <input name="col_NLetti" type="number" required><br>
        `;
    } else if (table === 'CLIENTE') {
        html = `
            Nome: <input name="col_Nome" required><br>
            Cognome: <input name="col_Cognome" required><br>
            Indirizzo: <input name="col_Indirizzo"><br>
            Telefono: <input name="col_Telefono"><br>
        `;
    } else if (table === 'HOTEL') {
        html = `
            RagioneSociale: <input name="col_RagioneSociale" required><br>
            Indirizzo: <input name="col_Indirizzo"><br>
            Telefono: <input name="col_Telefono"><br>
            CCa (CodCamera): <input name="col_CCa" type="number" required><br>
        `;
    } else if (table === 'PRENOTAZIONE') {
        html = `
            DataInizio: <input name="col_DataInizio" type="date" required><br>
            DataFine: <input name="col_DataFine" type="date" required><br>
            CCa (CodCamera): <input name="col_CCa" type="number" required><br>
            CCli (CodCliente): <input name="col_CCli" type="number" required><br>
        `;
    }
    
    columnsDiv.innerHTML = html;
}
loadColumns('CAMERA'); // Default
</script>
</body>
</html>
