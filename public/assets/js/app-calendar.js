/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 *
 **/

"use strict";

document.addEventListener("DOMContentLoaded", function () {
    (function () {
        toastr.options = {
            closeButton: true,
            newestOnTop: false,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
        };
        const calendarEl = document.getElementById("calendar"),
            appCalendarSidebar = document.querySelector(
                ".app-calendar-sidebar"
            ),
            addEventSidebar = document.getElementById("addEventSidebar"),
            appOverlay = document.querySelector(".app-overlay"),
            calendarsColor = {
                Business: "primary",
                Personal: "danger",
                Family: "warning",
            },
            offcanvasTitle = document.querySelector(".offcanvas-title"),
            btnToggleSidebar = document.querySelector(".btn-toggle-sidebar"),
            btnSubmitEventForm = document.querySelector(
                '#eventForm button[type="submit"]'
            ),
            btnSubmitExportForm = document.querySelector(
                '#exportForm button[type="submit"]'
            ),
            btnDeleteEvent = document.querySelector(".btn-delete-event"),
            btnCancel = document.querySelector(".btn-cancel"),
            eventupdate_id = document.querySelector("#update_id"),
            eventStartDate = document.querySelector("#eventStartDate"),
            eventEndDate = document.querySelector("#eventEndDate"),
            type = $("#type"), // ! Using jquery vars due to select2 jQuery dependency
            matiere_id = $("#matiere_id"), // ! Using jquery vars due to select2 jQuery dependency
            classe_id = $("#classe_id"), // ! Using jquery vars due to select2 jQuery dependency
            teacher_id = $("#teacher_id"), // ! Using jquery vars due to select2 jQuery dependency,
            periode_id = $("#periode_id"), // ! Using jquery vars due to select2 jQuery dependency,
            selectAll = document.querySelector(".select-all"),
            filterInput = Array.from(
                document.querySelectorAll(".input-filter")
            ),
            inlineCalendar = document.querySelector(".inline-calendar");
        // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
        let eventToUpdate,
            currentEvents = events,
            isFormValid = false,
            inlineCalInstance;

        // Init event Offcanvas
        const bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);

        //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
        // Event teacher_id (select2)
        if (teacher_id.length) {
            function renderBadges(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge =
                    "<span class='badge badge-dot bg-" +
                    $(option.element).data("label") +
                    " me-2'> " +
                    "</span>" +
                    option.text;

                return $badge;
            }
            teacher_id.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select value",
                dropdownParent: teacher_id.parent(),
                templateResult: renderBadges,
                templateSelection: renderBadges,
                minimumResultsForSearch: -1,
                escapeMarkup: function (es) {
                    return es;
                },
            });
        }

        // Event matiere_id (select2)
        if (matiere_id.length) {
            function renderBadges(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge =
                    "<span class='badge badge-dot bg-" +
                    $(option.element).data("label") +
                    " me-2'> " +
                    "</span>" +
                    option.text;

                return $badge;
            }
            matiere_id.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select value",
                dropdownParent: matiere_id.parent(),
                templateResult: renderBadges,
                templateSelection: renderBadges,
                minimumResultsForSearch: -1,
                escapeMarkup: function (es) {
                    return es;
                },
            });
        }

        // Event classe_id (select2)
        if (classe_id.length) {
            function renderBadges(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge =
                    "<span class='badge badge-dot bg-" +
                    $(option.element).data("label") +
                    " me-2'> " +
                    "</span>" +
                    option.text;

                return $badge;
            }
            classe_id.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select value",
                dropdownParent: classe_id.parent(),
                templateResult: renderBadges,
                templateSelection: renderBadges,
                minimumResultsForSearch: -1,
                escapeMarkup: function (es) {
                    return es;
                },
            });
        }

        // Event classe_id (select2)
        if (type.length) {
            function renderBadges(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge =
                    "<span class='badge badge-dot bg-" +
                    $(option.element).data("label") +
                    " me-2'> " +
                    "</span>" +
                    option.text;

                return $badge;
            }
            type.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select value",
                dropdownParent: type.parent(),
                templateResult: renderBadges,
                templateSelection: renderBadges,
                minimumResultsForSearch: -1,
                escapeMarkup: function (es) {
                    return es;
                },
            });
        }

        // Event start (flatpicker)
        if (eventStartDate) {
            var start = eventStartDate.flatpickr({
                enableTime: true,
                altFormat: "Y-m-dTH:i:S",
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute("step", null);
                    }
                },
            });
        }

        // Event end (flatpicker)
        if (eventEndDate) {
            var end = eventEndDate.flatpickr({
                enableTime: true,
                altFormat: "Y-m-dTH:i:S",
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute("step", null);
                    }
                },
            });
        }

        // Inline sidebar calendar (flatpicker)
        if (inlineCalendar) {
            inlineCalInstance = inlineCalendar.flatpickr({
                monthSelectorType: "static",
                inline: true,
            });
        }

        // Event click function
        function eventClick(info) {
            eventToUpdate = info.event;
            // console.log(eventToUpdate);
            axios
                .get(`http://127.0.0.1:8000/api/planning/${eventToUpdate.id}`)
                .then(function (response) {
                    eventupdate_id.value = response.data.id;
                    type.val(response.data.type).trigger("change");
                    classe_id.val(response.data.classe_id).trigger("change");
                    matiere_id.val(response.data.matiere_id).trigger("change");
                    teacher_id.val(response.data.teacher_id).trigger("change");
                })
                .catch(function (error) {
                    console.error(error);
                });
            bsAddEventSidebar.show();
            // For update event set offcanvas title text: Update Event
            if (offcanvasTitle) {
                offcanvasTitle.innerHTML = "Formulaire de mise à jour";
            }
            btnSubmitEventForm.innerHTML = "Mettre à jour";
            btnSubmitEventForm.classList.add("btn-update-event");
            btnSubmitEventForm.classList.remove("btn-add-event");
            btnDeleteEvent.classList.remove("d-none");
            start.setDate(eventToUpdate.start, true, "Y-m-dTH:i");
            eventToUpdate.end !== null
                ? end.setDate(eventToUpdate.end, true, "Y-m-d")
                : end.setDate(eventToUpdate.start, true, "Y-m-d");
        }

        // Modify sidebar toggler
        function modifyToggler() {
            const fcSidebarToggleButton = document.querySelector(
                ".fc-sidebarToggle-button"
            );
            fcSidebarToggleButton.classList.remove("fc-button-primary");
            fcSidebarToggleButton.classList.add(
                "d-lg-none",
                "d-inline-block",
                "ps-0"
            );
            while (fcSidebarToggleButton.firstChild) {
                fcSidebarToggleButton.firstChild.remove();
            }
            fcSidebarToggleButton.setAttribute("data-bs-toggle", "sidebar");
            fcSidebarToggleButton.setAttribute("data-overlay", "");
            fcSidebarToggleButton.setAttribute(
                "data-target",
                "#app-calendar-sidebar"
            );
            fcSidebarToggleButton.insertAdjacentHTML(
                "beforeend",
                '<i class="bx bx-menu bx-sm text-heading"></i>'
            );
        }

        // Filter events by calender
        function selectedCalendars() {
            let selected = [],
                filterInputChecked = [].slice.call(
                    document.querySelectorAll(".input-filter:checked")
                );

            filterInputChecked.forEach((item) => {
                selected.push(item.getAttribute("data-value"));
            });

            return selected;
        }

        // --------------------------------------------------------------------------------------------------
        // AXIOS: fetchEvents
        // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
        // --------------------------------------------------------------------------------------------------
        function fetchEvents(info, successCallback) {
            // Fetch Events from API endpoint reference
            let calendars = selectedCalendars();
            // We are reading event object from app-calendar-events.js file directly by including that file above app-calendar file.
            // You should make an API call, look into above commented API call for reference
            let selectedEvents = currentEvents.filter(function (event) {
                // console.log(event.extendedProps.calendar.toLowerCase());
                return calendars.includes(
                    event.extendedProps.calendar.toLowerCase()
                );
            });
            // if (selectedEvents.length > 0) {
            successCallback(selectedEvents);
            // }
        }
        // Init FullCalendar
        // ------------------------------------------------
        let calendar = new Calendar(calendarEl, {
            initialView: "dayGridMonth",
            events: fetchEvents,
            plugins: [
                dayGridPlugin,
                interactionPlugin,
                listPlugin,
                timegridPlugin,
            ],
            editable: true,
            dragScroll: true,
            dayMaxEvents: 2,
            eventTimeFormat: {
                hour: "2-digit",
                minute: "2-digit",
                meridiem: false,
            },
            // slotMinTime: "6:00:00",
            // slotMaxTime: "21:00:00",
            timeZone: "UTC",
            locale: "fr",
            buttonText: {
                month: "Mois",
                week: "Semaine",
                day: "Jour",
                list: "liste",
            },
            eventResizableFromStart: true,
            customButtons: {
                sidebarToggle: {
                    text: "Sidebar",
                },
            },
            headerToolbar: {
                start: "sidebarToggle, prev,next, title",
                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
            },
            direction: "ltr",
            initialDate: new Date(),
            navLinks: true, // can click day/week names to navigate views
            eventClassNames: function ({ event: calendarEvent }) {
                const colorName =
                    calendarsColor[calendarEvent._def.extendedProps.calendar];
                // Background Color
                return ["fc-event-" + colorName];
            },
            dateClick: function (info) {
                let date = moment(info.date).format("YYYY-MM-DD");
                resetValues();
                bsAddEventSidebar.show();

                // For new event set offcanvas title text: Add Event
                if (offcanvasTitle) {
                    offcanvasTitle.innerHTML = "Nouveau";
                }
                btnSubmitEventForm.innerHTML = "Valider";
                btnSubmitEventForm.classList.remove("btn-update-event");
                btnSubmitEventForm.classList.add("btn-add-event");
                btnDeleteEvent.classList.add("d-none");
                // eventStartDate.value = date;
                // eventEndDate.value = date;
            },
            eventClick: function (info) {
                eventClick(info);
            },
            datesSet: function () {
                modifyToggler();
            },
            viewDidMount: function () {
                modifyToggler();
            },
        });

        // Render calendar
        calendar.render();
        // Modify sidebar toggler
        modifyToggler();

        const eventForm = document.getElementById("eventForm");
        const fv = FormValidation.formValidation(eventForm, {
            fields: {
                eventStartDate: {
                    validators: {
                        notEmpty: {
                            message: "Entrez la date de debut",
                        },
                    },
                },
                eventEndDate: {
                    validators: {
                        notEmpty: {
                            message: "Entrez la date de fin",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    eleValidClass: "",
                    rowSelector: function (field, ele) {
                        // field is the field name & ele is the field element
                        return ".mb-3";
                    },
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
        })
            .on("core.form.valid", function () {
                // Jump to the next step when all fields in the current step are valid
                isFormValid = true;
            })
            .on("core.form.invalid", function () {
                // if fields are invalid
                isFormValid = false;
            });

        // Sidebar Toggle Btn
        if (btnToggleSidebar) {
            btnToggleSidebar.addEventListener("click", (e) => {
                btnCancel.classList.remove("d-none");
            });
        }

        // Update Event
        // ------------------------------------------------
        function updateEvent(eventData, eventsArray) {
            // Assuming you have the updated data in the eventData object

            axios
                .put(
                    "http://127.0.0.1:8000/api/planning/" + update_id.value,
                    eventData
                )
                .then(function (response) {
                    // Update existing event data in the provided eventsArray and refetch it to display on the calendar
                    console.log(response);
                    if (response.status === 200) {
                        calendar.refetchEvents();
                        // Reload the page to fetch the latest events from the server
                        toastr.success("Event mis à jour avec succès");
                        window.location.reload();
                    }
                })
                .catch(function (error) {
                    console.error(error);
                    // Handle errors here
                    toastr.error(
                        "Erreur lors de la mise à jour de l'événement"
                    );
                });
        }

        // Remove Event
        // ------------------------------------------------

        function removeEvent(eventId) {
            // ? Delete existing event data to current events object and refetch it to display on calender
            // ? You can write below code to AJAX call success response
            currentEvents = currentEvents.filter(function (event) {
                return event.id != eventId;
            });
            calendar.refetchEvents();

            // ? To delete event directly to calender (won't update currentEvents object)
            // removeEventInCalendar(eventId);
        }

        // Remove Event In Calendar (UI Only)
        // ------------------------------------------------
        function removeEventInCalendar(eventId) {
            calendar.getEventById(eventId).remove();
        }

        // Add new event
        // ------------------------------------------------
        btnSubmitEventForm.addEventListener("click", (e) => {
            if (btnSubmitEventForm.classList.contains("btn-add-event")) {
                if (isFormValid) {
                    let newEvent = {
                        debut: eventStartDate.value,
                        fin: eventEndDate.value,
                        type: type.val(),
                        teacher_id: teacher_id.val(),
                        classe_id: classe_id.val(),
                        matiere_id: matiere_id.val(),
                        periode_id: periode_id.val(),
                    };
                    addEvent(newEvent);
                    bsAddEventSidebar.hide();
                }
            } else {
                // Update event
                // ------------------------------------------------
                if (isFormValid) {
                    let eventData = {
                        // id: update_id.value,
                        debut: eventStartDate.value,
                        fin: eventEndDate.value,
                        type: type.val(),
                        teacher_id: teacher_id.val(),
                        classe_id: classe_id.val(),
                        matiere_id: matiere_id.val(),
                        periode_id: periode_id.val(),
                    };
                    updateEvent(eventData, currentEvents);
                    bsAddEventSidebar.hide();
                }
            }
        });

        // Add Event
        // ------------------------------------------------
        function addEvent(eventData) {
            axios
                .post("http://127.0.0.1:8000/api/planning", eventData)
                .then(function (response) {
                    if (response.status === 200) {
                        toastr.success("Event ajouté avec success");
                        // Reload the page to fetch the latest events from the server
                        window.location.reload();
                    }
                })
                .catch(function (error) {
                    toastr.error(
                        "La validation a échoué, vérifiez vos informations!"
                    );
                    console.error(error);
                });
        }

        // Call removeEvent function
        btnDeleteEvent.addEventListener("click", (e) => {
            var CSRF_TOKEN = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            axios
                .delete(
                    "http://127.0.0.1:8000/api/planning/" + eventToUpdate.id,
                    {
                        headers: {
                            "X-CSRF-TOKEN": CSRF_TOKEN,
                        },
                    }
                )
                .then(function (response) {
                    removeEvent(parseInt(eventToUpdate.id));
                    toastr.success("Event supprimé avec succès");
                })
                .catch(function (error) {
                    toastr.error("Operation echoué");
                    console.error(error);
                });

            // eventToUpdate.remove();
            bsAddEventSidebar.hide();
        });

        // Reset event form inputs values
        // ------------------------------------------------
        function resetValues() {
            eventEndDate.value = "";
            eventStartDate.value = "";
            type.val("").trigger("change");
            matiere_id.val("").trigger("change");
            classe_id.val("").trigger("change");
            teacher_id.val("").trigger("change");
        }

        // When modal hides reset input values
        addEventSidebar.addEventListener("hidden.bs.offcanvas", function () {
            resetValues();
        });

        // Hide left sidebar if the right sidebar is open
        btnToggleSidebar.addEventListener("click", (e) => {
            if (offcanvasTitle) {
                offcanvasTitle.innerHTML = "Ajouter";
            }
            btnSubmitEventForm.innerHTML = "Ajouter";
            btnSubmitEventForm.classList.remove("btn-update-event");
            btnSubmitEventForm.classList.add("btn-add-event");
            btnDeleteEvent.classList.add("d-none");
            appCalendarSidebar.classList.remove("show");
            appOverlay.classList.remove("show");
        });

        // Calender filter functionality
        // ------------------------------------------------
        if (selectAll) {
            selectAll.addEventListener("click", (e) => {
                if (e.currentTarget.checked) {
                    document
                        .querySelectorAll(".input-filter")
                        .forEach((c) => (c.checked = 1));
                } else {
                    document
                        .querySelectorAll(".input-filter")
                        .forEach((c) => (c.checked = 0));
                }
                calendar.refetchEvents();
            });
        }

        if (filterInput) {
            filterInput.forEach((item) => {
                item.addEventListener("click", () => {
                    const allFilters =
                        document.querySelectorAll(".input-filter");
                    const checkedFilters = document.querySelectorAll(
                        ".input-filter:checked"
                    );
                    selectAll.checked =
                        checkedFilters.length === allFilters.length;
                    calendar.refetchEvents();
                });
            });
        }

        // Jump to date on sidebar(inline) calendar change
        inlineCalInstance.config.onChange.push(function (date) {
            calendar.changeView(
                calendar.view.type,
                moment(date[0]).format("YYYY-MM-DD")
            );
            modifyToggler();
            appCalendarSidebar.classList.remove("show");
            appOverlay.classList.remove("show");
        });
    })();
});
