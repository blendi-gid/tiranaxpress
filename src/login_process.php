<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nid = isset($_POST['nid']) ? $_POST['nid'] : null;
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
    $father_name = isset($_POST['father_name']) ? $_POST['father_name'] : null;
    $otp = isset($_POST['otp']) ? $_POST['otp'] : null;

    if (empty($nid) || empty($phone_number) || empty($father_name) || empty($otp)) {
        header("Location: failed_login.html?error=empty_fields");
        exit();
    }

    $conn = new mysqli('localhost', 'id21188692_tiranaxpress', 'Root123!?', 'id21188692_tiranaxpress');
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM user_info WHERE nid COLLATE utf8_general_ci = ? AND phone_number COLLATE utf8_general_ci = ? AND father_name COLLATE utf8_general_ci = ? AND otp = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nid, $phone_number, $father_name, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        $conn->close(); 

        $userNID = $row['nid'];
        $userOTP = $row['otp']; 

        echo "<script>
                localStorage.setItem('wasLoggedIn', 'true');
                localStorage.setItem('userNID', '$userNID'); // Store the NID
                localStorage.setItem('userOTP', '$userOTP'); // Store the OTP
                window.location.href = '/src/home/user.html'; // Redirect to user.html
              </script>";
        exit();
    } else {
        $stmt->close();
        $conn->close(); 
        header("Location: failed_login.html?error=user_not_found");
        exit();
    }
}
?>