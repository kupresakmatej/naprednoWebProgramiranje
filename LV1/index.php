<?php
require 'simple_html_dom.php';
if (!function_exists('str_get_html')) {
    die("Error: simple_html_dom.php not loaded correctly.\n");
}
echo "simple_html_dom loaded successfully.\n";

interface iRadovi
{
    public function create($naziv_rada, $tekst_rada, $link_rada, $oib_tvrtke);
    public function save();
    public function read();
}

class DiplomskiRadovi implements iRadovi
{
    private $naziv_rada;
    private $tekst_rada;
    private $link_rada;
    private $oib_tvrtke;

    public function __construct($naziv_rada, $tekst_rada, $link_rada, $oib_tvrtke)
    {
        $this->naziv_rada = $naziv_rada;
        $this->tekst_rada = $tekst_rada;
        $this->link_rada = $link_rada;
        $this->oib_tvrtke = $oib_tvrtke;
    }

    public function create($naziv_rada, $tekst_rada, $link_rada, $oib_tvrtke)
    {
        return new self($naziv_rada, $tekst_rada, $link_rada, $oib_tvrtke);
    }

    public function save()
    {
        $conn = getDbConnection();
        $stmt = $conn->prepare("INSERT INTO diplomski_radovi (naziv_rada, tekst_rada, link_rada, oib_tvrtke) VALUES (:naziv, :tekst, :link, :oib)");
        $stmt->execute([
            ':naziv' => $this->naziv_rada,
            ':tekst' => $this->tekst_rada,
            ':link' => $this->link_rada,
            ':oib' => $this->oib_tvrtke
        ]);
        echo "Thesis '{$this->naziv_rada}' saved.\n";
    }

    public function read()
    {
        $conn = getDbConnection();
        $stmt = $conn->query("SELECT * FROM diplomski_radovi");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

function getDbConnection()
{
    try {
        $conn = new PDO("mysql:host=localhost;dbname=radovi;charset=utf8", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function fetchPageContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die("cURL error: " . curl_error($ch));
    }

    curl_close($ch);

    if (!$response) {
        die("Error: HTML not fetched from $url\n");
    }

    file_put_contents("debug.html", $response);
    return $response;
}

function parsePageContent($html)
{
    if (!$html || empty($html)) {
        die("Error: HTML not fetched correctly.\n");
    }

    $start = strpos($html, '<article');
    $end = strrpos($html, '</article>') + strlen('</article>');
    $html = substr($html, $start, $end - $start);

    $dom = str_get_html($html);

    if (!$dom) {
        die("Error: simple_html_dom couldnt parse\n");
    }

    $radovi = [];
    foreach ($dom->find('article') as $article) {
        echo "Found <article>\n";

        $titleElement = $article->find('h2.blog-shortcode-post-title.entry-title', 0);
        $linkElement = $titleElement ? $titleElement->find('a', 0) : null;
        $textElement = $article->find('div.fusion-post-content-container p', 0);

        if ($titleElement)
            echo "Naslov: " . $titleElement->plaintext . "\n";
        if ($textElement)
            echo "Tekst: " . $textElement->plaintext . "\n";
        if ($linkElement)
            echo "Link: " . $linkElement->href . "\n";

        if ($titleElement && $textElement && $linkElement) {
            $naziv = trim($titleElement->plaintext);
            $tekst = trim($textElement->plaintext);
            $link = $linkElement->href;
            $oib = "Uknown OIB";
            $img = $article->find('img', 0);
            if ($img && isset($img->src)) {
                $oib = pathinfo($img->src, PATHINFO_FILENAME);
            }

            $rad = new DiplomskiRadovi($naziv, $tekst, $link, $oib);
            $radovi[] = $rad;
        }
    }

    $dom->clear();
    unset($dom);
    return $radovi;
}

function extractOIBFromArticle($article)
{
    $imgElement = $article->find('img', 0);
    if ($imgElement && isset($imgElement->src)) {
        $imgUrl = $imgElement->src;
        $oib = pathinfo($imgUrl, PATHINFO_FILENAME);
        if (is_numeric($oib) && strlen($oib) == 11) {
            return $oib;
        }
    }
    return null;
}

for ($i = 2; $i <= 6; $i++) {
    $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/{$i}";
    $html = fetchPageContent($url);
    $radovi = parsePageContent($html);

    foreach ($radovi as $rad) {
        print_r($rad->read());
        $rad->save();
    }
}
?>