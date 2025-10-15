<?php
$currentPage = basename($_SERVER['PHP_SELF']); // e.g., 'products.php'
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-nav .nav-link.active {
            background-color: black; /* blue */
            color: white !important;
            border-radius: 5px;
        }
        .navbar-nav .nav-link {
            padding: 8px 15px;
            transition: 0.3s;
        }
    </style>
</head>
<body class="container mt-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#" style="margin-left: 15px;">AdminPanel</a>
        <div class="collapse navbar-collapse" id="navbarText" style="margin-left: 40%;">
            <ul class="navbar-nav mr-right">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'products.php' ? 'active' : '' ?>" href="products.php">Manage Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'customers.php' ? 'active' : '' ?>" href="customers.php">Manage Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'logout.php' ? 'active' : '' ?>" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</body>
</html>
