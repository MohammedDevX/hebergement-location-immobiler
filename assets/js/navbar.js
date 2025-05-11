document.addEventListener("DOMContentLoaded", function () {
    const profileImageInput = document.getElementById("profileImageInput");
    const profileImage = document.getElementById("profileImage");

    profileImageInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
      // Vérifier que c'est bien une image
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.onload = function (e) {
            // Remplacer l'image placeholder par l'image sélectionnée
                profileImage.src = e.target.result;
            };

            // Lire le fichier comme une URL de données
            reader.readAsDataURL(file);
            } else {
                alert("Veuillez sélectionner un fichier image valide.");
                this.value = ""; // Réinitialiser l'input
            }
        }
    });
});
