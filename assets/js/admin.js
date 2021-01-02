(function ($) {
    $(document).ready(function () {
        /**
         * Handles bostons settings ajax request
         */
        $(`#boston-options form`).on(`submit`, function (e) {
            e.preventDefault();

            let self = $(this);
            let bostonOptions = self.serialize();

            bostonWait();

            $.ajax({
                type: `POST`,
                url: boston.ajaxurl,
                data: bostonOptions,
                dataType: `json`,
                success: function (response) {
                    bostonWait(false);
                },
                error: function (error) {
                    bostonWait(false);
                    alert(`Something went wrong`);
                },
            });
        });

        /**
         * Handles user dashboards shortcodes ajax request
         */

        $(`#user-dashboard-settings form`).on(`submit`, function (e) {
            e.preventDefault();

            let self = $(this);
            let shortcodes = self.serialize();

            bostonWait();

            $.ajax({
                type: `POST`,
                url: boston.ajaxurl,
                data: shortcodes,
                dataType: `json`,
                success: function (response) {
                    bostonWait(false);
                },
                error: function (response) {
                    bostonWait(false);
                    alert(`Something went wrong`);
                },
            });
        });
    });
})(jQuery);
function bostonWait(show = true) {
    if (show) {
        jQuery(`.boston-save-load`).html("<span class='spinner is-active'></span>");
    } else {
        jQuery(`.boston-save-load`).html(``);
    }
}
