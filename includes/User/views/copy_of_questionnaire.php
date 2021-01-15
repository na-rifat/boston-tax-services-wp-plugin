<div class="wrap">
            <div id="welcome-panel" class="welcome-panel">
                <div class="welcome-panel-content">
                    <div class="welcome-panel-column-container">
                        <?php

                            if ( ! empty( $form_data ) ) {
                                foreach ( $form_data as $key => $data ):

                                    $matches = array();

                                    if ( $key == 'cfdb7_status' ) {
                                        continue;
                                    }

                                    if ( $rm_underscore ) {
                                        preg_match( '/^_.*$/m', $key, $matches );
                                    }

                                    if ( ! empty( $matches[0] ) ) {
                                        continue;
                                    }

                                    if ( strpos( $key, 'cfdb7_file' ) !== false ) {

                                        $key_val = str_replace( 'cfdb7_file', '', $key );
                                        $key_val = str_replace( 'your-', '', $key_val );
                                        $key_val = str_replace( array( '-', '_' ), ' ', $key_val );
                                        $key_val = ucwords( $key_val );
                                        echo '<p><b>' . $key_val . '</b>: <a href="' . $cfdb7_dir_url . '/' . $data . '">'
                                            . $data . '</a></p>';
                                    } else {

                                        if ( is_array( $data ) ) {

                                            $key_val      = str_replace( 'your-', '', $key );
                                            $key_val      = str_replace( array( '-', '_' ), ' ', $key_val );
                                            $key_val      = ucwords( $key_val );
                                            $arr_str_data = implode( ', ', $data );
                                            $arr_str_data = esc_html( $arr_str_data );
                                            echo '<p><b>' . $key_val . '</b>: ' . nl2br( $arr_str_data ) . '</p>';

                                        } else {

                                            $key_val = str_replace( 'your-', '', $key );
                                            $key_val = str_replace( array( '-', '_' ), ' ', $key_val );

                                            $key_val = ucwords( $key_val );
                                            $data    = esc_html( $data );
                                            echo '<p><b>' . $key_val . '</b>: ' . nl2br( $data ) . '</p>';
                                        }
                                    }

                                endforeach;
                            } else {
                                echo 'No forms found!';
                            }

                            $form_data['cfdb7_status'] = 'read';
                            $form_data                 = serialize( $form_data );
                            $form_id                   = $result->form_id;
                            $table_name                = "{$prefix}{$this->forms_table_suffix}";
                            $cfdb                      = apply_filters( 'cfdb7_database', $wpdb );

                            $cfdb->query( "UPDATE $table_name SET form_value =
                            '$form_data' WHERE form_id = $form_id"
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>