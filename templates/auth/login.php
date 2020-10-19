<?php
/**
 * @var \System\View\View $this
 */

$db = db();
$auth = auth();

?>

<?php $this->extend('layouts/layout'); ?>

<?php $this->start('style'); ?>
    <style>
        .auth-form {
            display: block;
            position: relative;
            width: 340px;
            margin: 0 auto;
            padding: 10px 20px;
        }

        .auth-form button {
            width: 120px;
            padding: 5px;
        }

        .auth-form-label {
            display: inline-block;
            font-size: 0.9em;
            font-weight: 700;
        }

        .auth-form-input {
            display: block;
            width: 100%;
            padding: 5px;
            border: 2px solid #d4d4d4;
        }

        .auth-form-input:focus {
            border-color: #0389df;
        }

        .auth-form-error {
            color: #f9f9f9;
            font-size: .9em;
            min-height: 18px;
            text-align: center;
        }

        .input-error {
            color: brown !important;
            border-color: #c9302c !important;
        }

        .input-error + .auth-form-error {
            background-color: #c9302c;
            -webkit-box-shadow: rgba(201, 48, 44, 0.1) 0 2px 0;
            box-shadow: rgba(201, 48, 44, 0.1) 0 2px 0;
        }
    </style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
    <h1 class="text-center">Вход на сайт</h1>
    <form class="auth-form shadow-box" method="post" action="<?php echo url('/api/auth/login'); ?>">
        <div>
            <label class="auth-form-label" for="login">Login</label>
            <input class="auth-form-input" id="login" name="login" type="text" autofocus>
            <div class="auth-form-error"></div>
        </div>
        <div class="mb-10">
            <label class="auth-form-label" for="password">Пароль</label>
            <input class="auth-form-input" id="password" name="password" type="password">
            <div class="auth-form-error"></div>
        </div>
        <div class="mb-20 text-center">
            <button class="btn btn-primary" type="submit">Войти</button>
        </div>
        <div class="text-center">
            <a href="<?php echo url('/auth/registration'); ?>">Зарегистрироваться</a>
            &bull;
            <a href="<?php echo url('/auth/repass'); ?>">Восстановить пароль</a>
        </div>
    </form>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
    <script>
      var send,
        form = $('.auth-form'),
        button = form.find('button');

      $('.auth-form-input').on('input', function () {
        var input = $(this);
        if (input.hasClass('input-error')) {
          input.removeClass('input-error').next().text('');
        }
      });

      form.on('submit', function (e) {
        e.preventDefault();
        if (send) {
          return;
        }

        send = $.ajax({
          url: form.attr('action'),
          type: 'post',
          dataType: 'json',
          data: form.serialize(),
          beforeSend: function () {
            button.addClass('spinner');
          },
          complete: function () {
            send = null;
            button.removeClass('spinner');
          },
          error: function (jqXHR) {
            if (jqXHR.status !== 422) {
              return alert('Forbidden!');
            }

            $.each(jqXHR['responseJSON']['errors'], function (key, value) {
              $('input[name='+ key +']').addClass('input-error').next().text(value);
            });

          },
          success: function () {
            window.location.replace('/');
          }
        });
      });

    </script>
<?php $this->stop(); ?>