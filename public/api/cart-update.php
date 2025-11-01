<?php
session_start();
header('Content-Type: application/json');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_quantity') {
        $index = intval($_POST['index'] ?? -1);
        $change = intval($_POST['change'] ?? 0);
        
        if ($index >= 0 && isset($_SESSION['cart'][$index])) {
            $currentQty = $_SESSION['cart'][$index]['quantity'];
            $newQty = $currentQty + $change;
            
            if ($newQty > 0) {
                $_SESSION['cart'][$index]['quantity'] = $newQty;
                $response = [
                    'success' => true,
                    'message' => 'Quantity updated',
                    'new_quantity' => $newQty
                ];
            } else {
                // Remove item if quantity becomes 0
                array_splice($_SESSION['cart'], $index, 1);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
                $response = [
                    'success' => true,
                    'message' => 'Item removed',
                    'cart_count' => count($_SESSION['cart'])
                ];
            }
        } else {
            $response['message'] = 'Item not found in cart';
        }
    }
    elseif ($action === 'remove') {
        $index = intval($_POST['index'] ?? -1);
        
        if ($index >= 0 && isset($_SESSION['cart'][$index])) {
            array_splice($_SESSION['cart'], $index, 1);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            $response = [
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => count($_SESSION['cart'])
            ];
        } else {
            $response['message'] = 'Item not found in cart';
        }
    }
}

echo json_encode($response);
