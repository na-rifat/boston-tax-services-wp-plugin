
<?php  if(!empty($profile['id'])){
    ?>
    <div class="admin-row" id="expert_avatar"><?php echo $profile['avatar'] ?></div>
    <table class="form-table" data-expert-id="<?php echo $profile['id'] ?>">
        <tr>
            <th>ID</th>
            <td><div id="expert_id"><?php echo $profile['id'] ?></div></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><div id="expert_name"><?php echo $profile['full_name'] ?></div></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><div id="expert_username"><?php echo $profile['username'] ?></div></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td><div id="expert_email"><?php echo $profile['email'] ?></div></td>
        </tr>
        <tr>
            <th>Current number of clients</th>
            <td><div id="current_clients"><?php echo $profile['current_client_count'] ?></div></td>
        </tr>
    </table>    
    <div class="spinner assign-spinner"></div>
    <div class="action-button" id="assign-expert">Assign</div>

    <script>
        ;(function($){
            $(`#assign-expert`).click(function(e){
            assignExpert();
        });
        })(jQuery);
    </script>
    <?php
}else{
    ?>
    <div class="no-expert">
        Sorry no expert found with associated information!
    </div>
    <?php
}