<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .total-sales {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Daily Sales Report</h2>
            <p>{{ $date }}</p>
        </div>

        <div class="summary-box">
            <h3>Summary</h3>
            <p><strong>Total Orders:</strong> {{ count($orders) }}</p>
            <p><strong>Total Sales:</strong> <span class="total-sales">${{ number_format($totalSales, 2) }}</span></p>
        </div>

        @if(count($orders) > 0)
            <h3>Order Details</h3>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span style="
                                    background-color: {{ $order->status === 'completed' ? '#28a745' : '#ffc107' }};
                                    color: white;
                                    padding: 4px 8px;
                                    border-radius: 4px;
                                    font-size: 12px;
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="summary-box">
                <p style="text-align: center; color: #666;">No sales recorded for this day.</p>
            </div>
        @endif

        <p style="color: #666; font-size: 12px; margin-top: 30px;">
            This is an automated daily sales report from your E-Commerce Cart system.
        </p>
    </div>
</body>
</html>
