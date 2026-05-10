document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");

    if (!form) return;

    const formMessage = document.getElementById("form-message");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const emailField = document.getElementById("email-field");
    const passwordField = document.getElementById("password-field");
    const emailFeedback = document.getElementById("email-feedback");
    const passwordFeedback = document.getElementById("password-feedback");
    const passwordToggle = document.getElementById("password-toggle");
    const validationState = {
        emailTouched: false,
        passwordTouched: false,
    };

    const setFieldState = (field, feedback, status, message) => {
        field.classList.remove("is-valid", "is-invalid");

        if (status) {
            field.classList.add(status);
        }

        if (feedback && message) {
            feedback.textContent = message;
        }
    };

    const validateEmail = () => {
        const value = emailInput.value.trim();
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

        if (!value) {
            if (!validationState.emailTouched) {
                setFieldState(emailField, emailFeedback, null, "Saisissez une adresse e-mail valide.");
                return false;
            }

            setFieldState(emailField, emailFeedback, "is-invalid", "L’adresse e-mail est obligatoire.");
            return false;
        }

        if (!isValid) {
            setFieldState(emailField, emailFeedback, "is-invalid", "Le format de l’adresse e-mail est invalide.");
            return false;
        }

        setFieldState(emailField, emailFeedback, "is-valid", "Adresse e-mail valide.");
        return true;
    };

    const validatePassword = () => {
        const value = passwordInput.value.trim();

        if (!value) {
            if (!validationState.passwordTouched) {
                setFieldState(passwordField, passwordFeedback, null, "Ce champ est requis pour continuer.");
                return false;
            }

            setFieldState(passwordField, passwordFeedback, "is-invalid", "Le mot de passe est obligatoire.");
            return false;
        }

        setFieldState(passwordField, passwordFeedback, "is-valid", "Mot de passe renseigné.");
        return true;
    };

    const markTouched = (fieldName) => {
        validationState[fieldName] = true;
    };

    const togglePasswordVisibility = () => {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        passwordToggle.setAttribute("aria-pressed", String(isPassword));
        passwordToggle.setAttribute("aria-label", isPassword ? "Masquer le mot de passe" : "Afficher le mot de passe");
        passwordToggle.classList.toggle("is-visible", isPassword);
    };

    emailInput.addEventListener("input", validateEmail);
    emailInput.addEventListener("blur", function () {
        markTouched("emailTouched");
        validateEmail();
    });
    passwordInput.addEventListener("input", validatePassword);
    passwordInput.addEventListener("blur", function () {
        markTouched("passwordTouched");
        validatePassword();
    });

    if (passwordToggle) {
        passwordToggle.addEventListener("pointerdown", function (event) {
            event.preventDefault();
        });

        passwordToggle.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();
            togglePasswordVisibility();
        });
    }

    form.addEventListener("submit", function (event) {
        markTouched("emailTouched");
        markTouched("passwordTouched");

        const isValid = validateEmail() && validatePassword();

        if (!isValid) {
            event.preventDefault();

            if (formMessage) {
                formMessage.className = "alert-error visible auth-alert";
                formMessage.textContent = "Veuillez corriger les champs signalés avant de continuer.";
            }

            return;
        }

        if (formMessage) {
            formMessage.className = "alert-success visible auth-alert";
            formMessage.textContent = "Validation réussie. Connexion en cours...";
        }
    });
});