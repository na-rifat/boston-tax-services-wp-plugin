<?php if ( ! empty( $this->files ) ) {?>

<table class="boston-file-list">
        <?php foreach ( $this->files as $file ) {?>
            <tr>
            <td><img src="<?php echo $this->file_ext2ico( $file->ext ) ?>" alt="<?php _e( 'File icon', 'boston-tax' )?>"></td>
            <td><a target="_blank" href="<?php echo $file->url ?>"><?php echo $file->name ?></a></td>
            <td><?php echo date( 'm/d/Y', $file->date ) ?></td>
            </tr>
        <?php }
            exit;?>
</table>

<?php } else {?>
 

<div style="padding: 100px; margin: auto; text-align: center;">No files found!</div>

<?php exit;}  ?>