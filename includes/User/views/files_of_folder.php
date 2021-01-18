<h6><?php _e( boston_folder_index2name( $folder ), 'boston-tax' )?></h6>
<div
    class="boston-large upload-client-file"
    data-folder-id="<?php echo $folder ?>"
>
    <div class="small-loader"></div>
    <?php _e( 'Upload', 'boston-tax' )?>
</div>
<table class="client-file-list">
    <tr>
        <th></th>
        <th>File name</th>
        <th>Upload date</th>
        <th>Action</th>
    </tr>
    <?php
        foreach ( $files as $file ) {
            if ( $file['folder'] != $folder ) {
                continue;
            }
        ?>
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
            <?php echo date( 'm/d/Y', $file['date'] ) ?>
        </td>
        <td>
            <div
                class="boston-large upload-prepared"
                data-file-id="<?php echo $file['id'] ?>"
            >
               <div class="small-loader"></div> Uplaod prepared
            </div>
        </td>
    </tr>

    <?php
        }
    ?>
</table>
