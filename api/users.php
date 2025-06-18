<?php
include '../db.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $conn->query("SELECT id, username, phone, email, role FROM users WHERE id = $id");
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT id, username, phone, email, role FROM users");
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode($users);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $conn->real_escape_string($data['username']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $phone = $conn->real_escape_string($data['phone']);
        $email = $conn->real_escape_string($data['email']);
        $role = 'customer';

        $sql = "INSERT INTO users (username, password, phone, email, role) VALUES ('$username', '$password', '$phone', '$email', '$role')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $sql = "DELETE FROM users WHERE id=$id";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
