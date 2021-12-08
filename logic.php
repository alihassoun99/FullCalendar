<?php include 'import.php'; ?>

<script>
/*
Global variabls initialisation
example :
*/

let color_value = ''; // globale variable used to change event color

function load_page() {
    // call functions that you want to initialize when loading the page
}


// FullCalendar **************************************************************************************************************
// ***************************************************************************************************************************
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        weekNumbers: true,
        aspectRatio: 1.65,
        // adding a costum button
        customButtons: {
            refresh: {
                text: 'Refresh',
                click: function() {
                    calendar.refetchEvents();
                }
            }
        },
        headerToolbar: {
            left: 'prev,next today refresh',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth,listMonth'
        },
        buttonText: {
            today: "Aujourd'hui",
            day: "Jour",
            week: "Semaine",
            month: "Mois",
            list: "Liste"
        },
        slotMinTime: '04:00:00',    // time limites in the calendar
        slotMaxTime: '22:00:00',
        locale: 'fr', // Langue du calendrier
        selectable: true,
        editable: true,
        selectOverlap: false,
        nowIndicator: true,
        allDaySlot: false,
        eventSources: [
            // your event source
            {
                events: function(info, successCallback, failureCallback) {
                    let start = info.start.toISOString().substring(0, 10);
                    let end = info.end.toISOString().substring(0, 10);
                    initData();
                    fetch("index.php/reservation/load/") // import the data from a function that get the data from the database in JSON form
                        .then(result => result.json())
                        .then(data => {
                            if (data != null) {
                                successCallback(Array.prototype.slice.call( // convert to array                            
                                        data.filter((e) => {

                                            // flexSwitchCheck_mesReservations is used to determine if the events is for the user or not
                                            if (document.getElementById('flexSwitchCheck_mesReservations') == null) {
                                                color_value = '#3366ff';
                                                return true;
                                            } else if (!document.getElementById('flexSwitchCheck_mesReservations').checked){
                                                c = calendar.getEventById(e.id);
                                                if (c != null) {
                                                    if (e.idIndividuBilling == idIndividu) {
                                                        color_value = '#ff7033';
                                                    } else {
                                                        color_value = '#ff7033';
                                                    }
                                                }else{
                                                }
                                                color_value = '#3366ff';
                                                return true;
                                            } else{
                                                c = calendar.getEventById(e.id);
                                                var retoure;
                                                if (c != null) {
                                                    if (e.idIndividuBilling == idIndividu) {
                                                        color_value = '#ff7033';;
                                                        retoure = true;
                                                    } 
                                                    else {
                                                        color_value = '#3366ff';;
                                                        retoure = false;
                                                    }
                                                }
                                                return retoure;
                                            }
                                        })
                                    )
                                )
                            } else {
                                successCallback([])
                            }
                        })
                    },
                    //color: color_value   // an option! // used to specifie a general color for all events
            }

        ],

        select: function(arg) {
            start = (arg.start.toLocaleString('en-US'));
            end = (arg.end.toLocaleString('en-US'));

            // verify if the date of the event is in the past
            let check = new Date(arg.start);
            let today = new Date();
            if (check.valueOf() < today.valueOf()) {
            } else {
                triggerModalEventAdmin(start, end);
            }
        },

        eventClick: function(arg) {
            // execute this code and this function when the user click on a event
            var id = arg.event.id;
            triggerModalModifierEventAdmin(arg);
        },

        eventResize: function(arg) {
            // execute this code and this function when the user resize an event
            resizeFuntion(arg);

        },

        eventDrop: function(arg) {
            // execute this code and this function when the user drop an event
        },

    });

    calendar.render();
});
</script>