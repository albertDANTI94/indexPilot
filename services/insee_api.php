<?php

/*function callINSEE($idbank) {
    $url = "https://api.insee.fr/series/BDM/V1/data/SERIES_BDM/$idbank";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // certificat Let's Encrypt (celui que tu utilises dÃ©jÃ)
    curl_setopt($ch, CURLOPT_SSLCERT, "/etc/letsencrypt/live/appcalc.ozratopia.com/fullchain.pem");
    curl_setopt($ch, CURLOPT_SSLKEY, "/etc/letsencrypt/live/appcalc.ozratopia.com/privkey.pem");

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception("Erreur CURL : " . curl_error($ch));
    }

    curl_close($ch);

    return $response;
}*/

/*function fetchSerieINSEE($idbank) {

    $url = "https://api.insee.fr/series/BDM/V1/data/SERIES_BDM/$idbank";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSLCERT => "/etc/letsencrypt/live/appcalc.ozratopia.com/fullchain.pem",
        CURLOPT_SSLKEY => "/etc/letsencrypt/live/appcalc.ozratopia.com/privkey.pem",
        CURLOPT_HTTPHEADER => [
            'Accept: application/xml'
        ]
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    curl_close($ch);

    return $response;
}*/

/*function fetchSerieINSEE($idbank) {

    $url = "https://bdm.insee.fr/series/sdmx/data/SERIES_BDM/$idbank";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        // CURLOPT_HEADER => true,
        CURLOPT_VERBOSE => true
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        die("cURL ERROR: " . curl_error($ch));
    }

    $info = curl_getinfo($ch);

    echo "HTTP CODE: " . $info['http_code'] . "\n";

    curl_close($ch);

    return $response;
}*/

function fetchSerieINSEE($idbank) {

    $url = "https://bdm.insee.fr/series/sdmx/data/SERIES_BDM/$idbank";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'Accept: application/xml'
        ],
    ]);

    $response = curl_exec($ch);
    


    if ($response === false) {
        die("❌ CURL ERROR: " . curl_error($ch));
    }
    
    //var_dump($response);
   // exit;


    curl_close($ch);

    // 🔥 Nettoyage CRUCIAL
    $response = trim($response);

    // 🔥 Coupe tout avant le XML
    $pos = strpos($response, '<?xml');
    if ($pos !== false) {
        $response = substr($response, $pos);
    }

    return $response;
}