<?php

// check login
if (!is_user_logged_in())
    exit;

// Get Profile
$model = get_query_var('target');

// Display model card
if ($model) {
    include bb_agency_BASEPATH.'Classes/ModelCard.php';

    $Card = new ModelCard;

    $Card->display( $model );
}