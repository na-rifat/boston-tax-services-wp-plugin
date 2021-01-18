<form action="">
    <input type="file" id="upload_irs_correspondence" style="display: none;">
</form>
<div class="irs-uploader boston-large mg8-ud">
    <div class="small-loader"></div>
    <i class="fas fa-file-alt"></i>
    <?php  _e('Upload document', 'boston-tax') ?>
</div>
<div class="client-irs-correspondence-files">

</div>

<script>
    (function($){
        $(document).ready(function(){
            get_list_of_client_irs_correspondence();
            upload_irs_correspondence();
        });
    })(jQuery)
</script>