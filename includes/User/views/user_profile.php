<div class="user-profile settings-section">
    <form action="">
    <div class="row avatar-holder"><div class="center-flex"><?php $this->info( 'avatar' )?></div></div>
    <div class="row name-holder">
        <div class="input-wrapper"><label for="full_name">Name</label><input type="text" placeholder="Your name" name="full_name" id="full_name" <?php $this->val( 'full_name' ) ?> ></div>
        <div class="input-wrapper"><label for="nice_name">Nick name</label><input type="text" placeholder="Nick name" name="nice_name" id="nice_name" <?php $this->val( 'nice_name' ) ?> ></div>        
    </div>
    <div class="row"><div class="input-wrapper"><label for="account_type">Account type: <?php $this->text('account_type') ?> </label></div></div>
    
    </form>
</div>
