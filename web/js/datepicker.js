$(function () {

    $('.datepicker').datepicker({
        language: 'fr',
        format: 'yyyy-mm-dd',
        startDate: 0,
        todayHighlight: true,
        startDate: new Date(),
        daysOfWeekDisabled: [2],
        datesDisabled: ['01-05-Y', '01-11-Y', '25-12-Y'],
    });
});