<!DOCTYPE html>
<html>
<head>
    <title>Reservation Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content p { margin-bottom: 15px; }
        .content strong { color: #555; }
        .footer { text-align: center; margin-top: 20px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservation Confirmed!</h1>
        </div>
        <div class="content">
            <p>Dear {{ $reservation->client->f_name ?? 'Client' }},</p>
            <p>We are pleased to inform you that your reservation (ID: <strong>{{ $reservation->id }}</strong>) has been confirmed.</p>
            
            <p><strong>Reservation Details:</strong></p>
            <ul>
                <li><strong>Car:</strong> {{ $reservation->car->modele->marque->name ?? 'N/A' }} {{ $reservation->car->modele->name ?? 'N/A' }} ({{ $reservation->car->mat ?? 'N/A' }})</li>
                <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($reservation->start_date)->format('d M Y, H:i A') }}</li>
                <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($reservation->end_date)->format('d M Y, H:i A') }}</li>
                <li><strong>Total Price:</strong> {{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' EUR' : 'N/A' }}</li>
            </ul>

            <p>Thank you for choosing our service!</p>
            <p>If you have any questions, please contact us.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MrcMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
