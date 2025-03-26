<?php

/**
 * Retrieve the address from the given latitude and longitude using the Nominatim API.
 *
 * This function sends a request to the Nominatim reverse geocoding API to obtain 
 * detailed address information based on geographic coordinates. It handles errors 
 * during the cURL request and formats the address components for easy access.
 *
 * @param float $latitude The latitude of the location.
 * @param float $longitude The longitude of the location.
 * @return array|null An associative array containing address components (country, province, 
 *                    city, district, village, road) and the input coordinates, or null 
 *                    if the address could not be retrieved.
 */
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

        $negara = isset($address['country']) ? $address['country'] : null;
        $provinsi = isset($address['city']) ? $address['city'] : null;
        $kabupaten_kota = isset($address['city_district']) ? $address['city_district'] : (isset($address['town']) ? $address['town'] : null);
        $kecamatan = isset($address['suburb']) ? $address['suburb'] : (isset($address['town']) ? $address['town'] : null);
        $desa_kelurahan = isset($address['village']) ? $address['village'] : (isset($address['neighbourhood']) ? $address['neighbourhood'] : null);
        $alamat_surat = isset($data['display_name']) ? $data['display_name']: (isset($address['road']) ? $address['road'] : null);

        // Fixing nama provinsi
        $provinsi = fixState($provinsi);
        return [
            'negara' => $negara,
            'provinsi' => $provinsi,
            'kecamatan' => $kecamatan,
            'kabupaten_kota' => $kabupaten_kota,
            'desa_kelurahan' => $desa_kelurahan,
            'alamat_surat' => $alamat_surat,
            'latitude' => $latitude, 
            'longitude' => $longitude
        ];
    }

    return null;
}

/**
 * Fixes the name of the province for consistency.
 *
 * @param string $provinsi The name of the province to be fixed.
 * @return string The fixed name of the province.
 */
function fixState($provinsi)
{
    $search = array(
        'Daerah Khusus ibukota Jakarta'
    );
    $replace = array(
        'DKI Jakarta'
    );
    return str_replace($search, $replace, $provinsi);
}

header("Content-type: application/json");

if(isset($_GET['latitude']) && isset($_GET['longitude']))
{
    $latitude = floatval($_GET['latitude']);
    $longitude = floatval($_GET['longitude']);
    $address = getAddressFromCoordinates($latitude, $longitude);
    echo json_encode($address);
}