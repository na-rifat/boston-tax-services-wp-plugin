<div class="client-profile">
    <div class="avatar-holder">
        <?php echo $profile['avatar'] ?>
    </div>
    <table>
        <tr>
            <td colspan="2"><?php echo $profile['username'] ?></td>
        </tr>
        <tr>
            <td>Current status</td>
            <td><?php echo $profile['current_status'] ?></td>
        </tr>
        <tr>
            <td>Current tax expert</td>
            <td>
                <?php echo $this->expert_name($profile['current_tax_expert']) ?>
            </td>
        </tr>
    </table>
</div>