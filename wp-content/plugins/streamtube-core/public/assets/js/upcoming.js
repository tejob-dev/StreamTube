(function ($) {
    $.fn.initCountDown = function (options) {
        return this.each(function () {
            var countDown = $(this);
            var settings = $.extend({
                selector: ".countdown.upcoming",
                timeAttribute: "data-options",
                onFinish: function (options) {
                    if ( ! $( 'body' ).hasClass( 'is-embed' ) && countDown.closest('.activity-player').length === 0 ) {
                        window.location.href = window.location.href;
                    } else {
                        countDown.replaceWith('<a target="_blank" href="' + options.url + '" class="text-white btn btn-danger mt-4 px-4"></span>' + options.button + '</a>');
                    }
                }
            }, options);

            var options = $.parseJSON(countDown.attr(settings.timeAttribute));

            countDown.countdown(options.time).on('update.countdown', function (event) {
                var strftime = '';

                strftime += '<div class="count-date">';
                strftime += '<span class="count">%D</span><span class="label">%!D:' + options.day[0] + ',' + options.day[1] + ';</span>';
                strftime += '</div>';

                strftime += '<div class="count-hour">';
                strftime += '<span class="count">%H</span><span class="label">%!H:' + options.hour[0] + ',' + options.hour[1] + ';</span>';
                strftime += '</div>';

                strftime += '<div class="count-minute">';
                strftime += '<span class="count">%M</span><span class="label">%!M:' + options.minute[0] + ',' + options.minute[1] + ';</span>';
                strftime += '</div>';

                strftime += '<div class="count-seconds">';
                strftime += '<span class="count">%S</span><span class="label">%!S:' + options.seconds[0] + ',' + options.seconds[1] + ';</span>';
                strftime += '</div>';

                $(this).html(event.strftime(strftime));
            });

            countDown.countdown(options.time).on('finish.countdown', function (event) {
                settings.onFinish(options);
            });
        });
    };

    $(document).ready(function ($) {
        $('.countdown.upcoming').initCountDown();
    });

})(jQuery);


