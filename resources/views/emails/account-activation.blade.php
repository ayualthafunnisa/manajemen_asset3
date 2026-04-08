<x-mail::message>
# Aktivasi Akun: {{ config('app.name') }}

Halo **{{ $name }}**,

Kami menginformasikan bahwa akun Anda pada sistem **{{ config('app.name') }}** telah berhasil diaktifkan oleh Administrator. Anda kini dapat mengakses seluruh fitur sesuai dengan paket lisensi yang terdaftar.

## Detail Akun & Akses
Untuk keamanan, kami menyarankan Anda untuk segera melakukan perubahan kata sandi setelah berhasil melakukan login pertama kali.

<x-mail::table>
| Informasi | Keterangan |
| :--- | :--- |
| **Nama Pengguna** | {{ $name }} |
| **Email Login** | {{ $email }} |
| **Kata Sandi Sementara** | `{{ $password }}` |
</x-mail::table>

## Informasi Lisensi
Berikut adalah rincian masa berlaku lisensi yang terasosiasi dengan akun Anda:

<x-mail::table>
| Komponen | Detail |
| :--- | :--- |
| **ID Lisensi** | `{{ $licenseKey }}` |
| **Tipe Paket** | {{ ucfirst($licenseType) }} |
| **Masa Berlaku** | {{ $expiredDate }} |
</x-mail::table>

<x-mail::button :url="$loginUrl" color="primary">
Login ke Dashboard
</x-mail::button>

Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan tautan berikut pada peramban Anda:  
[{{ $loginUrl }}]({{ $loginUrl }})

---

**Langkah Keamanan:**
Jangan memberikan kredensial login Anda kepada pihak manapun. Jika Anda tidak merasa melakukan pendaftaran ini, harap hubungi tim dukungan kami segera.

Hormat kami,  
**Tim Administrasi {{ config('app.name') }}**
</x-mail::message>