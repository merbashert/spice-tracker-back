<?php

header("Content-Type: application/json");

include_once __DIR__ . '/../models/spice.php';

if($_REQUEST['action'] === 'index'){
    echo json_encode(Spices::all());
} else if($_REQUEST['action'] === 'create') {
    $request_body = file_get_contents('php://input');
    $body_object = json_decode($request_body);
    $new_spice = new Spice(null, $body_object->name, $body_object->category, $body_object->date_purchased);
    $all_spices = Spices::create($new_spice);

    echo json_encode($all_spices);
} else if($_REQUEST['action'] ==='update'){
    $request_body = file_get_contents('php://input');
    $body_object = json_decode($request_body);
    $updated_spice = new Spice($_REQUEST['id'], $body_object->name, $body_object->category, $body_object->date_purchased);
    $all_spices = Spices::update($updated_spice);

    echo json_encode($all_spices);
} else if ($_REQUEST['action'] === 'delete'){
    $all_spices = Spices::delete($_REQUEST['id']);
    echo json_encode($all_spices);
}
 ?>
