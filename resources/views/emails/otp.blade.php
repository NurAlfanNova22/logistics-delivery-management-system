<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Anda</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #ea580c; text-align: center;">Lancar Ekspedisi</h2>
        <p style="font-size: 16px; color: #333;">Halo,</p>
        <p style="font-size: 16px; color: #333;">Anda baru saja meminta untuk mengatur ulang (reset) password akun Anda. Berikut adalah kode OTP 6 digit Anda:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #111; background-color: #f8f9fa; padding: 15px 30px; border-radius: 8px; border: 1px dashed #ccc;">
                {{ $otp }}
            </span>
        </div>
        
        <p style="font-size: 14px; color: #666;">Kode ini hanya berlaku selama 15 menit. <strong>Mohon jangan berikan kode ini kepada siapapun!</strong></p>
        <p style="font-size: 14px; color: #666;">Jika Anda tidak merasa meminta reset password, abaikan email ini.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #999; text-align: center;">&copy; {{ date('Y') }} Lancar Ekspedisi. All rights reserved.</p>
    </div>
</body>
</html>
