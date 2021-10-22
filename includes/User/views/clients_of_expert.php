<?php if ( ! empty( $clients ) ) {?>
    <table class="clients-of-expert"> 
    <tr>
    <th></th>
    <th>Client name</th>
    <th>ID</th>
    <th>Action</th>
    </tr>
    <?php
    foreach ( $clients as $client ) {
    ?>
            <tr>
                <td>
                    <?php echo $client['profile']['avatar'] ?>
                </td>
                <td>
                    <?php echo $client['profile']['full_name'] ?>
                </td>
                <td>
                    <?php echo $client['profile']['id'] ?>
                </td>
                <td>                    
                    <div class="small-loader"></div>
                    <div class="boston-large  manage-client-from-expert" data-client-id="<?php echo $client['profile']['id'] ?>">Manage</div>
                </td>
            </tr>

    <?php
    }
        ?> </table><?php
    } else {
    ?>
<div class="no-clients-found">
    <?php _e( 'No clients found!', 'boston-tax' )?>
</div>

<?php
}?>