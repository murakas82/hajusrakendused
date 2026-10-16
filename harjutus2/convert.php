
<?php

// Originaalfail
$inputFile = __DIR__ . "/LE/LE.txt";

// Uus UTF-8 fail
$outputFile = __DIR__ . "/LE/LE_utf8.txt";

// Avame failid
$input = fopen($inputFile, "r");
$output = fopen($outputFile, "w");

if ($input === false || $output === false) {
    die("Faili avamine ebaõnnestus!");
}

// Loendurid
$count = 0;
$encodingErrors = 0;

// Loeme kõik tooted
while (($row = fgetcsv($input, 0, "\t", '"', "")) !== false) {

    // Kontrollime toote nimetuse kodeeringut
    if (!mb_check_encoding($row[1], "UTF-8")) {

        $converted = @iconv("Windows-1257", "UTF-8", $row[1]);

        if ($converted !== false) {
            $row[1] = $converted;
        } else {
            $encodingErrors++;

            $row[1] = iconv(
                "Windows-1257",
                "UTF-8//IGNORE",
                $row[1]
            );

            echo "Hoiatus: vigased märgid eemaldati tootelt "
                . $row[0] . PHP_EOL;
        }
    }

    // Kirjutame KÕIK tooted uude faili
    fputcsv($output, $row, "\t", '"', "");

    $count++;
}

fclose($input);
fclose($output);

echo "Töödeldud ridu: " . $count . PHP_EOL;
echo "Teisendamisvigu: " . $encodingErrors . PHP_EOL;

// Kontrollime uue faili kodeeringut
$checkFile = fopen($outputFile, "r");

$invalidRows = 0;
$lineNumber = 0;

while (($row = fgetcsv($checkFile, 0, "\t", '"', "")) !== false) {
    $lineNumber++;

    if (!mb_check_encoding($row[1], "UTF-8")) {
        $invalidRows++;

        echo "Vigane rida: " . $lineNumber .
             " | Tootekood: " . $row[0] . PHP_EOL;
    }
}

fclose($checkFile);

echo "Kontrollitud ridu: " . $lineNumber . PHP_EOL;
echo "Vigase UTF-8 kodeeringuga nimetusi: " . $invalidRows . PHP_EOL;

$checkFile = fopen($outputFile, "r");

$invalidColumns = 0;

while (($row = fgetcsv($checkFile, 0, "\t", '"', "")) !== false) {

    if (count($row) !== 11) {
        $invalidColumns++;
    }
}

fclose($checkFile);

echo "Vale veergude arvuga ridu: " . $invalidColumns . PHP_EOL;
