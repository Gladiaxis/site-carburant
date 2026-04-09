<?php

/**
 * @file
 * @brief Projet de Développement Web
 *
 * @details
 * Ce fichier appartient à un projet universitaire réalisé dans le cadre
 * du module de Développement Web.
 *
 * @author Bijed KHALFOUN
 * @author Adam MOUHLI
 *
 * @version 1.0
 * @date 2025-2026
 *
 * @note L2 Informatique - Groupe C
 * CY Cergy Paris Université
 */

/**
 * Détermine la feuille de style CSS à charger selon le mode d'affichage
 * demandé dans l'URL.
 *
 * Le paramètre GET "style" peut prendre les valeurs "jour" ou "nuit".
 * Si la valeur est absente ou invalide, le mode "jour" est utilisé
 * par défaut.
 *
 * @return string Nom du fichier CSS à charger.
 */
function choisirStyle(): string {
    $style = isset($_GET['style']) ? $_GET['style'] : 'jour';

    if ($style !== 'jour' && $style !== 'nuit') {
        $style = 'jour';
    }

    return ($style === 'nuit') ? 'style_nuit.css' : 'style_jour.css';
}

/**
 * Récupère la liste des films depuis l'API Ghibli au format JSON,
 * sélectionne un film aléatoirement et le retourne sous forme
 * de tableau associatif.
 *
 * En cas d'échec de récupération ou de décodage du flux JSON,
 * l'exécution du script est interrompue avec un message d'erreur.
 *
 * @return array Film sélectionné aléatoirement.
 */
function apiGhibli(): array {

    $apiUrl = "https://ghibliapi.vercel.app/films";
    $json = @file_get_contents($apiUrl);

    if ($json === false) {
        die("Erreur : impossible de récupérer les données de l'API Ghibli.");
    }

    $films = json_decode($json, true);

    if (!is_array($films) || empty($films)) {
        die("Erreur : données JSON invalides ou vides.");
    }

    $indexAleatoire = array_rand($films);
    return $films[$indexAleatoire];
}

/**
 * Récupère les informations de géolocalisation approximative d'une IP
 * via l'API IPinfo au format JSON.
 *
 * @return array Tableau associatif contenant les données de géolocalisation
 *               et un éventuel message d'erreur.
 */
function geoIpInfo(): array {

    $ipVisiteur = $_SERVER['REMOTE_ADDR'];
    $tokenIpinfo = "9ea016c528d89e";
    $apiUrlIpinfo = "https://api.ipinfo.io/lite/" . urlencode($ipVisiteur) . "?token=" . urlencode($tokenIpinfo);

    $jsonIpinfo = @file_get_contents($apiUrlIpinfo);

    $geo = null;
    $erreurGeo = "";

    if ($jsonIpinfo === false) {
        $erreurGeo = "Impossible de récupérer les données de géolocalisation JSON.";
    } else {
        $geo = json_decode($jsonIpinfo, true);

        if (!is_array($geo)) {
            $geo = null;
            $erreurGeo = "Réponse JSON IPinfo invalide.";
        }
    }

    $pays = "Information non disponible";
    $codePays = "Information non disponible";
    $continent = "Information non disponible";
    $codeContinent = "Information non disponible";
    $asn = "Information non disponible";
    $organisation = "Information non disponible";

    if ($geo !== null) {
        if (isset($geo['country'])) {
            $pays = $geo['country'];
        }

        if (isset($geo['country_code'])) {
            $codePays = $geo['country_code'];
        }

        if (isset($geo['continent'])) {
            $continent = $geo['continent'];
        }

        if (isset($geo['continent_code'])) {
            $codeContinent = $geo['continent_code'];
        }

        if (isset($geo['asn'])) {
            $asn = $geo['asn'];
        }

        if (isset($geo['as_name'])) {
            $organisation = $geo['as_name'];
        }
    }

    return [
        'ip' => $ipVisiteur,
        'pays' => $pays,
        'codePays' => $codePays,
        'continent' => $continent,
        'codeContinent' => $codeContinent,
        'asn' => $asn,
        'organisation' => $organisation,
        'erreurGeo' => $erreurGeo
    ];
}

/**
 * Récupère les informations de géolocalisation d'une adresse IP
 * à partir de l'API WhatIsMyIP au format XML.
 *
 * La fonction envoie une requête HTTP à l'API, tente de charger
 * la réponse XML et extrait dynamiquement les champs retournés
 * sous forme de tableau associatif.
 *
 * En cas d'échec lors de la récupération ou du parsing du flux XML,
 * un message d'erreur est renvoyé dans le tableau de résultat.
 *
 * @param string $ipVisiteur Adresse IP à analyser.
 * @return array Tableau associatif contenant :
 *               - 'xml' : l'objet SimpleXMLElement ou null
 *               - 'xmlChamps' : les champs extraits du flux XML
 *               - 'erreurXml' : le message d'erreur éventuel
 */
function geoWhatIsMyIpXml(string $ipVisiteur): array {
    
    $cleXml = "049f430e71428f42c28a1f606aad8ac1";

    $apiUrlXml = "https://api.whatismyip.com/ip-address-lookup.php?key="
        . urlencode($cleXml)
        . "&input=" . urlencode($ipVisiteur)
        . "&output=xml";

    $xmlBrut = @file_get_contents($apiUrlXml);

    $xml = null;
    $erreurXml = "";
    $xmlChamps = array();

    if ($xmlBrut === false) {
        $erreurXml = "Impossible de récupérer le flux XML.";
    } else {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlBrut);

        if ($xml === false) {
            $erreurXml = "Le flux XML reçu est invalide ou vide.";
        } else {
            foreach ($xml->children() as $nomChamp => $valeurChamp) {
                $xmlChamps[$nomChamp] = (string) $valeurChamp;
            }
        }
    }

    return array(
        'xml' => $xml,
        'xmlChamps' => $xmlChamps,
        'erreurXml' => $erreurXml
    );
}

?>