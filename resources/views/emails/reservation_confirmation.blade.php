<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
        }
        .reservation-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            color: #007bff;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status.confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MrcMS - Rental Car Management</h1>
        <h2>Reservation Confirmation</h2>
    </div>

    <div class="content">
        <p>Dear {{ $reservation->client->full_name }},</p>
        
        <p>Thank you for your reservation request. Here are the details of your booking:</p>

        <div class="reservation-details">
            <div class="detail-row">
                <span class="label">Reservation ID:</span>
                <span class="value">#{{ $reservation->id }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Car:</span>
                <span class="value">{{ $reservation->car->full_name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">License Plate:</span>
                <span class="value">{{ $reservation->car->mat }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Start Date:</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">End Date:</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Duration:</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservation->start_date)->diffInDays(\Carbon\Carbon::parse($reservation->end_date)) }} day(s)</span>
            </div>
            <div class="detail-row">
                <span class="label">Total Price:</span>
                <span class="value">€{{ number_format($reservation->total_price, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="status {{ strtolower($reservation->status) }}">{{ ucfirst($reservation->status) }}</span>
            </div>
        </div>

        @if($reservation->status === 'pending')
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <strong>Important:</strong> Your reservation is currently pending approval. We will contact you within 24 hours to confirm your booking.
            </div>
        @elseif($reservation->status === 'confirmed')
            <div style="background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <strong>Confirmed!</strong> Your reservation has been confirmed. Please arrive at our location 15 minutes before your scheduled pickup time.
            </div>
        @endif

        <h3>Contact Information</h3>
        <p>If you have any questions or need to modify your reservation, please contact us:</p>
        <ul>
            <li><strong>Phone:</strong> +216 70 123 456</li>
            <li><strong>Email:</strong> info@mrcms.tn</li>
            <li><strong>Address:</strong> 123 Avenue Habib Bourguiba, Tunis, Tunisia</li>
        </ul>

        <h3>Important Notes</h3>
        <ul>
            <li>Please bring a valid driver's license and ID card</li>
            <li>A security deposit may be required at pickup</li>
            <li>Fuel should be returned at the same level as pickup</li>
            <li>Late returns may incur additional charges</li>
        </ul>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} MrcMS - Rental Car Management System</p>
        <p>Thank you for choosing our services!</p>
    </div>
</body>
</html>