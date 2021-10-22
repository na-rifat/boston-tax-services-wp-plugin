<div class="new-message-composer">

    <form action="">
    <div class="close-button">X</div>
        <table class="form-table" >
            <tr>
                <th><label for="to">To</label></th>
                <td><input type="text" name="to" id="to" data-to-id="<?php echo $atts['to_id'] ?>" class="regular-text" value="<?php echo $atts['to'] ?>" disabled></td>
            </tr>
            <tr>
                <th><label for="from">From</label></th>
                <td><input type="text" name="from" id="from" data-from-id="<?php echo $atts['from_id'] ?>"  value="<?php echo $atts['from'] ?>" class="regular-text" disabled></td>
            </tr>
            <tr>
                <th><label for="message_type">Message type</label></th>
                <td><select name="message_type" id="message_type"  class="regular-text">
                    <?php echo $this->message_types2options(); ?>
                </select></td>
            </tr>
            <tr>
                <th><label for="">Message body</label></th>
                <td><textarea name="body" id="body" cols="53" rows="8"></textarea></td>
            </tr>
            <tr>
                <th></th>
                <td><span id="message-sending" class="spinner small-loader"></span><div id="send_message">Send</div></td>
            </tr>
        </table>
        <input type="hidden" name="action" value="compose_message">
        <?php wp_nonce_field( 'compose_message' )?>
    </form>
    <script>
        (function($){
            $(`.close-button`).click(function(e){
                    $(`.new-message-composer`).hide(500, function(){
                    $(this).remove();
                });
            });



        })(jQuery)
    </script>
</div>
<?php exit;?>