<?php
/*require_once '../vendor/autoload.php';
require_once '../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

// Replace this endpoint secret with your endpoint's unique secret
// If you are testing with the CLI, find the secret by running 'stripe listen'
// If you are using an endpoint defined with the API or dashboard, look in your webhook settings
// at https://dashboard.stripe.com/webhooks
$endpoint_secret = 'whsec_12345';

$payload = @file_get_contents('php://input');
$event = null;
try {
  $event = \Stripe\Event::constructFrom(
    json_decode($payload, true)
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  echo '⚠️  Webhook error while parsing basic request.';
  http_response_code(400);
  exit();
}
// Handle the event
switch ($event->type) {
  case 'customer.subscription.trial_will_end':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the trial ending.
    // handleTrialWillEnd($subscription);
    break;
  case 'customer.subscription.created':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being created.
    // handleSubscriptionCreated($subscription);
    break;
  case 'customer.subscription.deleted':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being deleted.
    // handleSubscriptionDeleted($subscription);
    break;
  case 'customer.subscription.updated':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being updated.
    // handleSubscriptionUpdated($subscription);
    break;
  case 'entitlements.active_entitlement_summary.updated':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle active entitlement summary updated.
    // handleEntitlementUpdated($subscription);
    break;
  default:
    // Unexpected event type
    echo 'Received unknown event type';
}*/


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/db.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// Signature secret
$endpoint_secret = STRIPE_WEBHOOK_SECRET;

$payload = file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch (Exception $e) {
    http_response_code(400);
    echo "Signature invalide";
    exit;
}

if ($event->type === "checkout.session.completed") {
    $session = $event->data->object;

    // Les metadata contiennent toutes les infos
    $prenom  = $session->metadata->prenom;
    $nom     = $session->metadata->nom;
    $email   = $session->metadata->email;
    $password_hash = $session->metadata->password_hash;

    // Vérifie si l'utilisateur existe déjà
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() === 0) {
        // Création compte utilisateur
        $stmt = $pdo->prepare("
            INSERT INTO users (prenom, nom, email, password, role, abonnement_actif)
            VALUES (?, ?, ?, ?, 'user', 1)
        ");
        $stmt->execute([$prenom, $nom, $email, $password_hash]);
    }
}

http_response_code(200);
