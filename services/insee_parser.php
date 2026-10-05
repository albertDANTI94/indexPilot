<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);



function parseINSEE($xml) {

    libxml_use_internal_errors(true);

    // Nettoyage sécurité
    $pos = strpos($xml, '<?xml');
    if ($pos !== false) {
        $xml = substr($xml, $pos);
    }

    $xml = trim($xml);

    $xmlObj = simplexml_load_string($xml);

    if ($xmlObj === false) {
        echo "XML LOAD FAILED\n";
        foreach (libxml_get_errors() as $error) {
            echo $error->message . "\n";
        }
        exit;
    }

    // 🔥 IMPORTANT : accéder sans namespace
    $xmlObj->registerXPathNamespace('msg', 'http://www.sdmx.org/resources/sdmxml/schemas/v2_1/message');

    $result = [];

    // 🔥 XPath universel (ignore les namespaces)
    $obsList = $xmlObj->xpath('//Obs');

    if ($obsList === false) {
        echo "❌ XPath failed\n";
        return [];
    }

    foreach ($obsList as $obs) {

        $date = (string)$obs['TIME_PERIOD'];
        $value = (float)$obs['OBS_VALUE'];

        if ($date && $value !== null) {
            $result[] = [
                'date' => $date,
                'value' => $value
            ];
        }
    }

    return $result;
}