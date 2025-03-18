<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "moja_baza";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Neuspjelo povezivanje: " . $conn->connect_error);
}

$sql = "SHOW TABLES";
$result = $conn->query($sql);
$backup_data = "";

while ($row = $result->fetch_array()) {
    $table = $row[0];
    $res = $conn->query("SELECT * FROM $table");
    while ($data = $res->fetch_assoc()) {
        $values = array_map(function ($v) {
            return "'" . addslashes($v) . "'";
        }, array_values($data));
        $backup_data .= "INSERT INTO $table (" . implode(",", array_keys($data)) . ") VALUES (" . implode(",", $values) . ");\n";
    }
}

file_put_contents("backup.txt", $backup_data);

$zip = new ZipArchive();
if ($zip->open("backup.zip", ZipArchive::CREATE) === TRUE) {
    $zip->addFile("backup.txt", "backup.txt");
    $zip->close();
    unlink("backup.txt");
    echo "Backup završen!";
} else {
    echo "Greška pri kreiranju ZIP-a!";
}
?>