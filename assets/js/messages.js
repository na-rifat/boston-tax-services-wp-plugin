(function ($) {
    /**
     * Handles messages section
     */
    load_messages($);
})(jQuery);

/**
 * Handles message section
 */
function load_messages($) {
    messagesLoad();
    let data = new FormData();

    data.append(`action`, `get_messages`);
    data.append(`nonce`, boston.messages_nonce);
    // data.append(`action`, `get_messages`);

    $.ajax({
        type: `POST`,
        url: boston.ajaxurl,
        data: data,
        contentType: false,
        processData: false,
        success: (response) => {
            messagesLoad(true);
            $(`.messages-body`).html(response);
        },
        error: (response) => {
            wrong();
        },
    });
}

/**
 * Shows the load screen
 */

function messagesLoad(isended = false) {
    if (!isended) {
        jQuery(`.messages-body`).html(`<div class="loader"></div>`).css({
            display: `flex`,
            justifyContent: `center`,
            alignItems: `center`,
        });
    } else {
        jQuery(`.messages-body`).html(``).css({
            display: `block`,
        });
    }
}
