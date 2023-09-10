<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nid = $_POST['nid'];
    $phone_number = $_POST['phone_number'];
    $father_name = $_POST['father_name'];
    $otp = $_POST['otp'];

    if (empty($nid) || empty($phone_number) || empty($father_name) || empty($otp)) {
        echo "All fields are required.";
        exit;
    }

    if (!preg_match("/^6\d{8}$/", $phone_number)) {
        echo "Invalid phone number format.";
        exit;
    }

    $conn = new mysqli('localhost', 'id21188692_tiranaxpress', 'Root123!?', 'id21188692_tiranaxpress');
    if ($conn->connect_error) {
        echo "Connection Failed: " . $conn->connect_error;
        die();
    } else {
        $checkQuery = "SELECT * FROM user_info WHERE nid COLLATE utf8_general_ci = ? OR phone_number COLLATE utf8_general_ci = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $nid, $phone_number);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            header("Location: samedet.html");
            exit();
        }

        $insertQuery = "INSERT INTO user_info (nid, phone_number, father_name, otp) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $nid, $phone_number, $father_name, $otp);

        if ($stmt->execute()) {
            header("Location: login.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>