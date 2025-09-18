<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .thanks-container{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .thankyou-box {
            margin-top: 7rem;
            margin-bottom: 4rem;
            background: #dff5e2;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .thankyou-box h1 {
            color: #28a745;
            font-size: 2rem;
            margin-bottom: 15px;
        }
        .thankyou-box p {
            font-size: 1.1rem;
        }
        .thankyou-box a {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="wrap-header-cart js-panel-cart">
	<div class="s-full js-hide-cart"></div>
	
    </div>
    <div class="thanks-container">
        <div class="thankyou-box">
            <h1>🎉 Thank You!</h1>
            <p>Your order has been placed successfully.</p>
            <a href="index.php" class="btn btn-success">Continue Shopping</a>
        </div>
    </div>
</body>
</html>
<?php include 'includes/footer.php'; ?>