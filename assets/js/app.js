window.$ = window.jQuery = require('jquery');

$.ajaxSetup({
  headers : {
    'X-Key': document.querySelector('meta[name="csrf-key"]').content
  }
});


$('[data-toggle]').on('click', function (e) {
  e.preventDefault();
  $(`#${$(this).data('toggle')}`).toggleClass('hidden');
});
