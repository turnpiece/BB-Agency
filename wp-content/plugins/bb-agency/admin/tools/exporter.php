<h2><?php _e('Export Models', bb_agency_TEXTDOMAIN) ?></h2>

<?php $dataTypes = bb_agency_get_datatypes(false); ?>

<form action="" method="post">

    <?php if (!empty($dataTypes)) : ?>

    <table> 
        <tr>
            <th scope="row"><?php _e("Classification", bb_agency_TEXTDOMAIN) ?>:</th>
            <td><select name="ProfileType" id="ProfileType">               
                <option value=""><?php _e("Any Profile Type", bb_agency_TEXTDOMAIN) ?></option>
                <?php foreach ($dataTypes as $type) : ?>
                <option value="<?php echo $dataType->DataTypeID ?>" <?php selected($type->DataTypeID, $_SESSION['ProfileType']) ?>><?php echo $type->DataTypeTitle ?></option>
                <?php endforeach; ?>
                </select></td>
            </td>
        </tr>
    </table>

    <?php else : $type = $dataTypes[0]; ?>
    <input type="hidden" name="ProfileType" value="<?php echo $type->DataTypeID ?>" />
    <?php endif; ?>

    <input type="hidden" name="action" value="export" />
    
    <p class="submit">
        <input type="submit" value="<?php _e("Export", bb_agency_TEXTDOMAIN) ?>" class="button-primary" />
    </p>
<form>