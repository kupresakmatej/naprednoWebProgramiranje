<?php
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $allowed = ['pdf', 'jpeg', 'jpg', 'png'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (in_array($ext, $allowed)) {
        if ($file['error'] === 0) {
            $uploadDir = 'uploads/';
            $filePath = $uploadDir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                echo "Datoteka je uspješno uploadana!<br>";

                $data = file_get_contents($filePath);
                $key = "tajni_kljuc";
                $encrypted = openssl_encrypt($data, "AES-128-ECB", $key, 0, "");

                file_put_contents($filePath . ".enc", $encrypted);
                unlink($filePath);

                echo "Datoteka je kriptirana i spremljena.";
            } else {
                echo "Greška pri prijenosu datoteke.";
            }
        } else {
            echo "Došlo je do greške pri uploadu datoteke.";
        }
    } else {
        echo "Neispravan format datoteke. Dozvoljeni formati su PDF, JPEG, PNG.";
    }
}

$files = glob("uploads/*.enc");

foreach ($files as $file) {
    $data = file_get_contents($file);
    $decrypted = openssl_decrypt($data, "AES-128-ECB", "tajni_kljuc", 0, "");

    $newFile = str_replace(".enc", "", $file);
    file_put_contents("decrypted/" . basename($newFile), $decrypted);

    echo "<a href='decrypted/" . basename($newFile) . "'>Preuzmi " . basename($newFile) . "</a><br>";
}

echo "<hr>";
echo "<h4>Dekriptirane datoteke:</h4>";

$decryptedFiles = glob("decrypted/*");

if ($decryptedFiles) {
    foreach ($decryptedFiles as $decryptedFile) {
        echo "<a href='" . $decryptedFile . "'>Preuzmi " . basename($decryptedFile) . "</a><br>";
    }
} else {
    echo "Nema dekriptiranih datoteka.";
}
?>