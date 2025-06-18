<?php
include '../db.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $conn->query("SELECT * FROM orders WHERE id = $id");
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM orders");
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
            echo json_encode($orders);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $user_id = intval($data['user_id']);
        $total = floatval($data['total']);
        $payment_method = $conn->real_escape_string($data['payment_method']);
        $status = 'pending';

        $sql = "INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES ($user_id, $total, '$payment_method', '$status')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $status = $conn->real_escape_string($data['status']);
        $sql = "UPDATE orders SET status='$status' WHERE id=$id";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $sql = "DELETE FROM orders WHERE id=$id";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
