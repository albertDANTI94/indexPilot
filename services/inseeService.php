<?php

function getInseeToken() {
    $client_id = "TON_CLIENT_ID";
    $client_secret = "TON_CLIENT_SECRET";

    $ch = curl_init("https://api.insee.fr/token");

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    return $data['access_token'] ?? null;
}

function fetchSerie($idbank, $token) {
    $url = "https://api.insee.fr/series/BDM/V1/data/$idbank";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Accept: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}