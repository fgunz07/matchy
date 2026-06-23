<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background-color: #1f2937; font-family: Arial, sans-serif; }
        table { border-spacing: 0; border-collapse: collapse; }
        .wrapper { width: 100%; background-color: #1f2937; }
        .container { width: 100%; max-width: 500px; margin: 0 auto; background-color: #374151; }
        .content { padding: 30px; color: #e5e7eb; }
        .hero-text { font-size: 20px; font-weight: bold; color: #ffffff; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #60a5fa; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        .footer { width: 100%; max-width: 500px; margin: 20px auto 0 auto; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table class="container" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content">
                            @yield('content')

                            <p style="margin-top: 30px; font-size: 14px;">
                                Best regards,<br>
                                {{ config('app.name') }} Team
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="footer" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 10px 15px;">
                            Sent by <strong>{{ config('app.name') }}</strong> • 123 Maple Street, Apt 4B, Springfield, IL 62704, USA
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>