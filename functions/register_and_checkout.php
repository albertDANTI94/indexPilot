<?php
// functions/register_and_checkout.php
/*require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../vendor/autoload.php'; // composer autoload

\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET'));
$domain = rtrim(getenv('BASE_URL'), '/');

header('Content-Type: application/json');

try {
    // Récupère POST (sanitize côté front)
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$prenom || !$nom || !$email || !$password || $password !== $confirm) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Données invalides']);
        exit;
    }

    // Vérifier si email déjà utilisé
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email déjà existant']);
        exit;
    }

    // Insérer utilisateur en status pending
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $pending_token = bin2hex(random_bytes(32));

    $ins = $pdo->prepare("INSERT INTO utilisateurs (prenom, nom, email, password, status, pending_token, created_at)
                         VALUES (:prenom, :nom, :email, :password, 'pending', :token, NOW())");
    $ins->execute([
        'prenom' => $prenom,
        'nom' => $nom,
        'email' => $email,
        'password' => $hashed,
        'token' => $pending_token
    ]);
    $userId = $pdo->lastInsertId();

    // Créer la session Checkout Stripe
    $priceId = 'price_xxx'; // ID Stripe Price pour le plan/produit (ou amount si paiement unique)
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'mode' => 'subscription', // ou 'payment' si paiement unique
        'line_items' => [[ 'price' => $priceId, 'quantity' => 1 ]],
        'customer_email' => $email, // pratique pour pré-remplir
        'client_reference_id' => (string)$userId,
        'metadata' => [
            'user_id' => (string)$userId,
            'pending_token' => $pending_token
        ],
        'success_url' => $domain . '/public/success.html?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $domain . '/public/cancel.html',
    ]);

    echo json_encode(['success' => true, 'sessionId' => $session->id, 'checkoutUrl' => $session->url]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}*/


header('Content-Type: application/json'); 

// --------------------------
// Chargement Stripe + config
// --------------------------
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../includes/config.php';



\Stripe\Stripe::setApiKey($STRIPE_SECRET_KEY);   // ← CORRECTION ICI

// --------------------------
// Validation données
// --------------------------
$required = ["prenom", "nom", "email", "password"];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode([
            "success" => false,
            "message" => "Le champ $field est manquant."
        ]);
        exit;
    }
}

$prenom   = htmlspecialchars(trim($_POST["prenom"]));
$nom      = htmlspecialchars(trim($_POST["nom"]));
$email    = htmlspecialchars(trim($_POST["email"]));
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// --------------------------
// Création du client Stripe
// --------------------------
try {
    $customer = \Stripe\Customer::create([
        "email" => $email,
        "name"  => "$prenom $nom",
        "metadata" => [
            "prenom" => $prenom,
            "nom"    => $nom,
            "email"  => $email
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}

// --------------------------
// Création session Checkout
// --------------------------
try {
    $session = \Stripe\Checkout\Session::create([
        "mode" => "subscription",
        "payment_method_types" => ["card"],
        "customer" => $customer->id,

        "line_items" => [[
            "price"    => $STRIPE_PRICE_ID,   // ← CORRECTION ICI
            "quantity" => 1
        ]],

        "success_url" => "https://appcalc.lelabdal.com/public/success.html?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url"  => "https://appcalc.lelabdal.com/public/cancel.html",

        "metadata" => [
            "prenom" => $prenom,
            "nom" => $nom,
            "email" => $email
        ]
    ]);

    echo json_encode([
        "success" => true,
        "url" => $session->url,
        "sessionId" => $session->id
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}



