<?php
echo "
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='4' $disabled1 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled1 >April</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='5' $disabled2 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled2 >May</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='6' $disabled3 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled3 >June</button>
    </div>
</div>";
?>