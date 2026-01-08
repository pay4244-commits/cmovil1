<?php
header("Content-Type: application/json");
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data) {
        try {
            $stmt = $conn->prepare("INSERT INTO device_data (device_id, phone_number, model, brand, os_version, battery_level, is_charging, latitude, longitude, altitude, timestamp) VALUES (:device_id, :phone_number, :model, :brand, :os_version, :battery_level, :is_charging, :latitude, :longitude, :altitude, :timestamp)");

            $stmt->bindParam(':device_id', $data['device_id']);
            $stmt->bindParam(':phone_number', $data['phone_number']);
            $stmt->bindParam(':model', $data['model']);
            $stmt->bindParam(':brand', $data['brand']);
            $stmt->bindParam(':os_version', $data['os_version']);
            $stmt->bindParam(':battery_level', $data['battery_level']);
            $stmt->bindParam(':is_charging', $data['is_charging'], PDO::PARAM_BOOL);
            $stmt->bindParam(':latitude', $data['latitude']);
            $stmt->bindParam(':longitude', $data['longitude']);
            $stmt->bindParam(':altitude', $data['altitude']);
            $stmt->bindParam(':timestamp', $data['timestamp']); // Timestamp from device

            $stmt->execute();

            echo json_encode(["status" => "success", "message" => "Data recorded successfully"]);
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
}
?>
