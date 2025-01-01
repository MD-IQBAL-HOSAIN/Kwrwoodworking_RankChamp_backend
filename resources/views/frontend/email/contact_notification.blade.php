
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }

        .content {
            padding: 20px;
        }

        .message {
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 18px;
        }

        .button:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <p><strong>Name:</strong> {{ $name }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <div class="message">
                <p><strong>Message:</strong></p>
                <p>{{ $sms }}</p>
            </div>

            {{-- <a href="{{ url('/admin/messages') }}" class="button">View Messages</a> --}}
        </div>

        <div class="footer">
            <p>Thank you for using our application!</p>
        </div>
    </div>
</body>
</html>

