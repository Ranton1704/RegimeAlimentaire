document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("step1Form");

    if(!form) return;

    form.addEventListener("submit", function(e){
        let error = document.getElementById("error");
        error.innerText = "";

        let nom = document.getElementById("nom").value;
        let prenom = document.getElementById("prenom").value;
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let genre = document.getElementById("genre").value;
        let dateNaissance = document.getElementById("date_naissance").value;

        // Validation JS
        if(nom === "" || prenom === "" || email === "" || password === "" || genre === "" || dateNaissance === ""){
            e.preventDefault();
            error.innerText = "Tous les champs sont obligatoires";
            return;
        }

    });

});