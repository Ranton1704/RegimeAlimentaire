document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("loginForm");

    if(!form) return;

    form.addEventListener("submit", function(e){
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let error = document.getElementById("error");

        error.innerText = "";

        // Validation JS
        if(email === "" || password === ""){
            e.preventDefault();
            error.innerText = "Tous les champs sont obligatoires";
            return;
        }
    });

});