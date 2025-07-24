<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 40px;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #28a745; text-align: center;">✅ Payment Completed Successfully</h2>

        <p style="font-size: 16px; color: #333;">Thank you for your payment! Your account has been created with the following credentials:</p>

        <table style="width: 100%; margin-top: 20px; font-size: 16px;">
            <tr>
                <td style="font-weight: bold;">📧 Email:</td>
                <td>{{ $email }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">🔐 Password:</td>
                <td>{{ $password }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('login') }}" style="display: inline-block; padding: 12px 24px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 6px;">Login Now</a>
        </div>

        <p style="margin-top: 30px; font-size: 14px; color: #666;">📝 For security, we recommend changing your password after logging in.</p>
    </div>
</body>
</html>
