<div class="boston-file-manager">
    <div class="top-bar">
            <div class="folder" id="active-folder">
                <?php echo folder_info( 'W2/Income documents' ) ?>
            </div>
            <div class="folder">
                <?php echo folder_info( 'Business & other relevant expenses' ) ?>
            </div>
            <div class="folder">
                <?php echo folder_info( 'Draft Tax returns' ) ?>
            </div>
            <div class="folder">
                <?php echo folder_info( 'Final Tax Returns' ) ?>
            </div>
    </div>
<?php if ( $_GET['tab'] != 'prior-year-taxes' ) {?>
    <div class="uploader">
        <div class="loader"></div>
        <div class="upload-button">
            <img src="<?php echo imgfile( 'upload.png' ) ?>" alt="<?php _e( 'Upload button', 'boston-tax' )?>">
            <h5>Upload file</h5>
        </div>
        <div class="upload-input">
        <form action="">
            <input type="file" name="tax_file" id="tax_file">
            <?php ?>
        </form>
        </div>
    </div>
    <?php } else {?>  <div class="ruler"></div><?php }?>
    <div class="file-list">

    </div>
</div>