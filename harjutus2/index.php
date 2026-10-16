
<?php

header("Content-Type: application/json; charset=utf-8");

// Avame UTF-8 kodeeringus faili
$file = fopen(__DIR__ . "/LE/LE_utf8.txt", "r");

if ($file === false) {
    http_response_code(500);
    echo json_encode(["error" => "Faili ei õnnestunud avada"]);
    exit;
}

// Loome tühja toodete massiivi
$products = [];

// Loeme kõik tooted failist
while (($row = fgetcsv($file, 0, "\t", '"', "")) !== false) {

    $products[] = [
        "code" => $row[0],
        "name" => trim($row[1]),
        "price" => $row[8]
    ];

}

// Sulgeme faili
fclose($file);

// Loeme URL-ist otsinguparameetrid
$searchCode = $_GET["sn"] ?? null;
$searchName = $_GET["name"] ?? null;

// Lehekülgede seaded
$page = max(1, (int) ($_GET["page"] ?? 1));
$limit = 30;

// Otsing tootekoodi järgi
if ($searchCode !== null) {

    $products = array_filter($products, function ($product) use ($searchCode) {
        return $product["code"] === $searchCode;
    });

}

// Otsing nimetuse järgi
if ($searchName !== null) {

    $products = array_filter($products, function ($product) use ($searchName) {
        return stripos($product["name"], $searchName) !== false;
    });

}

// Arvutame lehekülje algusindeksi
$offset = ($page - 1) * $limit;

// Võtame vastava lehekülje 30 toodet
$pagedProducts = array_slice($products, $offset, $limit);

// Kontrollime API aadressi
if (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) === "/spare-parts") {

    // Teisendame tulemuse JSON-vormingusse
    $json = json_encode(
        $pagedProducts,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_UNICODE |
        JSON_INVALID_UTF8_SUBSTITUTE
    );

    if ($json === false) {

        http_response_code(500);

        echo json_encode([
            "error" => json_last_error_msg()
        ]);

    } else {

        echo $json;

    }

} else {

    http_response_code(404);

    echo json_encode([
        "error" => "Lehte ei leitud"
    ]);

}
