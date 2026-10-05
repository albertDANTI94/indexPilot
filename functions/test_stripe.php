<?php
require __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey("REDACTED_TEST_KEY");

try {
    $prod = \Stripe\Product::all(['limit' => 1]);
    var_dump($prod);
} catch(Exception $e) {
    echo $e->getMessage();
}
