<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->device_id) &&
    !empty($data->latitude) &&
    !empty($data->longitude)
){
    $device_id = $data->device_id;
    $phone_number = isset($data->phone_number) ? $data->phone_number : null;
    $model = isset($data->model) ? $data->model : null;
    $brand = isset($data->brand) ? $data->brand : null;
    $os_version = isset($data->os_version) ? $data->os_version : null;
    $battery_level = isset($data->battery_level) ? $data->battery_level : null;
    $is_charging = isset($data->is_charging) ? ($data->is_charging ? 1 : 0) : 0;
    $latitude = $data->latitude;
    $longitude = $data->longitude;
    $altitude = isset($data->altitude) ? $data->altitude : 0;

    $stmt = $conn->prepare("INSERT INTO device_data (device_id, phone_number, model, brand, os_version, battery_level, is_charging, latitude, longitude, altitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssiiddd", $device_id, $phone_number, $model, $brand, $os_version, $battery_level, $is_charging, $latitude, $longitude, $altitude);

    if($stmt->execute()){
        http_response_code(201);
        echo json_encode(array("message" => "Data recorded successfully."));
    } else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to record data. " . $stmt->error));
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data. device_id, latitude, and longitude are required."));
}

$conn->close();
?>
