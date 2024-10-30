<?php
// test.php

// Einbinden der Datenbank-Anmeldeinformationen
require_once 'db_credentials.php';

// Versuchen, eine Verbindung zur Datenbank herzustellen
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verbindung prüfen
if ($conn->connect_error) {
    die("<h2>Verbindung zur Datenbank fehlgeschlagen:</h2> " . $conn->connect_error);
} else {
    echo "<h2>Verbindung zur Datenbank erfolgreich!</h2>";
}

// PHP-Info anzeigen
phpinfo();

// Eine einfache Ausgabe, um sicherzustellen, dass PHP-Skripte ausgeführt werden können
echo "<h1>PHP Testseite</h1>";
echo "<p>Wenn Sie diese Nachricht sehen, funktioniert PHP korrekt auf Ihrem Server.</p>";

// Testen von Variablen
$testVariable = "Hallo, Welt!";
echo "<p>Variable testen: $testVariable</p>";

// Datum und Uhrzeit
echo "<p>Aktuelles Datum und Uhrzeit: " . date("Y-m-d H:i:s") . "</p>";

// Eine einfache Funktion definieren und aufrufen
function testFunction() {
    return "Dies ist eine Funktion, die in PHP läuft.";
}

echo "<p>" . testFunction() . "</p>";

// Verbindung schließen
$conn->close();
?>
