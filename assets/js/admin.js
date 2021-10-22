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

        /**
         * Admin message functions
         */
        $(`#send_message_from_admin`).click(function (e) {
            e.preventDefault();
            let data_holder = $(`.manage-user-holder`);
            let data = new FormData();

            waitForComposer(0);

            data.append(`action`, `get_composer`);
            data.append(`to`, data_holder.data(`client-name`));
            data.append(`to_id`, data_holder.data(`client-id`));
            data.append(`from`, `admin`);
            data.append(`from_id`, `admin`);

            $.ajax({
                type: `POST`,
                url: boston.ajaxurl,
                contentType: false,
                processData: false,
                data: data,
                success: (response) => {
                    waitForComposer(0, true);
                    $(`#wpbody-content`).append(response);
                    $(`.new-message-composer`).hide().show(500);

                    $(`#send_message`).click(function (e) {
                        e.preventDefault();
                        sendMessage();
                    });
                },
                error: (res) => {
                    waitForComposer(0, true);
                },
            });
        });

        /**
         * Tax expert assigner
         */
        $(`#assign_tax_expert`).click(function (e) {
            waitForComposer(1);

            let data_holder = $(`.manage-user-holder`);
            let data = {
                action: `get_tax_expert_assigner`,
                client_id: data_holder.data(`client-id`),
                expert_id: data_holder.data(`tax-expert-id`),
            };

            $.ajax({
                type: `POST`,
                url: boston.ajaxurl,
                dataType: `json`,
                data: data,
                success: (res) => {
                    waitForComposer(1, true);
                    if (res.success) {
                        $(`#wpbody-content`).append(res.data.element);
                        $(`.assign-tax-expert`).hide(0, function () {
                            $(this).show(500);
                        });
                    }
                },
                error: (err) => {
                    waitForComposer(1, true);
                    wrong();
                },
            });
        });
    });
})(jQuery);
function bostonWait(show = true) {
    if (show) {
        jQuery(`.boston-save-load`).html(
            "<span class='spinner is-active'></span>"
        );
    } else {
        jQuery(`.boston-save-load`).html(``);
    }
}

function wp_content_shadow(remove = false) {
    $ = jQuery;

    if (!remove) {
        $(`#wpbody-content`).css({
            backgroundColor: `rgba(160, 160, 160, 0.5)`,
        });
    } else {
        $(`#wpbody-content`).css({
            backgroundColor: `transparent`,
        });
    }
}

function waitForComposer(button, remove = false) {
    let el;
    if (button == 0) {
        el = jQuery(`.spinner.message`);
    } else {
        el = jQuery(`.spinner.assign-tax-expert-spinner`);
    }
    if (!remove) {
        el.addClass(`is-active`);
    } else {
        el.removeClass(`is-active`);
    }
}

function get_expert_profile() {
    let expert_id = $(`#expert_id`).val();

    $.ajax({
        type: `POST`,
        url: boston.ajaxurl,
        dataType: `json`,
        data: {
            action: `get_tax_expert_info`,
            expert_id: expert_id,
        },
        success: (res) => {
            if (res.data.status) {
                $(`.expert-information-profile`).html(res.data.profile);
            }
        },
        error: () => {
            alert(`Something went wrong!`);
        },
    });
}

function assignExpert() {
    let $ = jQuery;

    $(`.spinner.assign-spinner`).addClass(`is-active`);

    let client_data_holder = $(`.client-information`);
    let expert_data_holder = $(`.expert-information-profile table`);

    let data = {
        action: `assign_expert`,
        nonce: boston.assign_tax_expert_nonce,
        expert_id: expert_data_holder.data(`expert-id`),
        client_id: client_data_holder.data(`client-id`),
    };

    $.ajax({
        type: `POST`,
        url: boston.ajaxurl,
        dataType: `json`,
        data: data,
        success: (res) => {
            $(`.spinner.assign-spinner`).removeClass(`is-active`);
            $(`.assign-tax-expert`).hide(500, function (e) {
                $(this).remove();
            });
            if (res.success) {
            }
        },
        error: () => {
            $(`.spinner.assign-spinner`).removeClass(`is-active`);
            alert(`Something went wrong!`);
        },
    });
}
