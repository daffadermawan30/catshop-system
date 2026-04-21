<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .header { background: #ea580c; padding: 24px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0 0; opacity: 0.85; font-size: 13px; }
        .body { padding: 28px 24px; }
        .detail-box { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 16px; margin: 16px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; border-bottom: 1px solid #ffedd5; }
        .detail-row:last-child { border: none; font-weight: bold; }
        .detail-label { color: #666; }
        .detail-value { color: #333; font-weight: 500; }
        .cta { text-align: center; margin: 20px 0; }
        .btn { background: #ea580c; color: white; padding: 12px 28px; border-radius: 50px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; }
        .footer { background: #f3f4f6; padding: 16px 24px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🐱 CatShop</h1>
        <p>Booking Dikonfirmasi!</p>
    </div>

    <div class="body">
        <p>Halo <strong>{{ $customerName }}</strong>!</p>
        <p>Booking <strong>{{ $serviceType }}</strong> untuk <strong>{{ $catName }}</strong> telah berhasil dikonfirmasi. Berikut detail booking Anda:</p>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">🐾 Kucing</span>
                <span class="detail-value">{{ $catName }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📦 Layanan</span>
                <span class="detail-value">{{ $packageName }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📅 Tanggal</span>
                <span class="detail-value">{{ $date }}</span>
            </div>
            @if($time)
            <div class="detail-row">
                <span class="detail-label">🕐 Waktu</span>
                <span class="detail-value">{{ $time }}</span>
            </div>
            @endif
            @if($estimatedCost > 0)
            <div class="detail-row">
                <span class="detail-label">💰 Estimasi Biaya</span>
                <span class="detail-value">Rp {{ number_format($estimatedCost, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        <p style="font-size:13px; color:#666;">
            Mohon datang tepat waktu. Jika ada perubahan atau pertanyaan,
            hubungi kami via WhatsApp atau balas email ini.
        </p>

        <div class="cta">
            <a href="{{ route('public.booking') }}" class="btn">Lihat Detail Booking</a>
        </div>
    </div>

    <div class="footer">
        © {{ date('Y') }} CatShop — Pet Shop Khusus Kucing<br>
        Email ini dikirim otomatis, harap tidak membalas.
    </div>
</div>
</body>
</html>
