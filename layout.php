<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Makler Agreement</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-wrapper">
        <h1>WIDERRUFSBELEHRUNG</h1>
        <div class="details-container">
            <div class="greeting">
                <p id="begrüßung">
                    Hallo
                    <?php
                    // Aufbau des Namens
                    $nameParts = [];
                    if (!empty($interessent['title'])) {
                        $nameParts[] = $interessent['title'];
                    }
                    if (!empty($interessent['fname'])) {
                        $nameParts[] = $interessent['fname'];
                    }
                    if (!empty($interessent['lname'])) {
                        $nameParts[] = $interessent['lname'];
                    }
                    $name = implode(' ', $nameParts);

                    // Aufbau der Informationen in Klammern
                    $infoParts = [];
                    if (!empty($interessent['street'])) {
                        $infoParts[] = $interessent['street'];
                    }
                    if (!empty($interessent['hnr'])) {
                        $infoParts[] = $interessent['hnr'];
                    }
                    if (!empty($interessent['zip'])) {
                        $infoParts[] = $interessent['zip'];
                    }
                    if (!empty($interessent['phone'])) {
                        $infoParts[] = 'Telefon: ' . $interessent['phone'];
                    }
                    if (!empty($infoParts)) {
                        $info = ' (' . implode(', ', $infoParts) . ')';
                    } else {
                        $info = '';
                    }

                    echo htmlspecialchars($name . $info);
                    ?>
                    , willkommen! Vielen Dank für Ihr Interesse an der folgenden Immobilie:
                </p>
            </div>
            <div class="details">
                <?php if (!empty($immobilie['IMG_URL'])): ?>
                    <img src="<?php echo htmlspecialchars($immobilie['IMG_URL']); ?>" alt="Titelfoto der Immobilie" style="max-width: 100%; height: auto;" />
                <?php endif; ?>
                <ul id="immobilienDetails">
                    <li>Titel: <?php echo htmlspecialchars($immobilie['Titel_Immobilie']); ?></li>
                    <li>PLZ und Ort:
                        <?php
                        // Adresse zerlegen, um PLZ und Ort zu extrahieren
                        $adresse = $immobilie['Adresse'];
                        $plzOrt = '';
                        // Annahme: Die Adresse endet mit ", PLZ Ort"
                        $adresseTeile = explode(',', $adresse);
                        if (count($adresseTeile) > 1) {
                            $plzOrtTeil = trim(end($adresseTeile));
                            $plzOrt = htmlspecialchars($plzOrtTeil);
                        }
                        echo $plzOrt;
                        ?>
                    </li>
                    <?php if (!empty($immobilie['Stadtteil'])): ?>
                        <li>Stadtteil: <?php echo htmlspecialchars($immobilie['Stadtteil']); ?></li>
                    <?php endif; ?>
                    <li>Preis: <?php echo htmlspecialchars($immobilie['Preis']); ?></li>
                    <li>Größe: <?php echo htmlspecialchars($immobilie['Größe']); ?></li>
                    <li>Zimmerzahl: <?php echo htmlspecialchars($immobilie['Zimmerzahl']); ?></li>
                </ul>
            </div>
        </div>
        <div>
            <p>
                Bevor wir Ihnen das Exposé zu der gewünschten Immobilie bereitstellen können, möchten wir Sie auf folgende rechtliche Punkte zum Widerruf und Datenschutz aufmerksam machen.
            </p>
        </div>
        <div>
            <p>
                Durch die Inanspruchnahme unserer Dienstleistungen der Immobilienvermittlung kommt ein Maklervertrag zustande. Für den Fall, dass Sie unsere Dienstleistungen als Verbraucher (§ 13 BGB) in Anspruch nehmen möchten, bestätigen Sie Ihre Kenntnisnahme bzw. Ihre Zustimmung bitte durch Anklicken der entsprechenden Optionen.
            </p>
        </div>

        <form id="agreementForm" method="post">
            <div>
                <label><input class="checkbox" type="checkbox" name="terms1" required /> Ich bin von Hausmakler Meyer, Klein Sarau 22, 23627 Groß Sarau über mein gesetzliches Widerrufsrecht informiert worden und habe die Widerrufsbelehrung als Pdf-Datei erhalten.*</label><br />
                <label><input class="checkbox" type="checkbox" name="terms2" required /> Ich stimme der Datenschutzerklärung und einer Kontaktaufnahme durch Hausmakler Meyer per E-Mail oder Telefon für Rückfragen oder zu Informationszwecken zu.*</label><br />
                <label><input class="checkbox" type="checkbox" name="terms3" required /> Ich bin ausdrücklich damit einverstanden, dass Sie bereits vor dem Ende der Widerrufsfrist mit der Ausführung der Dienstleistung, wie die Bereitstellung des Exposés als Teilleistung des zwischen uns zustande kommenden Maklervertrages, beginnen. Das Exposé wird mir sofort zur Verfügung gestellt, wenn ich dem vorzeitigen Beginn zustimme. Dabei ist mir bekannt, dass ich durch dieses Einverständnis das mir gesetzlich zustehende Widerrufsrecht verliere.*</label><br />

            </div>

            <button type="submit" id="submitBtn" disabled>
                Maklervertrag kostenpflichtig abschließen
            </button>
        </form>
        <div class='imp-info'>
            <p>
                Der Maklervertrag kommt dabei unter den in der Immobilienanzeige dargelegten Bedingungen insbesondere der <strong>Provisionspflicht im Erfolgsfall</strong> zustande.
            </p>
            <p>
                Eine Provision wird grundsätzlich nur dann fällig, wenn ein Hauptvertrag zwischen dem Käufer und dem Eigentümer zustande kommt. Im Rahmen des Vertrages senden wir Ihnen kostenlos ähnliche Immobilien-Angebote zu. Sie können diese Einwilligung jederzeit widerrufen.
            </p>
            <p>* Pflichtangabe</p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
