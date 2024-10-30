<?php
// Fehlerberichterstattung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session starten
session_start();

// Datenbank-Anmeldeinformationen und Passwort laden
require_once 'db_credentials.php';

// Überprüfen, ob das Passwort bereits in der Session gesetzt ist
if (isset($_POST['password'])) {
    if ($_POST['password'] === ACCESS_PASSWORD) { // Passwort aus db_credentials.php
        $_SESSION['authenticated'] = true;
    } else {
        $error = "Falsches Passwort. Bitte versuchen Sie es erneut.";
    }
}

// Wenn nicht authentifiziert, zeige das Passwortformular an
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Passwortgeschützte Seite</title>
    </head>
    <body>
        <h2>Bitte geben Sie das Passwort ein, um fortzufahren:</h2>
        <form method="post">
            <input type="password" name="password" required>
            <button type="submit">Anmelden</button>
        </form>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    </body>
    </html>
    <?php
    exit(); // Beende das Skript, um nicht authentifizierte Benutzer auszuschließen
}

// Verbindung zur Datenbank herstellen
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Daten aus der Tabelle "Widerrufsbelehrung" abrufen
$query = "
    SELECT 
        W.Datum,
        W.ID_Kontakt, 
        K.fname, 
        K.lname, 
        K.mail,
        W.ID_Immobilie, 
        I.Titel_Immobilie,
        I.URL  -- Neue Spalte für die URL hinzufügen
    FROM Widerrufsbelehrung W
    JOIN Kontakte K ON W.ID_Kontakt = K.id
    JOIN Immobilien I ON W.ID_Immobilie = I.id
    WHERE W.Akzeptiert = 1
    ORDER BY W.Datum DESC";

$result = $conn->query($query);

// Überprüfen, ob die Abfrage erfolgreich war
if (!$result) {
    die("Fehler bei der Abfrage: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Akzeptierte Widerrufsbelehrungen</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        /* Optional: Styling für die URL-Links */
        a.url-link {
            color: #0066cc;
            text-decoration: none;
        }
        a.url-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Liste der akzeptierten Widerrufsbelehrungen</h1>
    <table>
        <tr>
            <th>Datum</th>
            <th>Kontakt-ID</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>E-Mail</th>
            <th>Immobilien-ID</th>
            <th>Immobilien-Titel</th>
            <th>Immobilien-URL</th> <!-- Neue Tabellenkopfzeile für die URL -->
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($row['Datum']))); ?></td>
                    <td><?php echo htmlspecialchars($row['ID_Kontakt']); ?></td>
                    <td><?php echo htmlspecialchars($row['fname']); ?></td>
                    <td><?php echo htmlspecialchars($row['lname']); ?></td>
                    <td><?php echo htmlspecialchars($row['mail']); ?></td>
                    <td><?php echo htmlspecialchars($row['ID_Immobilie']); ?></td>
                    <td><?php echo htmlspecialchars($row['Titel_Immobilie']); ?></td>
                    <td>
                        <?php 
                            // Sicherstellen, dass die URL gültig ist
                            if (!empty($row['URL']) && filter_var($row['URL'], FILTER_VALIDATE_URL)) {
                                // URL als klickbarer Link anzeigen
                                echo '<a class="url-link" href="' . htmlspecialchars($row['URL']) . '" target="_blank">' . htmlspecialchars($row['URL']) . '</a>';
                            } else {
                                echo 'Keine URL verfügbar';
                            }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Keine akzeptierten Widerrufsbelehrungen gefunden.</td> <!-- colspan von 7 auf 8 erhöhen -->
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
<?php
// Verbindung schließen
$conn->close();
?>
