<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .product-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .stock-level {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Low Stock Alert</h2>

        <div class="alert-box">
            <p><strong>Warning:</strong> The following product has low stock and needs to be restocked soon.</p>
        </div>

        <div class="product-details">
            <h3>{{ $product->name }}</h3>
            <p><strong>Current Stock Level:</strong> <span class="stock-level">{{ $product->stock_quantity }}</span></p>
            <p><strong>Product ID:</strong> {{ $product->id }}</p>
            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p><strong>Description:</strong> {{ $product->description }}</p>
        </div>

        <p>Please restock this product as soon as possible to avoid stock-outs.</p>

        <p style="color: #666; font-size: 12px; margin-top: 30px;">
            This is an automated notification from your E-Commerce Cart system.
        </p>
    </div>
</body>
</html>
