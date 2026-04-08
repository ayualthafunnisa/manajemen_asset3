<x-mail::message>
# Halo **{{ $name }}**! 💰

Pembayaran Anda telah **dikonfirmasi** oleh sistem!

## 📊 Detail Pembayaran:

| | |
|---|---|
| **Order ID** | `{{ $orderId }}` |
| **Nominal** | {{ $amount }} |
| **Metode** | {{ ucfirst($paymentType) }} |
| **Tanggal** | {{ $paidAt }}  |

## 🔑 Lisensi Aktif:

| | |
|---|---|
| **License Key** | `{{ $licenseKey }}` |
| **Berlaku Hingga** | {{ $expiredDate }} |

<x-mail::button :url="route('dashboard.admin')" color="success">
📊 Buka Dashboard
</x-mail::button>

Terima kasih telah melakukan pembayaran!  
Lisensi Anda sudah aktif dan siap digunakan.

Best regards,  
**Tim {{ config('app.name') }}**
</x-mail::message>