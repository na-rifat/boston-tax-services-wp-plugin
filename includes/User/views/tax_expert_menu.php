<div class="dashboard-left-panel">
<?php echo $profile ?>
    <div class="user-dashboard-menu">
        <ul>
            <li><a data-tab-name="clients" href="<?php boston_user_tab( 'clients' )?>"<?php echo boston_active_tab( 'clients' ) ?>><i class="fas fa-users"></i>Clients</a></li>
            <li><a data-tab-name="invoices" href="<?php boston_user_tab( 'invoices' )?>"<?php echo boston_active_tab( 'invoices' ) ?>><i class="fas fa-file-alt"></i>&nbsp;Invoices</a></li>
            <li><a data-tab-name="settings" href="<?php boston_user_tab( 'settings' )?>"<?php echo boston_active_tab( 'settings' ) ?>><i class="fas fa-cog"></i>Settings</a></li>
        </ul>
    </div>
</div>