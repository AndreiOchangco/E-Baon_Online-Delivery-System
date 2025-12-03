<?php
session_start();
require_once "../../Connection/connection.php";

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ../../Main/Index.php");
    exit();
}

$username = $_SESSION["username"] ?? "Admin";

// Fetch all products
$products = $conn->query("SELECT * FROM products ORDER BY productID DESC");

// Handle Add Product
if(isset($_POST['add_product'])){
    $shopName = $_POST['shopName'];
    $shopCategory = $_POST['shopCategory'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productQuantity = $_POST['productQuantity'];

    if(isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0){
        $fileTmp = $_FILES['productImage']['tmp_name'];
        $fileName = uniqid() . "_" . basename($_FILES['productImage']['name']);
        $fileDestination = "../../images/products/" . $fileName;

        if(move_uploaded_file($fileTmp, $fileDestination)){
            $stmt = $conn->prepare("INSERT INTO products (product_image, shopName, shopCategory, productName, productPrice, productQuantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssii", $fileName, $shopName, $shopCategory, $productName, $productPrice, $productQuantity);
            $stmt->execute();
            $stmt->close();

            header("Location: Admin_ManageProduct.php");
            exit();
        }
    }
}

// Handle Delete Product
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    $result = $conn->query("SELECT product_image FROM products WHERE productID=$id");
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $imagePath = "../../images/products/" . $row['product_image'];
        if(file_exists($imagePath)) unlink($imagePath);
    }
    $conn->query("DELETE FROM products WHERE productID=$id");
    header("Location: Admin_ManageProduct.php");
    exit();
}

// Handle Edit Product
if(isset($_POST['edit_product'])){
    $id = $_POST['productID'];
    $shopName = $_POST['shopName'];
    $shopCategory = $_POST['shopCategory'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productQuantity = $_POST['productQuantity'];

    if(isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0){
        $result = $conn->query("SELECT product_image FROM products WHERE productID=$id");
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $oldImage = "../../images/products/" . $row['product_image'];
            if(file_exists($oldImage)) unlink($oldImage);
        }

        $fileTmp = $_FILES['productImage']['tmp_name'];
        $fileName = uniqid() . "_" . basename($_FILES['productImage']['name']);
        move_uploaded_file($fileTmp, "../../images/products/" . $fileName);

        $conn->query("UPDATE products SET product_image='$fileName', shopName='$shopName', shopCategory='$shopCategory', productName='$productName', productPrice=$productPrice, productQuantity=$productQuantity WHERE productID=$id");
    } else {
        $conn->query("UPDATE products SET shopName='$shopName', shopCategory='$shopCategory', productName='$productName', productPrice=$productPrice, productQuantity=$productQuantity WHERE productID=$id");
    }

    header("Location: Admin_ManageProduct.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Product | Admin</title>
    <link rel="shortcut icon" href="../../images/e-baon-logo.png">
    <link rel="stylesheet" href="../../Css/Admin.css">
    <link rel="stylesheet" href="../Css/DisableStyles.css">
    <link rel="stylesheet" href="../../Css/Admin_css/Admin_ManageProduct.css">
</head>

<body class="admin-body">
    <header class="admin-head">
        <div class="admin-head-text">
            <h3>E-Baon</h3>
            <p>Admin</p>
        </div>

        <!-- Sidebar Toggle -->
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
    </header>

    <div class="admin-container">

        <!-- LEFT SIDEBAR -->
        <aside class="admin-sidebar" id="sidebar">
            <!-- Logo at top -->
            <img class="admin-logo-box" src="../../images/e-baon-logo.png" alt="E-Baon Logo">

            <!-- MENU ITEMS -->
            <nav class="admin-menu">

                <a href="../../Main/Admin.php"
                class="admin-menu-item"
                data-tooltip="Dashboard">
                    <span class="admin-menu-item-icon">📊</span>
                    <span class="admin-menu-item-text">Dashboard</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageOrder.php"
                class="admin-menu-item"
                data-tooltip="Manage Order">
                    <span class="admin-menu-item-icon">📋</span>
                    <span class="admin-menu-item-text">Manage Order</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageCategory.php"
                class="admin-menu-item"
                data-tooltip="Manage Shop Category">
                    <span class="admin-menu-item-icon">🏪</span>
                    <span class="admin-menu-item-text">Manage Category</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageProduct.php"
                class="admin-menu-item"
                data-tooltip="Manage Product">
                    <span class="admin-menu-item-icon">📦</span>
                    <span class="admin-menu-item-text">Manage Product</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageCustomer.php"
                class="admin-menu-item"
                data-tooltip="Manage Customer">
                    <span class="admin-menu-item-icon">🙎🏻‍♂️</span>
                    <span class="admin-menu-item-text">Manage Customer</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageDelivery.php"
                class="admin-menu-item"
                data-tooltip="Manage Delivery">
                    <span class="admin-menu-item-icon">🛵</span>
                    <span class="admin-menu-item-text">Manage Delivery D.</span>
                </a>

                <a href="../../Body/Admin/Admin_ManageAdminAcc.php"
                class="admin-menu-item"
                data-tooltip="Manage Admin">
                    <span class="admin-menu-item-icon">⚙️</span>
                    <span class="admin-menu-item-text">Manage Admin Acc.</span>
                </a>

            </nav>

        <!-- LOGOUT BUTTON -->
        <div class="admin-bottom-bar">
            <a href="../../Main/Admin.php" class="admin-logout">Back to Dashboard</a>
            <a href="../../Main/Logout.php" class="admin-logout">LOGOUT</a>
        </div>

        </aside>


        <!-- MAIN CONTENT AREA -->
        <main class="admin-main-content">
            <div class="product-wrapper">

                <h2>Add Product</h2>
                <div class="product-card">
                <form method="POST" enctype="multipart/form-data">
                    <label>Shop Name:</label><br>
                    <input type="text" name="shopName" required><br><br>

                    <label>Shop Category:</label><br>
                    <input type="text" name="shopCategory" required><br><br>

                    <label>Product Name:</label><br>
                    <input type="text" name="productName" required><br><br>

                    <label>Product Price:</label><br>
                    <input type="number" name="productPrice" required><br><br>

                    <label>Product Quantity:</label><br>
                    <input type="number" name="productQuantity" required><br><br>

                    <label>Product Image:</label><br>
                    <div id="dropArea" class="drop-area">
                        Drag & Drop Image Here or Click to Upload
                        <input type="file" name="productImage" accept="image/*" id="fileInput" style="display:none" required>
                        <img id="preview" class="preview-img" src="" style="display:none;">
                    </div><br>

                    <button type="submit" name="add_product">Add Product</button>
                </form>
                </div>

                <h2>Existing Products</h2>
                <table class="product-table">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Shop Name</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['productID'] ?></td>
                    <td><img src="../../images/products/<?= $row['product_image'] ?>" class="shop-img-preview"></td>
                    <td><?= $row['shopName'] ?></td>
                    <td><?= $row['shopCategory'] ?></td>
                    <td><?= $row['productName'] ?></td>
                    <td><?= number_format($row['productPrice'],2) ?></td>
                    <td><?= $row['productQuantity'] ?></td>
                    <td>
                        <button class="action-btn edit-btn" onclick="openModal(<?= $row['productID'] ?>,'<?= addslashes($row['shopName']) ?>','<?= addslashes($row['shopCategory']) ?>','<?= addslashes($row['productName']) ?>',<?= $row['productPrice'] ?>,<?= $row['productQuantity'] ?>,'<?= $row['product_image'] ?>')">Edit</button>
                        <a href="?delete_id=<?= $row['productID'] ?>" onclick="return confirm('Delete this product?')" class="action-btn delete-btn">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </table>

                <!-- Edit Modal -->
                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <h3>Edit Product</h3>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="productID" id="modalProductID">
                            <label>Shop Name:</label><br>
                            <input type="text" name="shopName" id="modalShopName" required><br><br>

                            <label>Shop Category:</label><br>
                            <input type="text" name="shopCategory" id="modalShopCategory" required><br><br>

                            <label>Product Name:</label><br>
                            <input type="text" name="productName" id="modalProductName" required><br><br>

                            <label>Product Price:</label><br>
                            <input type="number" name="productPrice" id="modalProductPrice" required><br><br>

                            <label>Product Quantity:</label><br>
                            <input type="number" name="productQuantity" id="modalProductQuantity" required><br><br>

                            <label>Product Image:</label><br>
                            <div id="editDropArea" class="drop-area">
                                Drag & Drop Image or Click
                                <input type="file" name="productImage" accept="image/*" id="editFileInput" style="display:none">
                                <img id="modalPreview" class="preview-img" src=""><br>
                                <button type="button" id="resetImageBtn">Reset Image</button>
                            </div>

                            <button type="submit" name="edit_product">Save Changes</button>
                        </form>
                    </div>
                </div>

            </div>
        </main>

    </div>

<!-- SMART COLLAPSE SCRIPT WITH LOCAL STORAGE -->
<script>
const sidebar = document.getElementById('sidebar');
const MOBILE_BREAKPOINT = 768;

/* 1. Disable ALL sidebar animations during initial load */
sidebar.classList.add("no-anim");

/* 2. Apply saved state BEFORE anything renders */
const savedState = localStorage.getItem("adminSidebarState");
if (savedState === "collapsed") {
    sidebar.classList.add("collapsed");
} else {
    sidebar.classList.remove("collapsed");
}

/* 3. Enable animations after load and after the logo finishes layout */
window.addEventListener("load", () => {
    // Give browser a moment to finalize layout to stop flicker
    setTimeout(() => {
        sidebar.classList.remove("no-anim");
        sidebar.classList.add("expanded-ready");
    }, 120);
});

/* SIDEBAR TOGGLE FUNCTION */
function toggleSidebar() {
    if (window.innerWidth < MOBILE_BREAKPOINT) {
        sidebar.classList.toggle("show");
        return;
    }

    const isCollapsed = sidebar.classList.contains("collapsed");

    sidebar.classList.add("transitioning");
    sidebar.classList.remove("expanded-ready");

    if (isCollapsed) {
        /* EXPANDING */
        sidebar.classList.remove("collapsed");

        setTimeout(() => {
            sidebar.classList.remove("transitioning");
            sidebar.classList.add("expanded-ready");
        }, 300);

        localStorage.setItem("adminSidebarState", "expanded");

    } else {
        /* COLLAPSING */
        sidebar.classList.add("collapsed");

        setTimeout(() => {
            sidebar.classList.remove("transitioning");
        }, 300);

        localStorage.setItem("adminSidebarState", "collapsed");
    }
}

/* CLOSE SIDEBAR ON MOBILE CLICK OUTSIDE */
document.addEventListener("click", function(e) {
    if (window.innerWidth >= MOBILE_BREAKPOINT) return;
    if (!sidebar.contains(e.target) && !e.target.classList.contains("sidebar-toggle")) {
        sidebar.classList.remove("show");
    }
});

/* PREVENT BACKGROUND SCROLL WHEN MOBILE SIDEBAR IS OPEN */
const observer = new MutationObserver(() => {
    if (sidebar.classList.contains("show")) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.overflow = "";
    }
});
observer.observe(sidebar, { attributes: true, attributeFilter: ["class"] });
</script>

<script>
// Drag & Drop Add
const dropArea = document.getElementById('dropArea');
const fileInput = document.getElementById('fileInput');
const preview = document.getElementById('preview');

dropArea.addEventListener('click', () => fileInput.click());
dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('hover'); });
dropArea.addEventListener('dragleave', e => { e.preventDefault(); dropArea.classList.remove('hover'); });
dropArea.addEventListener('drop', e => {
    e.preventDefault();
    dropArea.classList.remove('hover');
    const file = e.dataTransfer.files[0];
    if(file){ fileInput.files = e.dataTransfer.files; previewFile(file); }
});

fileInput.addEventListener('change', e => previewFile(e.target.files[0]));
function previewFile(file){
    preview.style.display='block';
    preview.src = URL.createObjectURL(file);
}

// Drag & Drop Edit Modal
const editDrop = document.getElementById('editDropArea');
const editInput = document.getElementById('editFileInput');
const modalPreview = document.getElementById('modalPreview');

editDrop.addEventListener('click', () => editInput.click());
editDrop.addEventListener('dragover', e => { e.preventDefault(); editDrop.classList.add('hover'); });
editDrop.addEventListener('dragleave', e => { e.preventDefault(); editDrop.classList.remove('hover'); });
editDrop.addEventListener('drop', e => {
    e.preventDefault(); editDrop.classList.remove('hover');
    const file = e.dataTransfer.files[0];
    if(file){ editInput.files = e.dataTransfer.files; modalPreview.src = URL.createObjectURL(file); }
});

// Modal Edit
let modal = document.getElementById('editModal');
let modalProductID = document.getElementById('modalProductID');
let modalShopName = document.getElementById('modalShopName');
let modalShopCategory = document.getElementById('modalShopCategory');
let modalProductName = document.getElementById('modalProductName');
let modalProductPrice = document.getElementById('modalProductPrice');
let modalProductQuantity = document.getElementById('modalProductQuantity');

function openModal(id, shopName, shopCategory, productName, price, quantity, image){
    modal.style.display='flex';
    modalProductID.value=id;
    modalShopName.value=shopName;
    modalShopCategory.value=shopCategory;
    modalProductName.value=productName;
    modalProductPrice.value=price;
    modalProductQuantity.value=quantity;
    modalPreview.src="../../images/products/"+image;
}

function closeModal(){ modal.style.display='none'; }
window.onclick = function(e){ if(e.target==modal) closeModal(); }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../Javascript/Chart.js"></script>

</body>
</html>