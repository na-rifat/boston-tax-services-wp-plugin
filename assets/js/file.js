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
    let self;
    let file = $(`#upload_tax_file_from_expert`);

    $(`.upload-client-file`).click(function (e) {
        self = $(this);
        file.trigger(`click`);
    });

    file.on(`change`, function () {
        self.find(`.small-loader`).addClass(`is-loading`);
        let data = {
            action: `upload_file_from_expert`,
            nonce: boston.upload_file_from_expert_nonce,
            folder: self.data(`folder-id`),
            tab: `current-year-taxes`,
            date: new Date().getTime(),
            tax_file: file[0].files[0],
            client_id: $(`.manage-client .information-holder`).data(
                `client-id`
            ),
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
                self.find(`.small-loader`).removeClass(`is-loading`);
                file.val(``);
                $(`.client-files-list`).replaceWith(res);
            },
            error: () => {
                self.find(`.small-loader`).removeClass(`is-loading`);
                file.val(``);
            },
        });
    });
}

function upload_prepared() {
    let $ = jQuery;
    let self;
    let file = $(`#upload_prepared_tax_file`);

    $(`.upload-prepared`).click(function (e) {
        self = $(this);
        file.trigger(`click`);
    });

    file.on(`change`, function () {
        self.find(`.small-loader`).addClass(`is-loading`);
        let data = {
            action: `upload_prepared`,
            nonce: boston.upload_prepared_nonce,
            file_id: self.data(`file-id`),
            file: file[0].files[0],
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
                self.find(`.small-loader`).removeClass(`is-loading`);
                file.val(``);
                $(`.client-files-list`).replaceWith(res);
                console.log(res);
            },
            error: () => {
                self.find(`.small-loader`).removeClass(`is-loading`);
                file.val(``);
            },
        });
    });
}

function get_list_of_client_irs_correspondence() {
    let $ = jQuery;
    let file_holder = $(`.client-irs-correspondence-files`);

    file_holder.html(
        `<div class="small-loader is-loading" style="margin:auto;"></div>`
    );

    let data = {
        action: `get_client_irs_correspondence`,
    };

    $.ajax({
        type: `POST`,
        url: boston.ajaxurl,
        dataType: `json`,
        data: data,
        success: (res) => {
            if (res.success) {
                file_holder.html(res.data.el);
            }            
        },
        error: (res) => {
            alert(`Something went wrong!`);
            file_holder.html(``);
        },
    });
}

function upload_irs_correspondence() {
    let $ = jQuery;
    let self;
    let file = $(`#upload_irs_correspondence`);
    let file_holder = $(`.client-irs-correspondence-files`);


    $(`.irs-uploader`).click(function (e) {
        self = $(this);
        file.trigger(`click`);
    });

    file.on(`change`, function () {
        self.find(`.small-loader`).addClass(`is-loading`);
        let data = {
            action: `upload_irs_document`,
            nonce: boston.upload_irs_document_nonce,
            
            date: new Date().getTime(),
            file: file[0].files[0],
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
                self.find(`.small-loader`).removeClass(`is-loading`);
                file.val(``);                
                file_holder.html(res);
            },
            error: () => {
                self.find(`.small-loader`).removeClass(`is-loading`);
                file.val(``);
            },
        });
    });
}
