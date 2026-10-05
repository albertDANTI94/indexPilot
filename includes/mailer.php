<?php

function sendAlertEmail($to, $idbank, $date, $value) {

    $subject = "📊 Nouvel indice disponible";

    $message = "
    <h2>Nouvelle donnée INSEE</h2>
    <p><strong>Indice :</strong> $idbank</p>
    <p><strong>Date :</strong> $date</p>
    <p><strong>Valeur :</strong> $value</p>
    ";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: AppCalc <no-reply@appcalc.com>\r\n";

    return mail($to, $subject, $message, $headers);
}