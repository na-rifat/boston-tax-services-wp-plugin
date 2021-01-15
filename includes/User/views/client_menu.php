<div class="dashboard-left-panel">
    <?php echo $profile ?>
    <div class="user-dashboard-menu">
        <ul>
            <li><a data-tab-name="current-year-taxes" href="<?php boston_user_tab( 'current-year-taxes' )?>"<?php echo boston_active_tab( 'current-year-taxes' ) ?>><i class="far fa-file-alt"></i>Current Year Taxes</a></li>
            <li><a data-tab-name="prior-year-taxes" href="<?php boston_user_tab( 'prior-year-taxes' )?>"<?php echo boston_active_tab( 'prior-year-taxes' ) ?>><i class="far fa-copy"></i>Prior Year Taxes</a></li>
            <li><a class="messages-tab" href="<?php boston_user_tab( 'messages' )?>"<?php echo boston_active_tab( 'messages' ) ?>><i class="far fa-comment-alt"></i>Messages<span class="unread-count">0</span></a></li>
            <li><a href="<?php boston_user_tab( 'irs-correspondence' )?>"<?php echo boston_active_tab( 'irs-correspondence' ) ?>><i class="fas fa-building"></i>IRS correspondence</a></li>
            <li><a href="<?php boston_user_tab( 'settings' )?>"<?php echo boston_active_tab( 'settings' ) ?>><i class="fas fa-cog"></i>Settings</a></li>
        </ul>
    </div>
</div>