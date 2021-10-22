<div id="boston-options">
    <h1>Boston Tax Services</h1>
    <hr />
    <h2>Social login</h2>
    <form action="" method="POST">
        <table class="form-table">
            <tr>
                <th><label for="facebook_app_id">Facebook App ID:</label></th>
                <td>
                    <input
                        type="text"
                        class="regular-text"
                        placeholder="Your facebook app ID"
                        name="facebook_app_id"
                        id="facebook_app_id"
                        value="<?php echo get_option( 'facebook_app_id' ) ?>"
                    />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="facebook_app_secret"
                        >Facebook App Secret:</label
                    >
                </th>
                <td>
                    <input
                        type="text"
                        class="regular-text"
                        placeholder="Your facebook app secret"
                        name="facebook_app_secret"
                        id="facebook_app_secret"
                        value="<?php echo get_option( 'facebook_app_secret' ) ?>"
                    />
                </td>
            </tr>
            <tr>
                <th><label for="twitter_app_id">Twitter App ID:</label></th>
                <td>
                    <input
                        type="text"
                        class="regular-text"
                        placeholder="Your twitter app ID"
                        name="twitter_app_id"
                        id="twitter_app_id"
                        value="<?php echo get_option( 'twitter_app_id' ) ?>"
                    />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="twitter_app_secret">Twitter App Secret:</label>
                </th>
                <td>
                    <input
                        type="text"
                        class="regular-text"
                        placeholder="Your twitter app secret"
                        name="twitter_app_secret"
                        id="twitter_app_secret"
                        value="<?php echo get_option( 'twitter_app_secret' ) ?>"
                    />
                </td>
            </tr>
            <tr>
                <th><label for="google_app_id">Google App ID:</label></th>
                <td>
                    <input
                        type="text"
                        class="regular-text"
                        placeholder="Your google app ID"
                        name="google_app_id"
                        id="google_app_id"
                        value="<?php echo get_option( 'google_app_id' ) ?>"
                    />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="google_app_secret">Google App Secret:</label>
                </th>
                <td>
                    <input
                        type="text"
                        class="regular-text"
                        placeholder="Your google app secret"
                        name="google_app_secret"
                        id="google_app_secret"
                        value="<?php echo get_option( 'google_app_secret' ) ?>"
                    />
                </td>
            </tr>
            <tr>
                <th class="boston-save-load"></th>
                <td>
                    <?php submit_button( __( 'Save', 'boston' ), 'large', 'save_boston_options' )?>
                </td>
            </tr>
        </table>

        <?php wp_nonce_field( 'boston_options_nonce' )?>
<?php boston_form_action( 'boston_options' )?>
    </form>
</div>
