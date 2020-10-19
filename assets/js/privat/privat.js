try {
  window.$ = window.jQuery = require('jquery');
  require('../insertAtCaret');
  require('bootstrap-sass');
} catch (e) {}

(function () {
  let pr, file, send_private = true;
  const response = $('#response'),
    textarea = $('#text'),
    form = $('#message-form'),
    modal = $('#modal-upload'),
    smile = $('#offSmile'),
    out = {id: null, vis: null},
    data = response.data('item');

  $('.smile').on('click', 'img', function () {
    textarea.insertAtCaret(this.alt + ' ').focus();
    smile.hide();
  });

  $('#collapsElement').on('click', function (e) {
    e.preventDefault();

    if (smile.is(':visible')) {
      return smile.hide();
    }

    if (smile.html() === '') {
      $.getJSON('/ajax/ancillary/smile.json', (data) => {
        const items = data.map(e => {
          return `<img src="${e.src}" width="${e.width}" height="${e.height}" alt="${e.alt}">`;
        });

        smile.html(`<div class="wrapper-smile">${items.join(' ')}</div>`);
      });
    }
    return smile.show();
  });

  function show () {
    $.ajax({
      url: '/main/privat/show.php',
      type: 'get',
      datatype: 'json',
      data: {privatcod: data.privatcod},
      success (json) {
        if (json.length && 'id' in json[0] && (json[0]['id'] !== out.id || json[0]['vis'] !== out.vis)) {
          out.id = json[0]['id'];
          out.vis = json[0]['vis'];
          const msg = json.reverse().map(m => {
            return `<div class="vis-${+(data['send_id'] !==
              m.user_id)} p-message"><div class="p-message-info-${m['vis']}"><strong>${data['users'][m.user_id]}</strong> <small class="text-muted">(${m.date})</small></div><div>${m.message}</div></div>`;
          });

          response.
            html(msg.join(' ')).
            scrollTop(1000000);
        }
        pr = setTimeout(show, 20000);
      }
    });
  }

  form.on({
    submit (e) {
      e.preventDefault();
      if (textarea.val() !== '' && send_private) {
        $.ajax({
          url: '/ajax/',
          type: 'post',
          dataType: 'json',
          data: {
            cntr: 'Privat',
            action: 'sendPrivate',
            user: data['send_id'],
            privatcod: data.privatcod,
            text: textarea.val()
          },
          beforeSend: function () {
            send_private = false;
            clearTimeout(pr);
          },
          success: function (json) {
            if (json.status === 1) {
              textarea.val('');
              send_private = true;
              return show();
            }
            let message = '<div class="alert alert-danger" role="alert"><p class="lead">К сожалению, Вы не можете отправить сообщение по причине:</p><ul>';
            $.each(json.html, function (key, val) {message += `<li>${val}</li>`;});
            message += '</ul></div>';
            return response.next().next().html(message);
          },
          error: function () {response.next().next().remove();}
        });
      }
    },
    keydown (e) {
      (e.keyCode === 13 && (e.ctrlKey || e.metaKey)) && form.submit();
    }
  });

  $('#file').on('change', function () {
    if (!window.FileReader || !window.FormData) {
      return alert('Ваш браузер слишком старый для этой операции');
    }

    file = this.files[0];
    this.value = null;

    if (file === undefined || file === null) {
      return;
    }

    if (file.type.match('image.*') === null || !/\.(jpe?g|png|gif)$/i.test(file.name)) {
      return alert('Допущены к загрузке только картинки');
    }

    modal.modal('show');
  });

  $('#send-photo').on('click', function (e) {
    e.preventDefault();

    if (file === undefined || file === null) {
      return;
    }

    const data = new FormData();
    data.append('privat', 'image');
    data.append('privat', file);

    modal.modal('hide');

    $.ajax({
      url: '/wupload.php',
      type: 'POST',
      data: data,
      processData: false,
      contentType: false,
      dataType: 'json',
      beforeSend: function () {
        send_private = false;
        response.addClass('mask');
      },
      success: function (json) {
        send_private = true;
        response.removeClass('mask');
        if (!json.status) {
          return alert(json.link);
        }
        const text = modal.find('input[type=text]');
        textarea.insertAtCaret(`${json.link} ${text.val()}`);
        text.val('');
        $('#message-form').submit();
      }
    });
  });

  modal.on('shown.bs.modal', function () {
    const reader = new FileReader();

    reader.addEventListener('load', function () {
      modal.find('img').attr('src', this.result);
    }, false);

    reader.readAsDataURL(file);
  });
  modal.on('hidden.bs.modal', function () {
    const img = modal.find('img');
    img.attr('src', img.data('src'));
    file = null;
  });
  show();
})();