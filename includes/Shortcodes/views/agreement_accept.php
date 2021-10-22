<?php 
    if(!get_user_meta(get_current_user_id(), 'agreement_accept_step', true)){            
?>
<form action="" id="agreement-accept-form">
<table>
    <tr>
        <th id="doc-text"><span class="red-text">Will you like us to represent you on any Tax issue?</span><br><br></th>
    </tr>
    <tr><td><input type="button" value="Yes" class="update-wizard" name="agree_to_sign" id="agree_to_sign"><input type="button" value="No" name="not_agree_to_sign" id="not_agree_to_sign"></td></tr>
</table>
<?php wp_nonce_field( 'user_agreement_accept' )?>
</form>
<?php 
    }else{ ?>
<form action="" id="agreement-accept-form">
<table>
    <tbody><tr>
        <th id="doc-text"><span class="blue-text">Thank you for opening an account with us, we shall send you important tax information in our newsletter.</span>
            <br>
            <span class="red-text">If you do not want to be part of our newsletter, click no below.</span>
            </th>        
    </tr>
    <tr><td><input type="button" value="No"  class="update-wizard" name="not_agree_to_sign" id="delete_from_newsletter"></td></tr>    
</tbody></table>
<?php wp_nonce_field( 'user_agreement_accept' )?>
</form>
<?php } ?>