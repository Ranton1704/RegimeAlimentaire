document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("loginForm");

    if(!form) return;

    form.addEventListener("submit", function(e){
        e.preventDefault();

        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let error = document.getElementById("error");

        error.innerText = "";

        // Validation JS
        if(email === "" || password === ""){
            error.innerText = "Tous les champs sont obligatoires";
            return;
        }

        fetch("/login", {
            method: "POST",
            body: new FormData(form)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === "error"){
                error.innerText = data.message;
            } else {
                window.location.href = "/dashboard";
            }
        })
        .catch(() => {
            error.innerText = "Erreur serveur";
        });

    });

});