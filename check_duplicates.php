<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $response = array();
    
    if (isset($input['check_name']) && !empty($input['check_name'])) {
        $name = trim($input['check_name']);
        $sql = "SELECT id FROM books WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $response['name_exists'] = $result->num_rows > 0;
        $stmt->close();
    }
    
    if (isset($input['check_image']) && !empty($input['check_image'])) {
        $image_url = $input['check_image'];
        $sql = "SELECT id FROM books WHERE image_url = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $image_url);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $response['image_exists'] = $result->num_rows > 0;
        $stmt->close();
    }
    
    echo json_encode($response);
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}

$conn->close();
?>