<?php if ( ! empty( $files ) ) {?>
<!-- Uploader -->
<form action="">
    <input type="file" id="upload_tax_file_from_expert" style="display: none" />
</form>
<!-- t0 -->
<h6><?php _e( boston_folder_index2name( 't0' ), 'boston-tax' )?></h6>
<div class="boston-large upload-client-file" data-folder-id="t0">
    <div class="small-loader"></div>
    Upload
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

                if ( $file['folder'] != 't0' ) {
                    break;
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
        <td>
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
                data-file-id="<?php $file['id']?>"
            >
                Uplaod prepared
            </div>
        </td>
    </tr>
    <?php
        }?>
</table>

<!-- t1 -->
<h6><?php _e( boston_folder_index2name( 't1' ), 'boston-tax' )?></h6>
<div class="boston-large upload-client-file" data-folder-id="t1">
    <div class="small-loader"></div>
    Upload
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

                if ( $file['folder'] != 't1' ) {
                    break;
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
        <td>
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
                data-file-id="<?php $file['id']?>"
            >
                Uplaod prepared
            </div>
        </td>
    </tr>
    <?php
        }?>
</table>

<!-- t2 -->
<h6><?php _e( boston_folder_index2name( 't2' ), 'boston-tax' )?></h6>
<div class="boston-large upload-client-file" data-folder-id="t2">
    <div class="small-loader"></div>
    Upload
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

                if ( $file['folder'] != 't2' ) {
                    break;
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
        <td>
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
                data-file-id="<?php $file['id']?>"
            >
                Uplaod prepared
            </div>
        </td>
    </tr>
    <?php
        }?>
</table>

<!-- t3 -->
<h6><?php _e( boston_folder_index2name( 't3' ), 'boston-tax' )?></h6>
<div class="boston-large upload-client-file" data-folder-id="t3">
    <div class="small-loader"></div>
    Upload
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

                if ( $file['folder'] != 't3' ) {
                    break;
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
        <td>
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
                data-file-id="<?php $file['id']?>"
            >
                Uplaod prepared
            </div>
        </td>
    </tr>
    <?php
        }?>
</table>
<script>
    (function ($) {
        upload_client_file();
    })(jQuery);
</script>

<?php
        } else {
        ?>
<div class="files-found">No files found!</div>
<?php
    }?>
