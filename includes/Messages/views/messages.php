<?php if ( ! empty( $list ) ) {?>

    <ul class="messages-list">
        <?php foreach ( $list as $message ) {?>
            <li></li>
        <?php } ?>
    </ul>

<?php } else {?>

<div class="no-messages">
    <i class="far fa-bell"></i>
    <div>No messages found!</div>
</div>

<?php } ?>