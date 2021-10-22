(function ($) {
    /**
     * Handles messages section
     */
    load_messages($);

    $(`#send_message`).click(function (e) {
        e.preventDefault();
        sendMessage();
    });

    show_unread_count();
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
        jQuery(`.messages-body`)
            .html(`<div class="medium-loader is-loading"></div>`)
            .css({
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

/**
 * Handles message functions
 */
function sendMessage() {
    let $ = jQuery;

    $(`#message-sending`).addClass(`is-active is-loading`);

    let data = {
        to: jQuery(`#to`).val(),
        to_id: jQuery(`#to`).data(`to-id`),
        from: jQuery(`#from`).val(),
        from_id: jQuery(`#from`).data(`from-id`),
        message_type: jQuery(`#message_type`).val(),
        body: jQuery(`#body`).val(),
        nonce: jQuery(`#_wpnonce`).val(),
        action: `send_message_from_ajax`,
    };

    jQuery.ajax({
        type: `POST`,
        url: boston.ajaxurl,
        data: data,
        dataType: `json`,
        success: (response) => {
            $(`.new-message-composer`).hide(500, function () {
                $(this).remove();
            });
        },
        error: () => {
            $(`.new-message-composer`).hide(500, function () {
                $(this).remove();
            });
            wrong();
        },
    });
}

function show_unread_count() {
    let $ = jQuery;

    if ($(`.unread-count`).length > 0) {
        ajax_unread_count();

        setInterval(() => {
            ajax_unread_count();
        }, 10000);
    }
}

function ajax_unread_count() {
    let $ = jQuery;
    $.ajax({
        type: `POST`,
        url: boston.ajaxurl,
        dataType: `json`,
        data: {
            action: `get_unread_count`,
        },
        success: (response) => {
            if (response.success) {
                $(`.unread-count`).text(
                    format_unread_count(response.data.count)
                );
            }
        },
        error: () => {
            alert(`Something went wrong!`);
        },
    });
}

function mark_as_read(unread_id) {
    let $ = jQuery;
    let data = {
        unread_id: unread_id,
        action: `mark_as_read`,
        nonce: boston.mark_as_read_nonce,
    };

    $.ajax({
        type: `POST`,
        url: boston.ajaxurl,
        dataType: `json`,
        data: data,
        success: (res) => {                        
            $(`.unread-count`).text(format_unread_count(res.data.count));
        
        },
        error: () => {
            alert(`Something went wrong!`);
        },
    });
}

function format_unread_count(count) {
    if (count > 10) {
        return `9+`;
    } else {
        return count;
    }
}
