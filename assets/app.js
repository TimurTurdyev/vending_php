(function ($) {
    'use strict';

    $('#message').modal('show');

    $('[data-autosubmit]').on('change', function () {
        this.form.submit();
    });

    $('form[data-confirm]').on('submit', function (event) {
        if (!window.confirm(this.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    });

    // 18+: спрашиваем подтверждение и передаем его в форме покупки
    $('form[data-adult]').on('submit', function (event) {
        var field = this.querySelector('[name="confirm_age"]');

        if (field.value) {
            return;
        }

        event.preventDefault();

        if (window.confirm(this.getAttribute('data-adult') + ' продается только с 18 лет. Вам исполнилось 18?')) {
            field.value = '1';
            this.submit();
        }
    });

    // занятый аппарат: считаем секунды и обновляем страницу, когда он освободится
    $('[data-countdown]').each(function () {
        var element = this;
        var seconds = parseInt(element.getAttribute('data-countdown'), 10);

        var timer = window.setInterval(function () {
            seconds -= 1;
            element.textContent = Math.max(seconds, 0);

            if (seconds <= 0) {
                window.clearInterval(timer);
                window.location.reload();
            }
        }, 1000);
    });
})(jQuery);
