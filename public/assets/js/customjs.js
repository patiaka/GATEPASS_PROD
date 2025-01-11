$(function () {
    $(".select2").each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: "Selectionner",
            dropdownParent: $this.parent(),
        });
    });
    var elements = document.querySelectorAll(".flatpickr-date");
    if (elements.length > 0) {
        elements.forEach(function (element) {
            element.flatpickr({
                monthSelectorType: "static",
            });
        });
    }

    var elemts = document.querySelectorAll(".flatpickr-time");

    if (elemts.length > 0) {
        elemts.forEach(function (element) {
            element.flatpickr({
                enableTime: true,
                noCalendar: true,
            });
        });
    }
});

function deleteConfirmation(url) {
    swal.fire({
        title: "Delete?",
        icon: "question",
        text: "Are you sure you want to delete this element?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete!",
        cancelButtonText: "No, Cancel!",
        reverseButtons: true,
    }).then(
        function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: { _token: CSRF_TOKEN },
                    dataType: "JSON",
                    success: function (results) {
                        if (results.success === true) {
                            swal.fire("Done!", results.message, "success");
                            // refresh page after 2 seco nds
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            swal.fire("Error!", results.message, "error");
                        }
                    },
                });
            } else {
                e.dismiss;
            }
        },
        function (dismiss) {
            return false;
        }
    );
}

function restore(url) {
    swal.fire({
        title: "Restaurer?",
        icon: "question",
        text: "Etes vous sur de vouloir restauré cet element!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, Restauré!",
        cancelButtonText: "Non, Annuler!",
        reverseButtons: true,
    }).then(
        function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    type: "GET",
                    url: url,
                    data: { _token: CSRF_TOKEN },
                    dataType: "JSON",
                    success: function (results) {
                        if (results.success === true) {
                            swal.fire("Done!", results.message, "success");
                            // refresh page after 2 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            swal.fire("Error!", results.message, "error");
                        }
                    },
                });
            } else {
                e.dismiss;
            }
        },
        function (dismiss) {
            return false;
        }
    );
}

// boostrap validation js function
(() => {
    "use strict";

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll(".needs-validation");

    // Loop over them and prevent submission
    Array.from(forms).forEach((form) => {
        form.addEventListener(
            "submit",
            (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            },
            false
        );
    });
})();
