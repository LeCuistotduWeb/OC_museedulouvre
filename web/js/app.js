$(function() {

  $('.btn-horaires').on( "click", function() {
    $('#horaires').removeClass('hide');
  });
  $('.btn-tarifs').on( "click", function() {
    $('#tarifs').removeClass('hide');
  });
  $('.btn-contact').on( "click", function() {
    $('#contact').removeClass('hide');
  });

  $('#horairesClose').on( "click", function() {
    $('#horaires').addClass('hide');
  });

  $('#tarifsClose').on( "click", function() {
    $('#tarifs').addClass('hide');
  });

  $('#contactClose').on( "click", function() {
    $('#contact').addClass('hide');
  });
});




