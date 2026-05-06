document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("step1Form");

    if(!form) return;

    form.addEventListener("submit", function(e){
        e.preventDefault();

        let error = document.getElementById("error");
        error.innerText = "";

        let nom = document.getElementById("nom").value;
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let genre = document.getElementById("genre").value;

        // Validation JS
        if(nom === "" || email === "" || password === "" || genre === ""){
            error.innerText = "Tous les champs sont obligatoires";
            return;
        }

        fetch("/register-step1", {
            method: "POST",
            body: new FormData(form)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === "error"){
                error.innerText = data.message;
            } else {
                window.location.href = "/register-step2";
            }
        })
        .catch(() => {
            error.innerText = "Erreur serveur";
        });

    });

});