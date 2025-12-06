<?php
    session_start();

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "delivery") {
        header("Location: ../../Main/Index.php");
        exit();
    }

    require_once "../../Connnection/Connection.php";

    $deliveryId = (int)($_SESSION["user_id"] ?? 0);

    $user = [
        "id"        => "",
        "full_name" => "",
        "email"     => "",
        "password"  => "",
        "role"      => ""
    ];

    $latestFromAddress = "";
    $latestToAddress   = "";
    $updateMessage     = "";

    if ($deliveryId > 0 && $_SERVER["REQUEST_METHOD"] === "POST") {
        $fullName = trim($_POST["full_name"] ?? "");
        $email    = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");

        if ($fullName === "" || $email === "" || $password === "") {
            $updateMessage = "All required fields must be filled.";
        } else {
            try {
                $stmt = $conn->prepare("
            UPDATE users
            SET full_name = :full_name,
                email     = :email,
                password  = :password
            WHERE id = :id
        ");
                $stmt->bindParam(":full_name", $fullName, PDO::PARAM_STR);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":password", $password, PDO::PARAM_STR);
                $stmt->bindParam(":id", $deliveryId, PDO::PARAM_INT);
                $stmt->execute();
                $updateMessage = "Profile updated successfully.";
            } catch (PDOException $e) {
                $updateMessage = "Failed to update profile.";
            }
        }
    }

    if ($deliveryId > 0) {
        try {
            $stmt = $conn->prepare("
        SELECT id, full_name, email, password, role
        FROM users
        WHERE id = :id
        LIMIT 1
    ");
            $stmt->bindParam(":id", $deliveryId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $user = $row;
            }
        } catch (PDOException $e) {
        }
    }

    if ($deliveryId > 0) {
        try {
            $stmt = $conn->prepare("
        SELECT from_address, to_address
        FROM orders
        WHERE delivery_id = :did
        ORDER BY id DESC
        LIMIT 1
    ");
            $stmt->bindParam(":did", $deliveryId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                if (!empty($row["from_address"])) {
                    $latestFromAddress = $row["from_address"];
                }
                if (!empty($row["to_address"])) {
                    $latestToAddress = $row["to_address"];
                }
            }
        } catch (PDOException $e) {
            $latestFromAddress = "";
            $latestToAddress   = "";
        }
    }

    function e($value) {
        return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
    }
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Delivery Profile</title>
            <link rel="stylesheet" href="../../Css/Delivery/Profile_D.css">
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script src="../../Javascript/Delivery/Profile_D.js" defer></script>
        </head>
        <body class="profile-body">

            <?php if ($updateMessage !== ""): ?>
                <div class="profile-modal-backdrop" id="profile-modal">
                    <div class="profile-modal-box">
                        <div class="profile-modal-text"><?php echo e($updateMessage); ?></div>
                        <button
                            type="button"
                            class="profile-modal-btn"
                            id="profile-modal-ok"
                            onclick="document.getElementById('profile-modal').style.display='none';"
                        >Ok</button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="profile-shell">

                <aside class="delivery-sidebar">
                    <div class="delivery-sidebar-logo">
                        <div class="delivery-logo-img-wrap">
                            <img src="../../Image/Admin/logo.png" class="delivery-logo-img" alt="E-Baon Logo">
                        </div>
                        <div class="delivery-logo-text-group">
                            <div class="delivery-logo-text-main">E-Baon</div>
                            <div class="delivery-logo-text-sub">Delivery</div>
                        </div>
                    </div>

                    <nav class="delivery-nav">
                        <button type="button" class="delivery-nav-item" data-action="dashboard">
                            Dashboard
                        </button>
                        <button type="button" class="delivery-nav-item delivery-nav-item-active" data-action="profile">
                            Profile
                        </button>
                    </nav>

                    <a href="../../Main/Logout.php" class="delivery-logout-link">Logout</a>
                </aside>

                <main class="profile-main">
                    <div class="profile-layout">
                        <section class="profile-right-panel">
                            <h2 class="profile-section-title">Account</h2>

                            <form method="post" class="profile-form" enctype="multipart/form-data">

                                <div class="profile-top-row">
                                    <div class="profile-photo-block">
                                        <div class="profile-photo-preview" id="profile-photo-preview">
                                            <span class="profile-photo-initial" id="profile-photo-initial">
                                                <?php echo strtoupper(substr($user["full_name"] !== "" ? $user["full_name"] : "D", 0, 1)); ?>
                                            </span>
                                        </div>

                                        <input type="file" id="profile-photo-input" accept="image/*" hidden>

                                        <div class="profile-photo-actions">
                                            <button type="button" class="profile-photo-btn" id="profile-photo-btn">Change photo</button>
                                            <button type="button" class="profile-photo-link" id="profile-photo-remove">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="profile-two-column">
                                    <div class="profile-field">
                                        <label for="profile-full-name">Username</label>
                                        <input type="text" id="profile-full-name" name="full_name" value="<?php echo e($user["full_name"]); ?>">
                                    </div>

                                    <div class="profile-field">
                                        <label for="profile-phone">Phone</label>
                                        <input type="text" id="profile-phone" name="phone" placeholder="+63">
                                    </div>
                                </div>

                                <div class="profile-two-column">
                                    <div class="profile-field">
                                        <label for="profile-email">Email</label>
                                        <input type="email" id="profile-email" name="email" value="<?php echo e($user["email"]); ?>">
                                    </div>

                                    <div class="profile-field profile-password-field">
                                        <label for="profile-password">Password</label>
                                        <div class="profile-password-wrapper">
                                            <input type="password" id="profile-password" name="password" value="<?php echo e($user["password"]); ?>">
                                            <button type="button" class="profile-password-toggle" id="profile-password-toggle">Show</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="profile-location-row">
                                    <div class="profile-location-card">
                                        <div class="profile-location-header">
                                            <div class="profile-location-title">Your Location</div>
                                            <div class="profile-status-label" id="profile-map-status"></div>
                                        </div>
                                        <div
                                            class="profile-location-map"
                                            id="profile-location-map"
                                            data-from="<?php echo e($latestFromAddress); ?>"
                                            data-to="<?php echo e($latestToAddress); ?>"
                                        >
                                            <?php echo $latestToAddress !== "" ? "" : "No recent delivery address."; ?>
                                        </div>
                                    </div>

                                    <div class="profile-address-card">
                                        <div class="profile-location-header">
                                            <div class="profile-location-title">Address Details</div>
                                            <div class="profile-status-label" id="profile-address-status"></div>
                                        </div>
                                        <textarea
                                            class="profile-address-text"
                                            id="profile-address-text"
                                            readonly
                                        ><?php echo e($latestToAddress); ?></textarea>
                                    </div>
                                </div>

                                <div class="profile-save-row">
                                    <button type="submit" class="profile-save-btn">Save Setting</button>
                                </div>

                            </form>
                        </section>
                    </div>
                </main>
            </div>

        </body>
</html>
