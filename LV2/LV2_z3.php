<?php
$xmlFile = "LV2.xml";
if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    foreach ($xml->record as $osoba) {
        echo "<div style='border:1px solid black; padding:10px; margin:10px;'>";
        echo "<img src='" . trim($osoba->slika) . "' width='100'><br>";
        echo "<strong>" . $osoba->ime . " " . $osoba->prezime . "</strong><br>";
        echo "Email: " . $osoba->email . "<br>";
        echo "Životopis: " . $osoba->zivotopis . "<br>";
        echo "</div>";
    }
} else {
    echo "Greška: XML datoteka ne postoji.";
}
?>