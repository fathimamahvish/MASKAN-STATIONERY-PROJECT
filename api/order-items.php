<?php
include '../db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get a single order item by ID
            $id = intval($_GET['id']);
            $result = $conn->query("SELECT * FROM order_items WHERE id = $id");
            echo json_encode($result->fetch_assoc());
        } elseif (isset($_GET['order_id'])) {
            // Get all items for a specific order
            $order_id = intval($_GET['order_id']);
            $result = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
            $items = [];
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            echo json_encode($items);
        } else {
            // Get all order items
            $result = $conn->query("SELECT * FROM order_items");
            $items = [];
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            echo json_encode($items);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $order_id = intval($data['order_id']);
        $product_id = intval($data['product_id']);
        $quantity = intval($data['quantity']);
        $price = floatval($data['price']);
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES ($order_id, $product_id, $quantity, $price)";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
       $data = json_decode(file_get_contents("php://input"), true);
        $id = intval($data['id']);
        $quantity = intval($data['quantity']);
        $price = floatval($data['price']);
        $sql = "UPDATE order_items SET quantity = $quantity, price = $price WHERE id = $id";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $sql = "DELETE FROM order_items WHERE id = $id";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>

