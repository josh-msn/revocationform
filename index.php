<?php
// Fehlerberichterstattung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_credentials.php';

// Verbindung zur Datenbank herstellen
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// IDs initialisieren
$interessentId = 0;
$immobilieId = 0;

// Überprüfen, ob die URL-Parameter vorhanden sind
foreach ($_GET as $key => $value) {
    if (strpos($key, 'iid') === 0) {
        $interessentId = (int)substr($key, 3);
    }
    if (strpos($key, 'imid') === 0) {
        $immobilieId = (int)substr($key, 4);
    }
}

// Überprüfen, ob die IDs gültig sind
if ($interessentId > 0 && $immobilieId > 0) {
    // Daten für den Interessenten abrufen
    $interessentQuery = "SELECT * FROM Kontakte WHERE id = ?";
    if ($stmt = $conn->prepare($interessentQuery)) {
        $stmt->bind_param("i", $interessentId);
        $stmt->execute();
        $interessentResult = $stmt->get_result();
        $interessent = $interessentResult->fetch_assoc();

        if (!$interessent) {
            die("Interessent nicht gefunden mit ID: $interessentId<br>");
        }
    } else {
        die("Fehler bei der Vorbereitung der SQL-Abfrage für Interessenten: " . $conn->error);
    }

    // Daten für die Immobilie abrufen
    $immobilieQuery = "SELECT * FROM Immobilien WHERE id = ?";
    if ($stmt = $conn->prepare($immobilieQuery)) {
        $stmt->bind_param("i", $immobilieId);
        $stmt->execute();
        $immobilieResult = $stmt->get_result();
        $immobilie = $immobilieResult->fetch_assoc();

        if (!$immobilie) {
            die("Immobilie nicht gefunden mit ID: $immobilieId<br>");
        }
    } else {
        die("Fehler bei der Vorbereitung der SQL-Abfrage für Immobilien: " . $conn->error);
    }

    // **Hier fügen wir die Überprüfung hinzu**
    $widerrufQuery = "SELECT * FROM Widerrufsbelehrung WHERE ID_Kontakt = ? AND ID_Immobilie = ?";
    if ($stmt = $conn->prepare($widerrufQuery)) {
        $stmt->bind_param("ii", $interessentId, $immobilieId);
        $stmt->execute();
        $widerrufResult = $stmt->get_result();
        $widerruf = $widerrufResult->fetch_assoc();

        if ($widerruf) {
            // Interessent hat bereits zugestimmt, Weiterleitung zum Exposé
            if (!empty($immobilie['URL'])) {
                header("Location: " . $immobilie['URL']);
                exit();
            } else {
                // Fallback, falls keine URL vorhanden ist
                header("Location: https://www.immowelt.de/");
                exit();
            }
        }
    } else {
        die("Fehler bei der Vorbereitung der SQL-Abfrage für Widerrufsbelehrung: " . $conn->error);
    }

    // Beim Absenden des Formulars die Widerrufsbelehrung speichern
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $akzeptiert = 1; // angenommen, die Akzeptanz ist immer true bei erfolgreichem Submit
        $datum = date('Y-m-d H:i:s');

        $insertQuery = "INSERT INTO Widerrufsbelehrung (ID_Kontakt, ID_Immobilie, Akzeptiert, Datum) 
                        VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($insertQuery)) {
            $stmt->bind_param("iiis", $interessentId, $immobilieId, $akzeptiert, $datum);
            
            if ($stmt->execute() === TRUE) {
                // Überprüfen, ob die URL-Spalte existiert und einen gültigen Wert enthält
                if (!empty($immobilie['URL'])) {
                    // Weiterleitung zu der URL aus der Datenbank
                    header("Location: " . $immobilie['URL']);
                    exit();
                } else {
                    // Fallback, falls keine URL vorhanden ist
                    header("Location: https://www.immowelt.de/");
                    exit();
                }
            } else {
                echo "Fehler beim Speichern: " . $conn->error;
            }
        } else {
            echo "Fehler bei der Vorbereitung der Insert-Abfrage: " . $conn->error;
        }
    }

    $conn->close();

    include 'layout.php';
} else {
    // IDs sind ungültig oder nicht vorhanden
    echo "Dieser Link ist nicht korrekt. Bitte einen neuen anfordern.";
}

?>
