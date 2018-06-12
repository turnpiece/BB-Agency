<?php

// check login
if (!is_user_logged_in())
    exit;

// Get Profile
$model = get_query_var('target');

// Display model card
if ($model) {
    include bb_agency_BASEPATH.'Classes/LBDA_ModelCard.php';

    $Card = new LBDA_ModelCard($model);

    $Card->display();
}