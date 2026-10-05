document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".profileForm");

    form.addEventListener("submit", (e) => {
        let errors = [];

        const prenom = form.querySelector("input[name='prenom']").value.trim();
        const nom = form.querySelector("input[name='nom']").value.trim();
        const password = form.querySelector("input[name='password']").value;
        const confirm = form.querySelector("input[name='confirm']").value;

        // Vérification prénom et nom
        if (prenom === "") errors.push("Le prénom est obligatoire.");
        if (nom === "") errors.push("Le nom est obligatoire.");

        // Vérification mot de passe (facultatif)
        if (password !== "" && password.length < 8) {
            errors.push("Le mot de passe doit contenir au moins 8 caractères.");
        }

        if (password !== "" && password !== confirm) {
            errors.push("Les mots de passe ne correspondent pas.");
        }

        // Si erreurs, on empêche l'envoi et on affiche les messages
        if (errors.length > 0) {
            e.preventDefault();

            // Supprimer messages existants
            const oldFlash = document.querySelector(".flash-message.js");
            if (oldFlash) oldFlash.remove();

            // Créer un nouveau conteneur pour les erreurs
            const flash = document.createElement("div");
            flash.classList.add("flash-message", "error", "js");
            flash.innerHTML = errors.join("<br>");
            form.parentNode.insertBefore(flash, form);
        }
    });
});