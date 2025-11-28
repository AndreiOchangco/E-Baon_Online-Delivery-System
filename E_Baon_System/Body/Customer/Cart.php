<?php
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
    header("Location: ../../Main/Index.php");
    exit();
}

$username = $_SESSION["username"] ?? "Customer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <link rel="stylesheet" href="../../Css/Customer.css">
    <link rel="stylesheet" href="../../Css/Customer_css/Cart.css">
</head>

<body class="customer-body">

<header class="customer-head">
    <div class="customer-logo-box">L</div>

    <div class="customer-head-text">
        <h1>Omacha Shop</h1>
        <p>customer</p>
    </div>

    <div class="cart-menu-wrap">
        <div class="cart-menu-box">
            <button type="button" class="cart-menu-toggle">☰</button>
            <a href="../../Main/Customer.php" class="cart-menu-back">⬅</a>
        </div>
    </div>
</header>

<div class="customer-separator"></div>

<main class="customer-main cart-main">

    <div class="cart-container">

        <div class="cart-shop-header">
            <div class="cart-shop-logo">L</div>
            <h3 class="cart-shop-name">Name of the Shop</h3>
        </div>

        <div class="cart-products">

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 1</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 2</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 3</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

        </div>

        <div class="cart-summary">

            <div class="cart-fees">
                <div class="cart-fee-line">Delivery Fee: <span class="fee-amount">+ ₱60</span></div>
                <div class="cart-fee-line">Discount: <span class="fee-amount">− ₱20</span></div>
            </div>

        </div>

        <div class="cart-total">
            <div class="total-label">Total Price</div>
            <div class="total-box">₱ 0.00</div>
        </div>

        <div class="cart-actions">
            <button class="delete-btn" type="button">Delete</button>
            <button class="pay-btn" type="button">Pay Now</button>
        </div>

    </div>

    <div class="cart-container">

        <div class="cart-shop-header">
            <div class="cart-shop-logo">L</div>
            <h3 class="cart-shop-name">Name of the Shop</h3>
        </div>

        <div class="cart-products">

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 1</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 2</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 3</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

        </div>

        <div class="cart-summary">

            <div class="cart-fees">
                <div class="cart-fee-line">Delivery Fee: <span class="fee-amount">+ ₱60</span></div>
                <div class="cart-fee-line">Discount: <span class="fee-amount">− ₱20</span></div>
            </div>

        </div>

        <div class="cart-total">
            <div class="total-label">Total Price</div>
            <div class="total-box">₱ 0.00</div>
        </div>

        <div class="cart-actions">
            <button class="delete-btn" type="button">Delete</button>
            <button class="pay-btn" type="button">Pay Now</button>
        </div>

    </div>

    <div class="cart-container">

        <div class="cart-shop-header">
            <div class="cart-shop-logo">L</div>
            <h3 class="cart-shop-name">Name of the Shop</h3>
        </div>

        <div class="cart-products">

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 1</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 2</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

            <div class="cart-item">
                <div class="qty-control">
                    <button class="qty-btn">-</button>
                    <span class="qty-label">Q: 1</span>
                    <button class="qty-btn">+</button>
                </div>
                <div class="cart-item-name">Product 3</div>
                <div class="cart-item-price">₱ Price</div>
            </div>

        </div>

        <div class="cart-summary">

            <div class="cart-fees">
                <div class="cart-fee-line">Delivery Fee: <span class="fee-amount">+ ₱60</span></div>
                <div class="cart-fee-line">Discount: <span class="fee-amount">− ₱20</span></div>
            </div>

        </div>

        <div class="cart-total">
            <div class="total-label">Total Price</div>
            <div class="total-box">₱ 0.00</div>
        </div>

        <div class="cart-actions">
            <button class="delete-btn" type="button">Delete</button>
            <button class="pay-btn" type="button">Pay Now</button>
        </div>

    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Delete all items in your cart?</p>
            <button type="button" class="modal-delete-ok">OK</button>
        </div>
    </div>

    <div id="payModal" class="modal">
        <div class="modal-content">
            <p>Proceed to payment?</p>
            <button type="button" class="modal-pay-ok">OK</button>
        </div>
    </div>

</main>

<script src="../../Javascript/cart.js"></script>

</body>
</html>
