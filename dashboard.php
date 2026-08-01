<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "config/database.php";

// Statistik
$product = mysqli_query($conn, "SELECT COUNT(*) total FROM products");
$totalProduct = mysqli_fetch_assoc($product)['total'];

$stock = mysqli_query($conn, "SELECT SUM(stock) total FROM products");
$totalStock = mysqli_fetch_assoc($stock)['total'];

$production = mysqli_query($conn, "SELECT COUNT(*) total FROM production");
$totalProduction = mysqli_fetch_assoc($production)['total'];

$low = mysqli_query($conn, "SELECT COUNT(*) total FROM products WHERE stock < 10");
$lowStock = mysqli_fetch_assoc($low)['total'];

// Produk stok menipis
$lowProduct = mysqli_query($conn, "
SELECT *
FROM products
WHERE stock < 10
LIMIT 5
");

// Produksi terbaru
$recent = mysqli_query($conn, "
SELECT
production.*,
products.product_name
FROM production
JOIN products
ON production.product_id=products.id
ORDER BY production.id DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <div class="sidebar">

        <h3>IPMS</h3>

        <p>Inventory System</p>

        <hr>

        <a href="dashboard.php"><i class="bi bi-grid"></i> Dashboard</a>

        <a href="products/index.php"><i class="bi bi-box"></i> Products</a>

        <a href="production/index.php"><i class="bi bi-building"></i> Production</a>

        <a href="reports/index.php"><i class="bi bi-file-earmark-text"></i> Reports</a>

        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

    </div>

    <div class="content">

        <div class="topbar">

            <div>

                <h3>Dashboard</h3>

                <p>Welcome back, <b>
                        <?= $_SESSION['name']; ?>
                    </b></p>

            </div>

            <div>

                <span class="badge bg-primary p-2">

                    Administrator

                </span>

            </div>

        </div>

        <div class="row mt-4">

            <div class="col-md-3">

                <div class="card dashboard-card border-0">

                    <div class="card-body">

                        <i class="bi bi-box card-icon text-primary"></i>

                        <h6>Total Product</h6>

                        <h2>
                            <?= $totalProduct ?>
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card dashboard-card border-0">

                    <div class="card-body">

                        <i class="bi bi-archive card-icon text-success"></i>

                        <h6>Total Stock</h6>

                        <h2>
                            <?= $totalStock ?: 0 ?>
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card dashboard-card border-0">

                    <div class="card-body">

                        <i class="bi bi-gear card-icon text-warning"></i>

                        <h6>Production</h6>

                        <h2>
                            <?= $totalProduction ?>
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card dashboard-card border-0">

                    <div class="card-body">

                        <i class="bi bi-exclamation-circle card-icon text-danger"></i>

                        <h6>Low Stock</h6>

                        <h2>
                            <?= $lowStock ?>
                        </h2>

                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-4">

            <div class="col-md-7">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5>Recent Production</h5>

                    </div>

                    <div class="card-body">

                        <table class="table">

                            <thead>

                                <tr>

                                    <th>Product</th>

                                    <th>Qty</th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php while ($r = mysqli_fetch_assoc($recent)) { ?>

                                    <tr>

                                        <td>
                                            <?= $r['product_name'] ?>
                                        </td>

                                        <td>
                                            <?= $r['quantity'] ?>
                                        </td>

                                        <td>

                                            <?php

                                            if ($r['status'] == "Completed") {

                                                echo "<span class='badge bg-success'>Completed</span>";

                                            } else {

                                                echo "<span class='badge bg-warning text-dark'>Pending</span>";

                                            }

                                            ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="col-md-5">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5>Low Stock Alert</h5>

                    </div>

                    <div class="card-body">

                        <table class="table">

                            <thead>

                                <tr>

                                    <th>Product</th>

                                    <th>Stock</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php while ($l = mysqli_fetch_assoc($lowProduct)) { ?>

                                    <tr>

                                        <td>
                                            <?= $l['product_name'] ?>
                                        </td>

                                        <td>

                                            <span class="badge bg-danger">

                                                <?= $l['stock'] ?>

                                            </span>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Logout</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to logout?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <a href="logout.php" class="btn btn-danger">
                        Logout
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>