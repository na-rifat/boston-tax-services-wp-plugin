<?php $unread = []?>
<?php if ( ! empty( $list ) ) {?>
    <table class="messages-list">
        <?php foreach ( $list as $message ) {?>
<?php $message = std2array( $message )?>
            <tr
            <?php echo $message['status'] == 'unread' ? " class='unread-message'" : ''; ?>>
                <td><img src="<?php echo $this->message_icon( $message['message_type'] ) ?>" alt="<?php _e( ucwords( $message['message_type'] ), 'boston-tax' )?>"></td>
                <td><?php echo $this->pretty_from( $message['from_user_id'] ) ?></td>
                <td><?php echo $message['message_content'] ?></td>
                <td><?php echo date( 'm/d/Y', $message['date'] ) ?></td>
            </tr>
            <?php
                if ( $message['status'] == 'unread' ) {
                    $unread[] = $message['id'];
                }
                ?>
<?php }?>
    </table>

    <div class="span" id="unread-id" data-unread-id="<?php echo htmlspecialchars( serialize( $unread ) ) ?>" style="display: none;"></div>
<script>
    (function($){
        mark_as_read($(`#unread-id`).data(`unread-id`));
    })(jQuery)
</script>
<?php } else {?>

<div class="no-messages">
    <i class="far fa-bell"></i>
    <div>No messages found!</div>
</div>

<?php }?>
