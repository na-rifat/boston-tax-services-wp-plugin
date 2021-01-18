<?php if ( ! empty( $this->files ) ) {?>

<table class="boston-file-list">
<tr>
<th></th>
<th>File name</th>
<th>Uploaded by</th>
<th>Approved</th>
<th>Upload date</th>
</tr>
        <?php foreach ( $this->files as $file ) {?>
            <tr>
                <td><img src="<?php echo $this->file_ext2ico( $file->ext ) ?>" alt="<?php _e( 'File icon', 'boston-tax' )?>"></td>
                <td><a target="_blank" href="<?php echo $file->url ?>"><?php echo $file->name ?></a></td>
                <td><?php echo $this->pretty_print_uploaded_by( $file->uploaded_by ) ?></td>
                <td><input type="checkbox" name="approved" id="approved" <?php echo $file->status == 'Approved' ? ' checked ' : '' ?> class="approve-file" data-file-id="<?php echo $file->id ?>"></td>
                <td><?php echo date( 'm/d/Y', $file->date ) ?></td>
            </tr>
        <?php }?>
</table>
<script>
    (function($){
        $(`.approve-file`).on('change',function (e) {
            let data = {
                action: `approve_file`,
                nonce: boston.approve_file_nonce,
                file_id: $(this).data(`file-id`)
            }

            $.ajax(
                {
                    type: `POST`,
                    url: boston.ajaxurl,
                    dataType: `json`,
                    data: data,
                    success: (res)=>{

                    },
                    error: ()=>{
                        alert(`Something went wrong with connection!`)
                    }
                }
            )
         })
    })(jQuery)
</script>
<?php } else {?>


<div style="padding: 100px; margin: auto; text-align: center;">You dont have any document in this folder!</div>

<?php }
exit;?>