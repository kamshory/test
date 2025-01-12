<?php
function getAddressFromCoordinates($latitude, $longitude) {
    // URL API Nominatim
    $url = "https://nominatim.openstreetmap.org/reverse?lat=$latitude&lon=$longitude&format=json&addressdetails=1";

    // Inisialisasi cURL
    $ch = curl_init($url);

    // Mengatur opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: PHP Script'
    ]);

    // Eksekusi cURL dan menangkap respons
    $response = curl_exec($ch);

    // Cek untuk kesalahan cURL
    if ($response === false) {
        echo 'cURL Error: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    // Menutup sesi cURL
    curl_close($ch);

    // Mengonversi JSON menjadi array PHP
    $data = json_decode($response, true);

    // Memeriksa apakah ada data yang diterima
    if (isset($data['address'])) {
        $address = $data['address'];
        return [
            'negara' => isset($address['country']) ? $address['country'] : null,
            'provinsi' => isset($address['city']) ? $address['city'] : null,
            'kecamatan' => isset($address['suburb']) ? $address['suburb'] : (isset($address['town']) ? $address['town'] : null),
            'kabupaten_kota' => isset($address['city_district']) ? $address['city_district'] : (isset($address['town']) ? $address['town'] : null),
            'desa_kelurahan' => isset($address['village']) ? $address['village'] : (isset($address['neighbourhood']) ? $address['neighbourhood'] : null),
            'alamat_surat' => isset($data['display_name']) ? $data['display_name']: (isset($address['road']) ? $address['road'] : null),
        ];
    }

    return null;
}

// Contoh penggunaan
$latitude = -6.1751; // Ganti dengan latitude yang diinginkan
$longitude = 106.8650; // Ganti dengan longitude yang diinginkan

$addressDetails = getAddressFromCoordinates($latitude, $longitude);

if ($addressDetails) {
    echo "Negara: " . (isset($addressDetails['negara']) ? $addressDetails['negara'] : 'Tidak ditemukan') . "<br>";
    echo "Provinsi: " . (isset($addressDetails['provinsi']) ? $addressDetails['provinsi'] : 'Tidak ditemukan') . "<br>";
    echo "Kabupaten/Kota: " . (isset($addressDetails['kabupaten_kota']) ? $addressDetails['kabupaten_kota'] : 'Tidak ditemukan') . "<br>";
    echo "Kecamatan: " . (isset($addressDetails['kecamatan']) ? $addressDetails['kecamatan'] : 'Tidak ditemukan') . "<br>";
    echo "Desa/Kelurahan: " . (isset($addressDetails['desa_kelurahan']) ? $addressDetails['desa_kelurahan'] : 'Tidak ditemukan') . "<br>";
    echo "Alamat Surat: " . (isset($addressDetails['alamat_surat']) ? $addressDetails['alamat_surat'] : 'Tidak ditemukan') . "<br>";
} else {
    echo "Tidak dapat menemukan alamat untuk koordinat yang diberikan.";
}
?>
