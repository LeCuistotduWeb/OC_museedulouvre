// bootstrap datepicker*/
// $(function () {
//
//     $('.datepicker').datepicker({
//         language: 'fr',
//         format: 'yyyy-mm-dd',
//         todayHighlight: true,
//         startDate: new Date(),
//         daysOfWeekDisabled: [2],
//         datesDisabled: ['01-05-Y', '01-11-Y', '25-12-Y'],
//     });
// });
/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Keith Wood (kbwood{at}iinet.com.au),
			  Stéphane Nahmani (sholby@sholby.net),
			  Stéphane Raimbault <stephane.raimbault@gmail.com> */
( function( factory ) {
    if ( typeof define === "function" && define.amd ) {

        // AMD. Register as an anonymous module.
        define( [ "../widgets/datepicker" ], factory );
    } else {

        // Browser globals
        factory( jQuery.datepicker );
    }
}( function( datepicker ) {

    datepicker.regional.fr = {
        closeText: "Fermer",
        prevText: "Précédent",
        nextText: "Suivant",
        currentText: "Aujourd'hui",
        monthNames: [ "janvier", "février", "mars", "avril", "mai", "juin",
            "juillet", "août", "septembre", "octobre", "novembre", "décembre" ],
        monthNamesShort: [ "janv.", "févr.", "mars", "avr.", "mai", "juin",
            "juil.", "août", "sept.", "oct.", "nov.", "déc." ],
        dayNames: [ "dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi" ],
        dayNamesShort: [ "dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam." ],
        dayNamesMin: [ "D","L","M","M","J","V","S" ],
        weekHeader: "Sem.",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "" };
    datepicker.setDefaults( datepicker.regional.fr );

    return datepicker.regional.fr;

} ) );

// fonction disable date datepicker
function DisableTuesday(date) {
    var day = date.getDay();
    // If day == 1 then it is tuesday
    if (day == 2) {
        return [false] ;
    } else {
        return [true] ;
    }
}
// jquery datepicker*/
$( function() {
    $( ".datepicker" ).datepicker({
        minDate: new Date(),
        dateFormat: "yy-mm-dd",
        autoSize: true,
        changeMonth: true,
        changeYear: true,
        showAnim: "slide",
        showOptions: { direction: "up" },
        maxDate: "+2Y",
        beforeShowDay: DisableTuesday,
        showButtonPanel: true,
    });
});
