(function ($) {
    $(document).ready(function () {
        get_file_list();
        $(`.upload-button `).click(function (e) {
            e.preventDefault();
            $(`#tax_file`).click();
            $(this).prop("disabled", true);
        });

        $(`#tax_file`).change(function (e) {
            $(`.uploader .loader`).css({ display: "inline-block" });
            let file = $(`#tax_file`)[0].files[0];
            let tab = jQuery(`#active-tab`).data("tab-name");
            let folder = $(`#active-folder .folder-title`).data(`folder-index`);

            let data = new FormData();
            data.append(`tax_file`, file);
            data.append(`action`, `upload_tax_file`);
            data.append(`tab`, tab);
            data.append(`folder`, folder);
            data.append(`date`, new Date().getTime());
            data.append(`filenonce`, boston.filenonce);

            $.ajax({
                url: boston.ajaxurl,
                type: `POST`,
                data: data,
                contentType: false,
                processData: false,
                success: (response) => {
                    console.clear();
                    console.log(response);

                    $(`.uploader .loader`).css({ display: "none" });
                    $(`#tax_file`).val(``);

                    get_file_list();
                    $(`.upload-button`).prop(`disabled`, false);
                },
                error: (response) => {
                    console.clear();
                    console.log(response);
                    alert("");
                },
            });
        });

        $(`.folder`).click(function () {
            $(`#active-folder`).removeAttr(`id`);
            $(this).attr(`id`, `active-folder`);
            
            get_file_list();           
        });
    });
})(jQuery);
function get_file_list() {
    // if (boston.current_page != boston.wizard_page) {
    //     return;
    // }

    jQuery(`.file-list`)
        .html(`<div class="loader"></div>`)
        .addClass(`file-list-with-loader`);
    let tab = jQuery(`#active-tab`).data("tab-name");
    let folder = jQuery(`#active-folder .folder-title`).data(`folder-index`);
    let data = new FormData();
    data.append(`action`, `boston_get_file_list`);
    data.append(`folder`, folder);
    data.append(`tab`, tab);

    jQuery.ajax({
        url: boston.ajaxurl,
        type: `POST`,
        data: data,
        contentType: false,
        processData: false,
        success: (response) => {
            jQuery(`.file-list`)
                .html(response)
                .removeClass(`file-list-with-loader`);
        },
        error: () => {},
    });
}
