<?php
echo "
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='1' ".$disabled1." >
            </span>
        </div>
        <button type='button' class='form-control' ".$disabled1." >January</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='2' ".$disabled2." >
            </span>
        </div>
        <button type='button' class='form-control' ".$disabled2." >February</button>
    </div>
</div>
<div class='col-sm-4'>
    <div class='input-group'>
        <div class='input-group-prepend'>
            <span class='input-group-text'>
                <input type='checkbox' name='months[]' value='3' ".$disabled3." >
            </span>
        </div>
        <button type='button' class='form-control' ".$disabled3." >March</button>
    </div>
</div>";
?>