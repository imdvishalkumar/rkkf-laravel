<?php
echo "
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='7' $disabled1 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled1 >July</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='8' $disabled2 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled2 >August</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='9' $disabled3 >
            </span>
        </div>
        <button type='button' class='form-control' $disabled3 >September</button>
    </div>
</div>";
?>