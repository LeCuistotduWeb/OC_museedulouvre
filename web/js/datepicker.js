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

// jquery datepicker*/
$( function() {
    $( ".datepicker" ).datepicker({
        dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
        minDate: new Date(),
        dateFormat: "yy-mm-dd",
        monthNamesShort: [ "Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec" ],
        monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre" ],
        autoSize: true,
        changeMonth: true,
        changeYear: true,
        showAnim: "slide",
        showOptions: { direction: "up" },
    });
} );
