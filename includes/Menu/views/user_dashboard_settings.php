<div id="user-dashboard-settings">
    <h1>Boston Tax Services</h1>
    <hr />
    <h2>Tab shortcodes</h2>
    <form action="" method="POST">
        <table class="form-table">
            <tr>
                <th>
                    <label for="current_year_taxes">Current Year Taxes</label>
                </th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="current_year_taxes"
                        id="current_year_taxes"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'current_year_taxes' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="prior_year_taxes">Prior Year Taxes</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="prior_year_taxes"
                        id="prior_year_taxes"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'prior_year_taxes' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="boston_user_messages">Messages</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="boston_user_messages"
                        id="boston_user_messages"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'boston_user_messages' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th>
                    <label for="irs_correspondence">IRS correspondence</label>
                </th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="irs_correspondence"
                        id="irs_correspondence"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'irs_correspondence' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="boston_user_settings">Settings</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="boston_user_settings"
                        id="boston_user_settings"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'boston_user_settings' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="boston_welcome_page">Welcome page</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="boston_welcome_page"
                        id="boston_welcome_page"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'boston_welcome_page' ) ?></textarea
                    >
                </td>
            </tr>

            <!-- Wizard settings part -->

            <tr><th><h2><?php _e( 'Wizard settings', 'boston-tax' )?></h2></th><td></td></tr>
            <tr>
                <th><label for="user_wizard_step_1">Step 1(First form)</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="user_wizard_step_1"
                        id="user_wizard_step_1"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'user_wizard_step_1' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="user_wizard_step_2">Step 2(Ask for agreement)</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="user_wizard_step_2"
                        id="user_wizard_step_2"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'user_wizard_step_2' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="user_wizard_step_3">Step 3(Agreement sign)</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="user_wizard_step_3"
                        id="user_wizard_step_3"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'user_wizard_step_3' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="user_wizard_step_4">Step 4(Form 2)</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="user_wizard_step_4"
                        id="user_wizard_step_4"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'user_wizard_step_4' ) ?></textarea
                    >
                </td>
            </tr>
            <tr>
                <th><label for="user_wizard_step_5">Step 5(Dashboard)</label></th>
                <td>
                    <textarea
                        placeholder="[shortcode attribute=value]"
                        name="user_wizard_step_5"
                        id="user_wizard_step_5"
                        cols="100"
                        rows="5"
                    >
<?php echo boston_get_option( 'user_wizard_step_5' ) ?></textarea
                    >
                </td>
            </tr>

            <!-- Wizard forms information part -->

            <tr><th><h2><?php _e( 'Wizard forms information', 'boston-tax' )?></h2></th><td></td></tr>
            <tr>
                <th><label for="wizard_form_id_1">Wizard form ID(1)</label></th>
                <td>
                    <input
                        type="text"
                        placeholder="123"
                        name="wizard_form_id_1"
                        id="wizard_form_id_1"
                        value="<?php echo boston_get_option( 'wizard_form_id_1' ) ?>"
                        class="regular-text"
                    >
                </td>
            </tr>
            <tr>
                <th><label for="wizard_form_id_2">Wizard form ID(2)</label></th>
                <td>
                    <input
                        type="text"
                        placeholder="123"
                        name="wizard_form_id_2"
                        id="wizard_form_id_2"
                        value="<?php echo boston_get_option( 'wizard_form_id_2' ) ?>"
                        class="regular-text"
                    >
                </td>
            </tr>
            <tr>
                <th><label for="wizard_form_id_3">Wizard form ID(3)</label></th>
                <td>
                    <input
                        type="text"
                        placeholder="123"
                        name="wizard_form_id_3"
                        id="wizard_form_id_3"
                        value="<?php echo boston_get_option( 'wizard_form_id_3' ) ?>"
                        class="regular-text"
                    >
                </td>
            </tr>


            <!-- Pages settings part -->

            <tr><th><h2><?php _e( 'Pages settings', 'boston-tax' )?></h2></th><td></td></tr>
            <tr>
                <th><label for="wizard_page_slug">Wizard page slug</label></th>
                <td>
                    <input
                        type="text"
                        placeholder="slug-slug"
                        name="wizard_page_slug"
                        id="wizard_page_slug"
                        value="<?php echo boston_get_option( 'wizard_page_slug' ) ?>"
                        class="regular-text"
                    >
                </td>
            </tr>
            <tr>
                <th><label for="dashboard_page_slug">Dashboard page slug</label></th>
                <td>
                    <input
                        type="text"
                        placeholder="slug-slug"
                        name="dashboard_page_slug"
                        id="dashboard_page_slug"
                        value="<?php echo boston_get_option( 'dashboard_page_slug' ) ?>"
                        class="regular-text"
                    >
                </td>
            </tr> 

            <tr>
                <th><label for="restricted_plages_for_all">Restricted pages for all</label></th>
                <td>
                    <input
                        type="text"
                        placeholder="slug-slug, slug-slug, id"
                        name="restricted_plages_for_all"
                        id="restricted_plages_for_all"
                        value="<?php echo boston_get_option( 'restricted_plages_for_all' ) ?>"
                        class="regular-text"
                    >
                </td>
            </tr>

            <tr>
                <th><label for="restricted_plages_for_logged_out">Restricted pages for logged out users</label></th>
                <td>
                    <input
                        type="text"
                        placeholder="slug-slug, slug-slug, id"
                        name="restricted_plages_for_logged_out"
                        id="restricted_plages_for_logged_out"
                        value="<?php echo boston_get_option( 'restricted_plages_for_logged_out' ) ?>"
                        class="regular-text"
                    >
                </td>
            </tr>

            <!-- submit part -->
            <tr>
                <th class="boston-save-load"></th>
                <td>
                    <?php submit_button( 'Save settings', 'large', 'save_dashboard_shortcodes' )?>
                </td>
            </tr>
        </table>
        <?php boston_form_action( 'save_dashboard_shortcodes' )?>
<?php wp_nonce_field( 'save_dashboard_shortcodes' )?>
    </form>
</div>
