<?php
session_start();
require_once "../../Connection/connection.php"; 

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ../../Main/Index.php");
    exit();
}

$username = $_SESSION["username"] ?? "Admin";

// Handle Add Shop Category
if (isset($_POST['add_category'])) {
    $shopName = $_POST['shopName'];
    $shopCategory = $_POST['shopCategory'];

    if (isset($_FILES['shopImage']) && $_FILES['shopImage']['error'] === 0) {
        $fileTmp = $_FILES['shopImage']['tmp_name'];
        $fileName = uniqid() . "_" . basename($_FILES['shopImage']['name']);
        $fileDestination = "../../images/shops/" . $fileName;

        if (move_uploaded_file($fileTmp, $fileDestination)) {
            $stmt = $conn->prepare("INSERT INTO shops (shopName, shopCategory, shopImage) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $shopName, $shopCategory, $fileName);
            $stmt->execute();
            $stmt->close();

            $conn->close();   // Close DB connection

            header("Location: Admin_ManageCategory.php");  
            exit();
        }
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $result = $conn->query("SELECT shopImage FROM shops WHERE shopID=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = "../../images/shops/" . $row['shopImage'];
        if (file_exists($imagePath)) unlink($imagePath);
    }
    $conn->query("DELETE FROM shops WHERE shopID=$id");
    header("Location: Admin_ManageCategory.php");
    exit();
}

// Handle Edit
if (isset($_POST['edit_category'])) {
    $id = $_POST['shopID'];
    $shopName = $_POST['shopName'];
    $shopCategory = $_POST['shopCategory'];

    if (isset($_FILES['shopImage']) && $_FILES['shopImage']['error'] === 0) {
        $result = $conn->query("SELECT shopImage FROM shops WHERE shopID=$id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $oldImage = "../../images/shops/" . $row['shopImage'];
            if (file_exists($oldImage)) unlink($oldImage);
        }

        $fileTmp = $_FILES['shopImage']['tmp_name'];
        $fileName = uniqid() . "_" . basename($_FILES['shopImage']['name']);
        $fileDestination = "../../images/shops/" . $fileName;
        move_uploaded_file($fileTmp, $fileDestination);

        $conn->query("UPDATE shops SET shopName='$shopName', shopCategory='$shopCategory', shopImage='$fileName' WHERE shopID=$id");
    } else {
        $conn->query("UPDATE shops SET shopName='$shopName', shopCategory='$shopCategory' WHERE shopID=$id");
    }

    header("Location: Admin_ManageCategory.php");
    exit();
}

$shops = $conn->query("SELECT * FROM shops ORDER BY shopID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Shop Category | Admin</title>
<link rel="shortcut icon" href="../../images/e-baon-logo.png">
<link rel="stylesheet" href="../../Css/Admin.css">
<link rel="stylesheet" href="../../Css/Admin_css/Admin_ManageCategory.css">
<link rel="stylesheet" href="../../Css/DisableStyles.css">
</head>
<body class="admin-body">

<header class="admin-head noselect">
    <div class="admin-head-text noselect"><h3>E-Baon</h3><p>Admin</p></div>
    <button class="sidebar-toggle noselect" onclick="toggleSidebar()">☰</button>
</header>

<div class="admin-container">

<aside class="admin-sidebar noselect" id="sidebar">
    <img class="admin-logo-box noselect" src="../../images/e-baon-logo.png" alt="E-Baon Logo">
    <nav class="admin-menu noselect">
        <a href="../../Main/Admin.php" class="admin-menu-item" data-tooltip="Dashboard">
            <span class="admin-menu-item-icon">📊</span>
            <span class="admin-menu-item-text">Dashboard</span>
        </a>
        <a href="../Body/Admin/Admin_ManageOrder.php" class="admin-menu-item" data-tooltip="Manage Order">
            <span class="admin-menu-item-icon">📋</span>
            <span class="admin-menu-item-text">Manage Order</span>
        </a>
        <a href="../../Body/Admin/Admin_ManageCategory.php" class="admin-menu-item" data-tooltip="Manage Shop Category">
            <span class="admin-menu-item-icon">🏪</span>
            <span class="admin-menu-item-text">Manage Category</span>
        </a>
        <a href="../Body/Admin/Admin_ManageProduct.php" class="admin-menu-item" data-tooltip="Manage Product">
            <span class="admin-menu-item-icon">📦</span>
            <span class="admin-menu-item-text">Manage Product</span>
        </a>
        <a href="../Body/Admin/Admin_ManageCustomer.php" class="admin-menu-item" data-tooltip="Manage Customer">
            <span class="admin-menu-item-icon">🙎🏻‍♂️</span>
            <span class="admin-menu-item-text">Manage Customer</span>
        </a>
        <a href="../Body/Admin/Admin_ManageDelivery.php" class="admin-menu-item" data-tooltip="Manage Delivery">
            <span class="admin-menu-item-icon">🛵</span>
            <span class="admin-menu-item-text">Manage Delivery D.</span>
        </a>
        <a href="../Body/Admin/Admin_ManageAdminAcc.php" class="admin-menu-item" data-tooltip="Manage Admin">
            <span class="admin-menu-item-icon">⚙️</span>
            <span class="admin-menu-item-text">Manage Admin Acc.</span>
        </a>
    </nav>
    <div class="admin-bottom-bar">
        <a href="../../Main/Logout.php" class="admin-logout">LOGOUT</a>
    </div>
</aside>

<main class="admin-main-content">

<h2>Add Shop Category</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Shop Name:</label><br>
    <input type="text" name="shopName" required><br><br>
    <label>Shop Category:</label><br>
    <input type="text" name="shopCategory" required><br><br>
    
    <label>Shop Image:</label><br>
    <div id="dropArea" class="drop-area">
        Drag & Drop Image Here or Click to Upload
        <input type="file" name="shopImage" accept="image/*" id="fileInput" style="display:none" required>
        <img id="preview" class="preview-img" src="" style="display:none;">
    </div><br>

    <button type="submit" name="add_category">Add Category</button>
</form>

<h2>Existing Shop Categories</h2>
<table class="shop-table">
<tr><th>ID</th><th>Name</th><th>Category</th><th>Image</th><th>Actions</th></tr>
<?php while($row = $shops->fetch_assoc()): ?>
<tr>
    <td><?= $row['shopID'] ?></td>
    <td><?= $row['shopName'] ?></td>
    <td><?= $row['shopCategory'] ?></td>
    <td><img src="../../images/shops/<?= $row['shopImage'] ?>" class="shop-img-preview"></td>
    <td>
        <button class="action-btn edit-btn" onclick="openModal(<?= $row['shopID'] ?>,'<?= addslashes($row['shopName']) ?>','<?= addslashes($row['shopCategory']) ?>','<?= $row['shopImage'] ?>')">Edit</button>
        <a href="?delete_id=<?= $row['shopID'] ?>" onclick="return confirm('Delete this shop?')" class="action-btn delete-btn">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Edit Shop</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="shopID" id="modalShopID">
            <label>Shop Name:</label><br>
            <input type="text" name="shopName" id="modalShopName" required><br><br>
            <label>Shop Category:</label><br>
            <input type="text" name="shopCategory" id="modalShopCategory" required><br><br>
            <label>Shop Image:</label><br>
            <div id="editDropArea" class="drop-area">
                Drag & Drop Image or Click
                <input type="file" name="shopImage" accept="image/*" id="editFileInput" style="display:none">
                <img id="modalPreview" class="preview-img" src=""><br>
                <button type="button" id="resetImageBtn">Reset Image</button>
            </div>
            <button type="submit" name="edit_category">Save Changes</button>
        </form>
    </div>
</div>

</main>
</div>

<script>
const sidebar = document.getElementById('sidebar');
function toggleSidebar(){ sidebar.classList.toggle('collapsed'); }

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
let modalShopID = document.getElementById('modalShopID');
let modalShopName = document.getElementById('modalShopName');
let modalShopCategory = document.getElementById('modalShopCategory');

function openModal(id,name,category,image){
    modal.style.display='flex';
    modalShopID.value=id;
    modalShopName.value=name;
    modalShopCategory.value=category;
    modalPreview.src="../images/shops/"+image;
}
function closeModal(){ modal.style.display='none'; }
window.onclick = function(e){ if(e.target==modal) closeModal(); }

</script>
</body>
</html>