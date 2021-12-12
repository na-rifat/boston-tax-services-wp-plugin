<div class="assign-tax-expert">
    <div class="information-holder">
        <div class="close-button">X</div>
        <div class="client-information" data-client-id="<?php echo $atts['client_profile']['id'] ?>">
            <h2>Client</h2>
            <div class="admin-row">
                <?php echo $atts['client_profile']['avatar'] ?>
            </div>
            <table class="form-table">
                <tr>
                    <th>ID</th>
                    <td><?php echo $atts['client_profile']['id'] ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo $atts['client_profile']['full_name'] ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo $atts['client_profile']['username'] ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $atts['client_profile']['email'] ?></td>
                </tr>
                <tr>
                    <th>Member since</th>
                    <td>
                        <?php echo date( 'D, M d, Y', strtotime( $atts['client_profile']['joined'] ) ) ?>
                    </td>
                </tr>
                <tr>
                    <th>Currently assigned expert</th>
                    <td><?php echo $atts['client_profile']['current_expert']['full_name'] ?></td>
                </tr>
            </table>
        </div>
        <div class="expert-information">
            <h2>Tax expert</h2>
            <table class="form-table">
                <tr>
                    <th><label for="expert_id">Choose expert</label></th>
                    <td>
                        <select name="expert_id" id="expert_id">
                            <?php echo $atts['tax_experts'] ?>
                        </select>
                    </td>
                </tr>
            </table>
            <hr />
            <div class="expert-information-profile"></div>
        </div>
    </div>
</div>
<script>
;
(function($) {
    $(`.close-button`).click(function(e) {
        $(`.assign-tax-expert`).hide(500, function(e) {
            $(this).remove();
        });
    });

    get_expert_profile();
    $(`#expert_id`).on(`change`, function() {
        get_expert_profile();
    });

    function get_expert_profile() {
        let expert_id = $(`#expert_id`).val();
        $(`.expert-information-profile`).html(`<div class="loader"></div>`)

        $.ajax({
            type: `POST`,
            url: boston.ajaxurl,
            dataType: `json`,
            data: {
                action: `get_tax_expert_info`,
                expert_id: expert_id
            },
            success: (res) => {
                console.log(res)
                if (res.data.found) {
                    $(`.expert-information-profile`).html(res.data.profile);
                }
            },
            error: () => {
                alert(`Something went wrong!`);
            }
        })
    }
})(jQuery);
</script>



<style>
.bon-grid-item strong.orange-text {
    color: #FF9900;
    margin-bottom: 10px;
    display: inline-block;
}
.bon-grid-item{
    width: calc(50% - 10px);
    display: inline-flex;
    margin: 20px 0 20px 0;
}
.bon-grid-item img{
    margin-right: 40px;
    filter: grayscale(1);
}
.bond-grid{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-flow: column;
    flex-wrap: wrap;
    height: 100%;
    width: 100%;
}
.bon-grid-item div.white-text{
    color: white;
}
</style>

<!-- TEMP -->
<div class="bon-grid">
    <div class="bon-grid-item">
        <div>
            <img src="https://naturenow.rafalotech.com/wp-content/uploads/2021/11/Icon-awesome-tools.png">
        </div>
        <div>
            <strong class="orange-text">Bonorum et Malorum</strong>
            <div class="white-text">Bonorum et Malorum</div>
            <div class="white-text">Bonorum et Malorum</div>
        </div>
    </div>
    <div class="bon-grid-item">
        <div>
            <img src="https://naturenow.rafalotech.com/wp-content/uploads/2021/11/Icon-awesome-tools.png">
        </div>
        <div>
            <strong class="orange-text">Bonorum et Malorum</strong>
            <div class="white-text">Bonorum et Malorum</div>
            <div class="white-text">Bonorum et Malorum</div>
        </div>
    </div>
    <div class="bon-grid-item">
        <div>
            <img src="https://naturenow.rafalotech.com/wp-content/uploads/2021/11/Icon-awesome-tools.png">
        </div>
        <div>
            <strong class="orange-text">Bonorum et Malorum</strong>
            <div class="white-text">Bonorum et Malorum</div>
            <div class="white-text">Bonorum et Malorum</div>
        </div>
    </div>
    <div class="bon-grid-item">
        <div>
            <img src="https://naturenow.rafalotech.com/wp-content/uploads/2021/11/Icon-awesome-tools.png">
        </div>
        <div>
            <strong class="orange-text">Bonorum et Malorum</strong>
            <div class="white-text">Bonorum et Malorum</div>
            <div class="white-text">Bonorum et Malorum</div>
        </div>
    </div>
</div>