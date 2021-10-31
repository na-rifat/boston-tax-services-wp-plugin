(function ($) {
    $(document).ready(function () {
        /**
         * Initializes file management function
         */
        docInit(jQuery);

        /**
         * Handles input styles
         */
        let inputElem = $(`.um .um-form input[type=text],
                            .um .um-form input[type=search],
                            .um .um-form input[type=tel],
                            .um .um-form input[type=number],
                            .um .um-form input[type=password],
                            .um .um-form textarea,
                            .um .upload-progress,
                            .select2-container .select2-choice,
                            .select2-drop,
                            .select2-container-multi .select2-choices,
                            .select2-drop-active,
                            .select2-drop.select2-drop-above`);

        let prevStyle =
            $(inputElem).attr(`style`) == undefined
                ? ""
                : $(inputElem).attr(`style`);

        $(inputElem)
            .attr(
                `style`,
                prevStyle +
                `border-top: none !important;
                    border-left: none !important;
                    border-right: none !important;
                    `
            )
            .on(`focus`, function (e) {
                $(this).addClass(`modern-input`);
            })
            .on(`focusout`, function (e) {
                $(this).removeClass(`modern-input`);
            });

        /**
         * Calendly functions
         */

        $(`.meeting-scheduler`).click(function (e) {
            Calendly.initPopupWidget({
                url: "https://calendly.com/nuraalamrifat/15min",
            });
            return false;
        });

        window.addEventListener("message", function (e) {
            if (isCalendlyEvent(e)) {
                console.log(e.data.event);
                // calendly.event_scheduled

                // jQuery(`iframe[src="https://calendly.com/nuraalamrifat/15min?embed_domain=localhost&embed_type=PopupText"]`).contents().find(`div[data-id="details-highlighted-item"]`)
                if (e.data.event == `calendly.event_scheduled`) {
                    /**
                     * Successfully scheduled an meeting, now let the server know
                     */
                    let params = {
                        type: `POST`,
                        url: boston.ajaxurl,
                        dataType: `json`,
                        data: {
                            action: `process_calendly`,
                            nonce: boston.calendly_nonce,
                        },
                        success: (response) => {
                            if (response.success) {
                                // alert(`Your schedule processed successfully!`)
                            }
                        },
                        error: (response) => {
                            wrong();
                        },
                    };
                    $.ajax(params);
                }
            }
        });

        /**
         * Load client manage option
         */

        $(`.manage-client-from-expert`).click(function (e) {
            let self = $(this);

            loadSpin(self.parent().find(`.small-loader`));

            let client_id = self.data(`client-id`);

            let data = {
                action: `client_option_manager`,
                nonce: boston.client_option_manager_nonce,
                client_id: client_id,
            };

            $.ajax({
                type: `POST`,
                url: boston.ajaxurl,
                dataType: `json`,
                data: data,
                success: (res) => {
                    loadSpin(self.parent().find(`.small-loader`), true);
                    console.log(res);
                    if (res.success) {
                        $(`body`).append(res.data.element);
                        $(`.manage-client`).hide(0, function (e) {
                            $(this).show(500);
                        });
                    }
                },
                error: () => {
                    $(`body`).append(res.data.element);
                    alert(
                        `Something wrong happen with server or your internet connection!`
                    );
                },
            });
        });

        checkUncheck();
    });
})(jQuery);
function isCalendlyEvent(e) {
    return e.data.event && e.data.event.indexOf("calendly") === 0;
}

//Social login functions
jQuery(document).ready(function ($) {
    xy_days();
    ("use strict");

    window.submitRedirect = false;
    $(document).on("click", ".submit-redirect", function () {
        window.submitRedirect = true;
    });

    $(".social-login a").on("click", function (e) {
        e.preventDefault();
        window.open(
            boston.boston_sc[$(this).attr("class")],
            "",
            "scrollbars=no,menubar=no,height=500,width=900,resizable=yes,toolbar=no,status=no"
        );
    });
});

(function ($) { })(jQuery);

/**
 * Counts remaining days till the next april
 */

function xy_days() {
    let el = jQuery(`#xy-days`);
    let now = new Date();
    let till = new Date();
    let oneDay = 24 * 60 * 60 * 1000;

    if (till.getMonth() >= 3) {
        till.setFullYear(till.getFullYear() + 1);
    }

    till.setMonth(3);
    till.setDate(1);

    let xyDays = Math.round(Math.abs((now - till) / oneDay));
    el.text(xyDays);
}

/**
 * Alerts if failure message
 *
 * Uses for ajax fails
 */
function wrong() {
    alert(`Something went wrong!`);
}

/**
 * Collects nonce for file manager
 */
function get_nonce() {
    return {
        _wpnonce: jQuery(`input[name="_wpnonce"]`).val(),
        _wp_http_referer: jQuery(`input[name="_wp_http_referer"]`).val(),
    };
}

/**
 * Handles and inits files management functions
 *
 * @param {mixed} $
 */
function docInit($) {
    /**
     * Adds to newsletter
     */
    $(`#not_agree_to_sign`).click(function (e) {
        e.preventDefault();
        wizardLoad();

        let data = {
            action: `user_agreement_accept`,
            agree: `no`,
            update_boston_wizard_step: `true`,
            _wpnonce: get_nonce()._wpnonce,
            _wp_http_referer: get_nonce()._wp_http_referer,
        };

        $.ajax({
            url: boston.ajaxurl,
            type: `POST`,
            data: data,
            dataType: `json`,
            success: (response) => {
                wizardLoad(true);
                if (response.success) {
                    $(`#doc-text`).html(
                        `<span class="blue-text">Thank you for opening an account with us, we shall send you important tax information in our newsletter.</span>
                        <br>
                        <span class="red-text">If you do not want to be part of our newsletter, click no below.</span>
                        `
                    );
                    $(`#agree_to_sign`).remove();
                    $(`#not_agree_to_sign`).attr(
                        `id`,
                        "delete_from_newsletter"
                    );
                    $(`input[name="_wpnonce"]`).val(response.data.new_nonce);
                }

                docInit($);
            },
            error: (response) => {
                wizardLoad(true);
                wrong();
            },
        });
    });

    /**
     * Deletes user account
     */
    $(`#delete_from_newsletter`).click(function (e) {
        e.preventDefault();

        wizardLoad();

        let data = {
            action: `user_agreement_accept`,
            agree: `delete`,
            update_boston_wizard_step: `true`,
            _wpnonce: get_nonce()._wpnonce,
            _wp_http_referer: get_nonce()._wp_http_referer,
        };

        $.ajax({
            url: boston.ajaxurl,
            type: `POST`,
            data: data,
            dataType: `json`,
            success: (response) => {
                if (response.data.deleted) {
                    window.location.href = boston.siteurl;
                }
            },
            error: (response) => {
                wrong();
            },
        });
    });

    /**
     * Agreement function ended
     */

    /**
     * wizard update function
     */
    $(document).on(`wpcf7mailsent`, function (event) {

        let formId = event.detail.contactFormId;

        if (
            formId != boston.wizard_form_id_1 &&
            formId != boston.wizard_form_id_2 &&
            formId != boston.wizard_form_id_3
        )
            return;

        wizardLoad();

        let data = new FormData();
        data.append(`action`, `update_wizard`);
        data.append(`nonce`, boston.wizard_nonce);
        data.append(`form_id`, formId);

        let param = {
            url: boston.ajaxurl,
            type: `POST`,
            processData: false,
            contentType: false,
            data: data,
            success: (response) => {
                if (formId == boston.wizard_form_id_3) {
                    window.location.reload();
                }
                wizardLoad(true);
                $(`#wizard-layout, #wizard-layout`).html(response);
                docInit($);
            },
            error: (response) => {
                wizardLoad(true);
                wrong();
            },
        };

        $.ajax(param);
    });

    $(`.update-wizard`).on(`click`, function (event) {
        wizardLoad();
        let data = new FormData();
        data.append(`action`, `update_wizard`);
        data.append(`nonce`, boston.wizard_nonce);

        let param = {
            url: boston.ajaxurl,
            type: `POST`,
            processData: false,
            contentType: false,
            data: data,
            success: (response) => {
                wizardLoad(true);
                $(`#wizard-layout, #wizard-layout`).html(response);
                docInit($);
            },
            error: (response) => {
                wizardLoad(true);
                wrong();
            },
        };

        $.ajax(param);
    });
}

/**
 * shows loading animation
 * @param {*} isended
 */
function wizardLoad(isended = false) {
    let el = jQuery(`#wizard-layout`);
    if (!isended) {
        el.html(`<div class="loader"></div>`).css({
            display: `flex`,
            justifyContent: `center`,
            alignItems: `center`,
        });
    } else {
        el.css({ display: `inline-block` });
    }
}

/**
 * Shows or hides the loading spin
 * @param {object} elem
 * @param {boolean} end
 */
function loadSpin(elem, end = false) {
    if (!end) {
        elem.css({
            display: `inline-block`,
        });
    } else {
        elem.css({
            display: `none`,
        });
    }
}



/**
 * Make checkbox behaviors like radio button
 */
function checkUncheck() {
    let $ = jQuery;
    let checkBoxes = $(`.wizard-qa-form .wpcf7-checkbox input[type="checkbox"]`);

    checkBoxes.on(`click`, function (e) {
        $(this).parents(`.wpcf7-checkbox`).find(`input[type="checkbox"]`).not($(this)).prop('checked', false)
    })
}