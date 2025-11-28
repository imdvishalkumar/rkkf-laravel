<?php
echo "
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='10' $disabled1 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled1 >October</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='11' $disabled2 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled2 >November</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='12' $disabled3 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled3 >December</button>
    </div>
</div>";
?>