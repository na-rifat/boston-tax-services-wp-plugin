<a class="button button-large" style="margin: 8px;" href="<?php echo admin_url( "admin.php?page=current-year-clients" ) ?>"><?php _e( '&#171 Back to clients list', 'boston-tax' )?></a>
<hr>
<div class="manage-user-holder"
        data-client-id="<?php echo $profile_info['id'] ?>"
        data-client-name="<?php echo $profile_info['full_name'] ?>"        
        data-tax-expert-id="<?php echo $assigned_info['expert_id'] ?>"
        data-tax-expert-name="<?php echo $assigned_info['tax_expert_name'] ?>"
         >
    <h2><?php _e( 'Manage client', 'boston-tax' )?></h2>
    <div class="admin-row"><?php echo $profile_info['avatar'] ?></div>
    <table class="form-table">
        <tr>
            <th><span>Actions</span></th>
            <td>
                <span class="spinner message"></span>
                <span id="send_message_from_admin" class="action-button"><?php _e( 'Send message', 'boston-tax' )?></span>
                <span class="spinner assign-tax-expert-spinner"></span>
                <span id="assign_tax_expert" class="action-button"><?php _e( 'Assign tax expert', 'boston-tax' )?></span>
            </td>
        </tr>
        <tr>
            <th><span><?php _e( 'Client name', 'boston-tax' )?></span></th>
            <td><span><?php echo $profile_info['full_name'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Client ID', 'boston-tax' )?></span></th>
            <td><span><?php echo $profile_info['id'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Assigned preparer', 'boston-tax' )?></span></th>
            <td><span><?php echo $assigned_info['tax_expert_name'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Assigned preparer ID' )?></span></th>
            <td><span><?php echo $assigned_info['expert_id'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Amount billed', 'boston-tax' )?></span></th>
            <td><span><?php echo $assigned_info['amount_billed'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Date billed', 'boston-tax' )?></span></th>
            <td><span><?php echo $assigned_info['date_billed'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Amount paid', 'boston-tax' )?></span></th>
            <td><span><?php echo $assigned_info['amount_paid'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Date paid', 'boston-tax' )?></span></th>
            <td><span><?php echo $assigned_info['date_paid'] ?></span></td>
        </tr>
        <tr>
            <th><span><?php _e( 'Filed', 'boston-tax' )?></span></th>
            <td><span><?php echo $assigned_info['filed'] ?></span></td>
        </tr>
    </table>
</div>