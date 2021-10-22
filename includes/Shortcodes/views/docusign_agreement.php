<form action="" id="agreement-accept-form">
<table>
    <tr>
        <th id="doc-text"><span class="red-text">Docusign contents and function goes here -></span><br><br></th>
    </tr>
    <tr><td><input type="button" value="Next" class="update-wizard" name="agree_to_sign" id="agree_to_sign"></td></tr>
</table>
<?php wp_nonce_field( 'user_agreement_accept' )?>
</form>