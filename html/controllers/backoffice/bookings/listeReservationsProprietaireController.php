<?php
require_once(__DIR__."/../../../helpers/globalUtils.php");
require_once(__DIR__."/../../../models/BookingModel.php");

if(isset($_POST)) {
    extract($_POST);

    $result = BookingModel::find($owner_id, $period, $offset, $limit, $sortDir);
    $result = array_map(function ($r) {
        return array_merge(
            $r->getData(),
            ["photo_logement"=>$r->get("photo_logement")]
        );
    }, $result);
    send_json_response(["bookings" => $result]);
}