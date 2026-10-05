// In production, this should check CSRF, and not pass the session ID.
// The customer ID for the portal should be pulled from the
// authenticated user on the server.
/*document.addEventListener('DOMContentLoaded', async () => {
  let searchParams = new URLSearchParams(window.location.search);
  if (searchParams.has('session_id')) {
    const session_id = searchParams.get('session_id');
    document.getElementById('session-id').setAttribute('value', session_id);
  }
});*/

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        const response = await fetch("../functions/register_and_checkout.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (!data.success) {
            alert("Erreur : " + data.message);
            return;
        }

        // Redirection Stripe Checkout
        window.location.href = data.url;
    });
});
