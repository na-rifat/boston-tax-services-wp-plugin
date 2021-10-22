<h1>Current year clients</h1>
<hr>
<h3>Instructions</h3>
<table class="instruction-table-color">
    <tr>
        <td><div class="ins-red"></div></td>
        <td>High priority</td>
    </tr>
    <tr>
        <td><div class="ins-yellow"></div></td>
        <td>Medium priority</td>
    </tr>
    <tr>
        <td><div class="ins-orange"></div></td>
        <td>Low priority</td>
    </tr>
    <tr>
        <td><div class="ins-green"></div></td>
        <td>Filed</td>
    </tr>
    <tr>
        <td><div class="ins-grey"></div></td>
        <td>Ready for review</td>
    </tr>
    <tr>
        <td><div class="ins-blue"></div></td>
        <td>On entension</td>
    </tr>
    <tr>
        <td><div class="ins-violet"></div></td>
        <td>Engaged but no docs</td>
    </tr>
</table>

<hr>
<?php

    $list->prepare_items();
    $list->search_box( 'search', 'search_id' );
    $list->display();

    // var_dump(\Boston\User\User::get()->roles);
?>