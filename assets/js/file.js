(function ($) {
    $(document).ready(function () {
        get_file_list();
        $(`.upload-button `).click(function (e) {
            e.preventDefault();
            $(`#tax_file`).click();
            $(this).prop("disabled", true);
        });

        $(`#tax_file`).change(function (e) {
            $(`.uploader .small-loader`).addClass(`is-loading`);
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
                    $(`.uploader .small-loader`).removeClass(`is-loading`);
                    $(`#tax_file`).val(``);
                    
                    get_file_list();
                    $(`.upload-button`).prop(`disabled`, false);
                },
                error: (response) => {                    
                    alert("Something went wrong!");
                    $(`#tax_file`).val(``);
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
        .html(`<div class="medium-loader is-loading"></div>`)
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

function upload_client_file() {
    let $ = jQuery;
    let self = $(`.upload-client-file`);
    let file = $(`#upload_tax_file_from_expert`);

    self.click(function (e) {
        file.trigger(`click`);

        file.on(`change`, function () {
            self.find(`.small-loader`).addClass(`is-loading`);
            let data = {
                action: `upload_file_from_expert`,
                nonce: boston.upload_file_from_expert_nonce,
                folder: self.data(`folder-id`),
                tab: `current-year-taxes`,
                date: new Date().getTime(),
                tax_file: file[0].files[0],
            };

            let formattedData = new FormData();

            for (var key in data) {
                formattedData.append(key, data[key]);
            }

            data = formattedData;

            $.ajax({
                type: `POST`,
                url: boston.ajaxurl,
                contentType: false,
                processData: false,
                data: data,
                success: (res) => {   
                    console.log(res)                 
                    self.find(`.small-loader`).removeClass(`is-loading`);
                    file.val(``);
                },
                error: () => {
                    self.find(`.small-loader`).removeClass(`is-loading`);
                    file.val(``);
                },
            });
        });
    });
}
