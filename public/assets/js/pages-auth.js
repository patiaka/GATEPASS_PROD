"use strict";
const formAuthentication = document.querySelector("#formAuthentication");
document.addEventListener("DOMContentLoaded", function (e) {
    var t;
    formAuthentication &&
        FormValidation.formValidation(formAuthentication, {
            fields: {
                username: {
                    validators: {
                        notEmpty: {
                            message: "Veuillez saisir votre nom d'utilisateur",
                        },
                        stringLength: {
                            min: 6,
                            message:
                                "Le nom d'utilisateur doit comporter plus de 6 caractères",
                        },
                    },
                },
                email: {
                    validators: {
                        notEmpty: {
                            message:
                                "Veuillez entrer une adresse e-mail valide",
                        },
                        emailAddress: {
                            message:
                                "Veuillez entrer une adresse e-mail valide",
                        },
                    },
                },
                "email-username": {
                    validators: {
                        notEmpty: {
                            message:
                                "Veuillez entrer votre email / nom d'utilisateur",
                        },
                        stringLength: {
                            min: 6,
                            message:
                                "Le nom d'utilisateur doit comporter plus de 6 caractères",
                        },
                    },
                },
                password: {
                    validators: {
                        notEmpty: {
                            message:
                                "s'il vous plait entrez votre mot de passe",
                        },
                        stringLength: {
                            min: 6,
                            message:
                                "Le mot de passe doit comporter plus de 6 caractères",
                        },
                    },
                },
                "confirm-password": {
                    validators: {
                        notEmpty: {
                            message: "Veuillez confirmer le mot de passe",
                        },
                        identical: {
                            compare: function () {
                                return formAuthentication.querySelector(
                                    '[name="password"]'
                                ).value;
                            },
                            message:
                                "Le mot de passe et sa confirmation ne sont pas les mêmes",
                        },
                        stringLength: {
                            min: 6,
                            message:
                                "Le mot de passe doit comporter plus de 6 caractères",
                        },
                    },
                },
                terms: {
                    validators: {
                        notEmpty: {
                            message:
                                "Veuillez accepter les termes et conditions",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: "",
                    rowSelector: ".mb-3",
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
            init: (e) => {
                e.on("plugins.message.placed", function (e) {
                    e.element.parentElement.classList.contains("input-group") &&
                        e.element.parentElement.insertAdjacentElement(
                            "afterend",
                            e.messageElement
                        );
                });
            },
        }),
        (t = document.querySelectorAll(".numeral-mask")).length &&
            t.forEach((e) => {
                new Cleave(e, { numeral: !0 });
            });
});
