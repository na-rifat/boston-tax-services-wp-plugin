<div class="client-files-list">

<!-- Uploader -->
<form action="">
    <input type="file" id="upload_tax_file_from_expert" style="display: none" />
    <input type="file" id="upload_prepared_tax_file" style="display: none" />
</form>

<?php
    echo $this->files_of_folder( $files, 't0' );
    echo $this->files_of_folder( $files, 't1' );
    echo $this->files_of_folder( $files, 't2' );
    echo $this->files_of_folder( $files, 't3' );
?>

<h6><?php _e( 'IRS correspondence', 'boston-tax' )?></h6>
<div
    class="boston-large upload-client-file"
    data-folder-id="irs-correspondence"
>
    <div class="small-loader"></div>
    <?php _e( 'Upload', 'boston-tax' )?>
</div>
<div class="expert-irs-correspondence">
<table>
        <th></th>
        <th><?php _e( 'File name', 'boston-tax' )?></th>
        <th><?php _e( 'Uploaded by', 'boston-tax' )?></th>
        <th><?php _e( 'Upload date', 'boston-tax' )?></th>
    <?php foreach ( $irs as $file ) {?>
       <tr>
       <td>
            <img
                class="file-icon"
                src="<?php echo $this->file->file_ext2ico( $file['ext'] ) ?>"
                alt="<?php _e( 'File icon', 'boston-tax' )?>"
            />
        </td>
        <td class="file-name">
            <a target="_blank" href="<?php echo $file['url'] ?>"
                ><?php echo $file['name'] ?></a
            >
        </td>
        <td>
            <?php echo $this->file->pretty_print_uploaded_by( $file['uploaded_by'] ) ?>
        </td>
        <td>
            <?php echo date( 'm/d/Y', $file['date'] ) ?>
        </td>
       </tr>
    <?php }?>
    </table>
</div>
<script>
    (function ($) {
        upload_client_file();
        upload_prepared();
    })(jQuery);
</script>

</div>