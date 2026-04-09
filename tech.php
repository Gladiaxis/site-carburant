<?php
require_once('include/functions.inc.php');
$style = choisirStyle();
$film = apiGhibli();
$ipJson = geoIpInfo();
$ipXml = geoWhatIsMyIpXml($ipJson['ip']);

$lienAutreFilm = 'tech.php';
if (isset($_GET['style']) && ($_GET['style'] === 'jour' || $_GET['style'] === 'nuit')) {
    $lienAutreFilm .= '?style=' . urlencode($_GET['style']);
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Page technique - API JSON et XML</title>
        <link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($style, ENT_QUOTES, 'UTF-8'); ?>">
    </head>
    <body>
        <div id="contenu">
            <h1>Page technique</h1>

            <div class="bloc-film">
                <h2>API Ghibli - Extraction depuis un flux JSON</h2>

                <div class="film-informations">
                    <div class="film-ligne">
                        <span class="film-label">Titre :</span>
                        <span class="film-valeur">
                            <?php echo htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>

                    <div class="film-ligne">
                        <span class="film-label">Titre japonais :</span>
                        <span class="film-valeur" lang="ja">
                            <?php echo htmlspecialchars($film['original_title'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>

                    <div class="film-ligne">
                        <span class="film-label">Année de sortie :</span>
                        <span class="film-valeur">
                            <?php echo htmlspecialchars($film['release_date'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                </div>

                <div class="film-description">
                    <p><strong>Description :</strong></p>
                    <p>
                        <?php echo htmlspecialchars($film['description'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>

                <div class="images-film">
                    <div class="image-bloc">
                        <img
                            src="<?php echo htmlspecialchars($film['image'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="Affiche du film <?php echo htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8'); ?>"
                            width="300">
                        <p class="legende">Affiche officielle du film</p>
                    </div>

                    <div class="image-bloc">
                        <img
                            src="<?php echo htmlspecialchars($film['movie_banner'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="Bannière du film <?php echo htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8'); ?>"
                            width="500">
                        <p class="legende">Bannière promotionnelle du film</p>
                    </div>
                </div>

                <div class="film-action">
                    <p>
                        <a href="<?php echo htmlspecialchars($lienAutreFilm, ENT_QUOTES, 'UTF-8'); ?>">Afficher un autre film</a>
                    </p>
                </div>
            </div>

            <hr>

            <div class="bloc-geolocalisation">
                <h2>API IPinfo - Extraction depuis un flux JSON</h2>

                <div class="geo-introduction">
                    <p>
                        Cette localisation est une estimation obtenue à partir de l’adresse IP du visiteur.
                    </p>
                </div>

                <?php if (empty($ipJson['erreurGeo'])) { ?>
                    <div class="geo-informations">
                        <div class="geo-ligne">
                            <span class="geo-label">Adresse IP détectée :</span>
                            <span class="geo-valeur">
                                <?php echo htmlspecialchars($ipJson['ip'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                        <div class="geo-ligne">
                            <span class="geo-label">Pays :</span>
                            <span class="geo-valeur">
                                <?php echo htmlspecialchars($ipJson['pays'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                        <div class="geo-ligne">
                            <span class="geo-label">Code pays :</span>
                            <span class="geo-valeur">
                                <?php echo htmlspecialchars($ipJson['codePays'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                        <div class="geo-ligne">
                            <span class="geo-label">Continent :</span>
                            <span class="geo-valeur">
                                <?php echo htmlspecialchars($ipJson['continent'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                        <div class="geo-ligne">
                            <span class="geo-label">Code continent :</span>
                            <span class="geo-valeur">
                                <?php echo htmlspecialchars($ipJson['codeContinent'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                        <div class="geo-ligne">
                            <span class="geo-label">ASN :</span>
                            <span class="geo-valeur">
                                <?php echo htmlspecialchars($ipJson['asn'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                        <div class="geo-ligne">
                            <span class="geo-label">Organisation réseau :</span>
                            <span class="geo-valeur">
                                <?php echo htmlspecialchars($ipJson['organisation'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="geo-erreur">
                        <p>
                            <?php echo htmlspecialchars($ipJson['erreurGeo'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                <?php } ?>
            </div>

            <hr>

            <div class="bloc-xml">
                <h2>API WhatIsMyIP - Extraction depuis un flux XML</h2>

                <div class="xml-introduction">
                    <p>
                        Cette section illustre la lecture d’un flux XML à partir d’une autre API.
                    </p>
                    <p>
                        L’adresse IP utilisée est :
                        <strong><?php echo htmlspecialchars($ipJson['ip'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    </p>
                </div>

                <?php if ($ipXml['xml'] !== null) { ?>
                    <div class="xml-informations">
                        <?php if (!empty($ipXml['xmlChamps'])) { ?>
                            <?php foreach ($ipXml['xmlChamps'] as $nomChamp => $valeurChamp) { ?>
                                <div class="xml-ligne">
                                    <span class="xml-label">
                                        <?php echo htmlspecialchars($nomChamp, ENT_QUOTES, 'UTF-8'); ?> :
                                    </span>
                                    <span class="xml-valeur">
                                        <?php echo htmlspecialchars($valeurChamp, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="xml-erreur">
                                <p>
                                    Le flux XML a été lu, mais aucun champ exploitable n’a été trouvé.
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="xml-erreur">
                        <p>
                            <?php echo htmlspecialchars($ipXml['erreurXml'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <p>
                            Vérifie que la clé API XML est valide.
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>