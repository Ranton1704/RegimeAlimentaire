document.addEventListener("DOMContentLoaded", function(){

    const pageRegister = document.querySelector(".page-register");
    const form = document.getElementById("step1Form");
    const formStep2 = document.getElementById("step2Form");
    const error = document.getElementById("error");
    const passwordHint = document.getElementById("passwordHint");
    const passwordToggle = document.getElementById("passwordToggle");
    const passwordFieldShell = document.querySelector(".password-field-shell");
    const passwordInput = document.getElementById("password");

    if(pageRegister){
        pageRegister.classList.add("is-entering");
        window.setTimeout(function(){
            pageRegister.classList.remove("is-entering");
        }, 460);
    }

    if(!form && !formStep2) return;

    const getAlertContent = function(alertElement){
        if(!alertElement) return null;
        return alertElement.querySelector(".alert-content") || alertElement;
    };

    const setFieldState = function(input, isValid){
        if(!input) return;
        input.classList.remove("is-invalid", "is-valid");
        if(isValid === true){
            input.classList.add("is-valid");
        }
        if(isValid === false){
            input.classList.add("is-invalid");
        }
    };

    const updatePasswordHint = function(message, isValid){
        if(!passwordHint) return;
        passwordHint.innerText = message;
        passwordHint.classList.toggle("valid", isValid === true);
    };

    const togglePasswordVisibility = function(){
        if(!passwordInput || !passwordToggle || !passwordFieldShell) return;

        const isVisible = passwordInput.type === "text";
        passwordInput.type = isVisible ? "password" : "text";
        passwordToggle.setAttribute("aria-pressed", String(!isVisible));
        passwordToggle.setAttribute("aria-label", isVisible ? "Afficher le mot de passe" : "Masquer le mot de passe");
        passwordFieldShell.classList.toggle("is-visible", !isVisible);
        passwordInput.focus();
        passwordInput.setSelectionRange(passwordInput.value.length, passwordInput.value.length);
    };

    const isValidEmail = function(value){
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    };

    const isValidPassword = function(value){
        return typeof value === "string" && value.trim().length >= 6;
    };

    const validateStep1Field = function(input){
        if(!input) return true;

        const value = input.value.trim();
        const fieldName = input.getAttribute("name");

        if(value === ""){
            setFieldState(input, false);
            if(fieldName === "password") updatePasswordHint("Le mot de passe est obligatoire.", false);
            return false;
        }

        if(fieldName === "email"){
            const valid = isValidEmail(value);
            setFieldState(input, valid);
            return valid;
        }

        if(fieldName === "password"){
            const valid = isValidPassword(value);
            setFieldState(input, valid);
            updatePasswordHint(valid ? "Format accepté." : "Au moins 6 caractères.", valid);
            return valid;
        }

        if(input.tagName === "SELECT"){
            const valid = value !== "";
            setFieldState(input, valid);
            return valid;
        }

        setFieldState(input, true);
        return true;
    };

    const animateAndSubmit = function(currentForm){
        if(pageRegister){
            pageRegister.classList.add("is-leaving");
            window.setTimeout(function(){
                currentForm.submit();
            }, 220);
            return;
        }
        currentForm.submit();
    };

    if(formStep2){
        formStep2.addEventListener("submit", function(){
            if(pageRegister){
                pageRegister.classList.add("is-leaving");
            }
        });
    }

    if(passwordToggle){
        passwordToggle.addEventListener("click", togglePasswordVisibility);
    }

    if(!form) return;

    ["nom", "prenom", "email", "password", "genre", "date_naissance"].forEach(function(fieldId){
        const field = document.getElementById(fieldId);
        if(!field) return;

        const validate = function(){
            validateStep1Field(field);
            if(error){
                const errorContent = getAlertContent(error);
                if(errorContent && errorContent.innerText.trim() !== ""){
                    errorContent.innerText = "";
                    error.classList.remove("visible");
                }
            }
        };

        field.addEventListener("input", validate);
        field.addEventListener("blur", validate);
    });

    form.addEventListener("submit", function(e){
        e.preventDefault();

        const errorContent = getAlertContent(error);
        if(errorContent) errorContent.innerText = "";
        if(error) error.classList.remove("visible");

        const nom = document.getElementById("nom");
        const prenom = document.getElementById("prenom");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const genre = document.getElementById("genre");
        const dateNaissance = document.getElementById("date_naissance");

        const fields = [nom, prenom, email, password, genre, dateNaissance];
        const allValid = fields.every(function(field){
            return validateStep1Field(field);
        });

        if(!allValid){
            if(errorContent) errorContent.innerText = "Veuillez corriger les champs en rouge.";
            if(error) error.classList.add("visible");
            return;
        }

        animateAndSubmit(form);
    });

});