<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get all orders with their items
$stmt = $pdo->prepare("
    SELECT 
        o.id AS order_id,
        o.created_at AS order_date,
        o.total AS order_total,
        p.name AS product_name,
        oi.quantity,
        oi.price AS unit_price
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$allOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($allOrders)) {
    die("No orders found");
}

// Calculate grand total
$grandTotal = array_sum(array_column($allOrders, 'order_total'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Complete Order History</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        @media print {
            nav, .no-print { display: none; }
            .receipt-container { 
                width: 100% !important;
                margin: 0 !important;
                padding: 10px !important;
            }
            body { font-size: 12px; }
        }
        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .order-group {
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .product-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }
        .total-row {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-top: 15px;
        }
        .grand-total {
            font-size: 1.4em;
            color: #2c3e50;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="receipt-container">
        <h2 class="text-center">Premium Goods Hub - Complete Order History</h2>
        <p class="text-center">Customer: <?= htmlspecialchars($_SESSION['user_name']) ?></p>
        <p class="text-center">Generated: <?= date('F j, Y \a\t H:i') ?></p>

        <?php
        $currentOrderId = null;
        foreach ($allOrders as $index => $order):
            if ($currentOrderId !== $order['order_id']) {
                if ($currentOrderId !== null) {
                    echo '</div>'; // Close previous order group
                }
                $currentOrderId = $order['order_id'];
                ?>
                <div class="order-group">
                    <h3>Order #<?= $order['order_id'] ?></h3>
                    <p>Date: <?= date('M d, Y H:i', strtotime($order['order_date'])) ?></p>
                    <div class="products-list">
            <?php
            }
            ?>
            <div class="product-row">
                <span><?= htmlspecialchars($order['product_name']) ?></span>
                <span>
                    <?= $order['quantity'] ?> x Ksh<?= number_format($order['unit_price'], 2) ?>
                    = Ksh<?= number_format($order['quantity'] * $order['unit_price'], 2) ?>
                </span>
            </div>
            
            <?php
            // Show order total when last item in group
            if (!isset($allOrders[$index + 1]) || $allOrders[$index + 1]['order_id'] !== $currentOrderId) {
                ?>
                    <div class="total-row">
                        Order Total: Ksh<?= number_format($order['order_total'], 2) ?>
                    </div>
                </div>
                <?php
            }
        endforeach;
        ?>
        </div> <!-- Close last order group -->

        <div class="grand-total text-center">
            <strong>Grand Total of All Orders:</strong>
            Ksh<?= number_format($grandTotal, 2) ?>
        </div>

        <button onclick="window.print()" class="no-print" style="margin-top: 30px;">
            Print Complete Receipt
        </button>
    </div>
    <div class="button-container text-center no-print">
    <button onclick="downloadReceipt()" class="btn btn-success">
        Download Receipt
    </button>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadReceipt() {
        const receipt = document.querySelector(".receipt-container");
        html2pdf().from(receipt).save("Order_History.pdf");
    }
</script>

</body>
</html>