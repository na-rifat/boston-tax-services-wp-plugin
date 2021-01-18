<table class="irs-correspondence-list">
    <tr>
        <th></th>
        <th><?php _e( 'File name', 'boston-tax' )?></th>
        <th><?php _e( 'Uploaded by', 'boston-tax' )?></th>
        <th><?php _e( 'Upload date', 'boston-tax' )?></th>
    </tr>
    <?php
        foreach ( $correspondence as $file ) {
        ?>
    <tr>
        <td>
            <img
                class="file-icon"
                src="<?php echo $this->file_ext2ico( $file['ext'] ) ?>"
                alt="<?php _e( 'File icon', 'boston-tax' )?>"
            />
        </td>
        <td class="file-name">
            <a target="_blank" href="<?php echo $file['url'] ?>"
                ><?php echo $file['name'] ?></a
            >
        </td>
        <td>
            <?php echo $this->pretty_print_uploaded_by( $file['uploaded_by'] ) ?>
        </td>
        <td>
            <?php echo date( 'm/d/Y', $file['date'] ) ?>
        </td>
    </tr>
    <?php
        }
    ?>
</table>
