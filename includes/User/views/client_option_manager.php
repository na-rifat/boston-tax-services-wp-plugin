<div class="manage-client">
    <div class="information-holder"
    data-to="<?php echo $atts['profile']['full_name'] ?>"
    data-to-id="<?php echo $atts['profile']['id'] ?>"
    data-from="<?php echo $atts['ai']['tax_expert_name'] ?>"
    data-from-id="<?php echo $atts['ai']['expert_id'] ?>"
    data-client-id="<?php echo $atts['profile']['id'] ?>"
    >
        <div class="close-button manage-client">x</div>
        <h2>Manage client</h2>
        <div class="client-block">
            <div class="avatar-holder">
                <?php echo $atts['profile']['avatar'] ?>
            </div>
            <table class="form-table profile-table">
                <tr>
                    <th><?php _e( 'Name', 'boston-tax' )?></th>
                    <td><?php echo $atts['profile']['full_name'] ?></td>
                </tr>
                <tr>
                    <th><?php _e( 'ID', 'boston-tax' )?></th>
                    <td><?php echo $atts['profile']['id'] ?></td>
                </tr>
                <tr>
                    <th><?php _e( 'Assigned from', 'boston-tax' )?></th>
                    <td><?php echo $this->pretty_print_assign_time( $atts['ai']['assigned_at'] ) ?></td>
                </tr>
            </table>
            <br>
            <div class="send-message-to-client boston-large" data-client-id="<?php echo $atts['profile']['id'] ?>"><div class="small-loader"></div>Send message</div>

        </div>
        <div class="client-block">
            <h4>Files</h4>
            <?php echo $this->files_of_client( $atts['profile']['id'] ) ?>
        </div>
    </div>
    <script>
        /**
         * Manage client close button
         */
        jQuery(`.close-button.manage-client`).click(function (e) {
            jQuery(`.manage-client`).hide(500, function (e) {
                jQuery(this).remove();
            });
        });

        (function($){
            $(`.send-message-to-client`).click(function(e){
                $(this).find(`.small-loader`).addClass(`is-loading`);
                let data_holder = $(`.manage-client .information-holder`);
                let data = {
                    action: `get_composer`,
                    to: data_holder.data(`to`),
                    to_id: data_holder.data(`to-id`),
                    from: data_holder.data(`from`),
                    from_id: data_holder.data(`from-id`)
                }

                $.ajax(
                    {
                        type: `POST`,
                        url: boston.ajaxurl,
                        dataType: false,
                        data:data,
                        success: (res)=>{
                            console.log(res)
                            $(this).find(`.small-loader`).removeClass(`is-loading`);
                            $(`body`).append(res);

                            $(`#send_message`).click(function (e) {
                                e.preventDefault();
                                sendMessage();
                            });
                        },
                        error: ()=>{
                            alert(`Something wrong happen with the server`)
                            $(this).find(`.small-loader`).removeClass(`is-active`);
                        }
                    }
                )
            })    
        })(jQuery)
    </script>
</div>
