document.addEventListener("DOMContentLoaded", function(){

    const pageRegister = document.querySelector(".page-register");
    const form = document.getElementById("step1Form");
    const formStep2 = document.getElementById("step2Form");
    const error = document.getElementById("error");
    const passwordHint = document.getElementById("passwordHint");
    const passwordToggle = document.getElementById("passwordToggle");
    const passwordFieldShell = document.querySelector(".password-field-shell");
    const passwordInput = document.getElementById("password");
    const storageKey = 'register:step1';

    if(pageRegister){
        pageRegister.classList.add("is-entering");
        window.setTimeout(function(){
            pageRegister.classList.remove("is-entering");
        }, 460);
    }

    if(!form && !formStep2) return;

    // Clear sessionStorage when loading step1 (unless there's an error to show)
    if(form && !error.innerText.trim()){
        try{ sessionStorage.removeItem(storageKey); }catch(e){}
    }

    const getAlertContent = function(alertElement){
        if(!alertElement) return null;
        return alertElement.querySelector(".alert-content") || alertElement;
    };

    const setFieldState = function(input, isValid){
        if(!input) return;
        // prefer to toggle state on the field container for consistent CSS
        const container = input.closest('.field') || input;
        container.classList.remove("is-invalid", "is-valid");
        if(isValid === true){
            container.classList.add("is-valid");
        }
        if(isValid === false){
            container.classList.add("is-invalid");
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

    // storage helpers for multi-step form
    const saveStep1ToStorage = function(){
        try{
            const data = {
                nom: (document.getElementById('nom')||{}).value || '',
                prenom: (document.getElementById('prenom')||{}).value || '',
                email: (document.getElementById('email')||{}).value || '',
                genre: (document.getElementById('genre')||{}).value || '',
                date_naissance: (document.getElementById('date_naissance')||{}).value || ''
            };
            sessionStorage.setItem(storageKey, JSON.stringify(data));
        }catch(err){ /* ignore */ }
    };

    const restoreStep1FromStorage = function(){
        try{
            const raw = sessionStorage.getItem(storageKey);
            if(!raw) return false;
            const data = JSON.parse(raw);
            if(document.getElementById('nom')) document.getElementById('nom').value = data.nom || '';
            if(document.getElementById('prenom')) document.getElementById('prenom').value = data.prenom || '';
            if(document.getElementById('email')) document.getElementById('email').value = data.email || '';
            if(document.getElementById('genre')) document.getElementById('genre').value = data.genre || '';
            if(document.getElementById('date_naissance')) document.getElementById('date_naissance').value = data.date_naissance || '';
            // refresh states
            const evt = new Event('input', { bubbles: true });
            ['nom','prenom','email','password','genre','date_naissance'].forEach(function(id){
                const el = document.getElementById(id);
                if(el) el.dispatchEvent(evt);
            });
            // ensure date overlay updates
            if(dateInput) dateInput.dispatchEvent(new Event('change'));
            return true;
        }catch(e){ return false; }
    };

    if(formStep2){
        formStep2.addEventListener("submit", function(){
            if(pageRegister){
                pageRegister.classList.add("is-leaving");
            }
        });
        // bind previous button if present
        const prevBtn = document.getElementById('prevStepBtn');
        if(prevBtn){
            prevBtn.addEventListener('click', function(e){
                e.preventDefault();
                // navigate back to step1 — values are saved in sessionStorage
                window.location.href = '/register-step1';
            });
        }
    }

    if(passwordToggle){
        passwordToggle.addEventListener("pointerdown", function(e){ e.preventDefault(); });
        passwordToggle.addEventListener("click", function(e){ e.preventDefault(); togglePasswordVisibility(); });
    }

    // date icon focuses the date input
    const dateIcon = document.querySelector('.date-wrap .date-icon');
    const dateInput = document.getElementById('date_naissance');
    if(dateIcon && dateInput){
        dateIcon.addEventListener('click', function(e){ e.preventDefault(); dateInput.focus(); });
    }

    // manage sample placeholder overlay (hide when value present)
    const dateWrap = document.querySelector('.date-wrap');
    if(dateInput && dateWrap){
        const refreshDateState = function(){
            if(dateInput.value && dateInput.value.trim() !== '') dateWrap.classList.add('has-value');
            else dateWrap.classList.remove('has-value');
        };
        dateInput.addEventListener('change', refreshDateState);
        dateInput.addEventListener('input', refreshDateState);
        // initial
        refreshDateState();
    }

    if(!form) return;

    // attempt to restore saved values when returning to step1
    try{ restoreStep1FromStorage(); }catch(e){}

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

    // keep step1 values in storage as the user types, so the previous button is reliable
    ["nom", "prenom", "email", "genre", "date_naissance"].forEach(function(fieldId){
        const field = document.getElementById(fieldId);
        if(!field) return;
        field.addEventListener('input', saveStep1ToStorage);
        field.addEventListener('change', saveStep1ToStorage);
    });
    if(dateInput){
        dateInput.addEventListener('change', saveStep1ToStorage);
    }

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

        // save current values so step2 (or previous) can restore them
        saveStep1ToStorage();
        animateAndSubmit(form);
    });

});