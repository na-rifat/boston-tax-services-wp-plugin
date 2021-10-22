<div class="boston-file-manager">
    <div class="top-bar">
        <div class="folder  ripple" id="active-folder">
            <?php echo folder_info( 'W2/Income documents' ) ?>
        </div>
        <div class="folder  ripple">
            <?php echo folder_info( 'Business & other relevant expenses' ) ?>
        </div>
        <div class="folder  ripple">
            <?php echo folder_info( 'Draft Tax returns' ) ?>
        </div>
        <div class="folder  ripple">
            <?php echo folder_info( 'Final Tax Returns' ) ?>
        </div>

        <?php if ( $_GET['tab'] != 'prior-year-taxes' ) {?>
        <div class="uploader">
            
            <div class="upload-button boston-large">                
                <div class="small-loader"></div>
                <i class="fas fa-file-alt"></i>
                Upload tax documents
            </div>

            <div class="upload-input">
                <form action="">
                    <input type="file" name="tax_file" id="tax_file" />
                    <?php ?>
                </form>
            </div>


            <?php if ( $this->user->have_expert( $this->user->current_user_id )
            ) {?>
            <div class="meeting-scheduler boston-large">
                <i class="far fa-clock"></i>
                <span>Schedule a meeting</span>
            </div>
            <?php }?>
        </div>
        <?php }?>

    </div>

    <div class="file-list"></div>
</div>
