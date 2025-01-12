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
 * @return array|null An associative array containing address components (country, state, 
 *                    city, district, village, road) and the input coordinates, or null 
 *                    if the address could not be retrieved.
 */
function getAddressFromCoordinates($latitude, $longitude) {
    // Nominatim API URL
    $url = "https://nominatim.openstreetmap.org/reverse?lat=$latitude&lon=$longitude&format=json&addressdetails=1";

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: PHP Script'
    ]);

    // Execute cURL and capture the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        echo 'cURL Error: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    // Close cURL session
    curl_close($ch);

    // Convert JSON to PHP array
    $data = json_decode($response, true);

    // Check if data was received
    if (isset($data['address'])) {
        $address = $data['address'];

        $negara = getAddressValue($address, ['country']);
        $provinsi = getAddressValue($address, ['city']);
        $kabupaten_kota = getAddressValue($address, ['city_district', 'town']);
        $kecamatan = getAddressValue($address, ['suburb', 'town']);
        $desa_kelurahan = getAddressValue($address, ['village', 'neighbourhood']);
        $alamat_surat = getAddressValue($data, ['display_name', 'road']);

        // Fixing the province name
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
 * Retrieves the value from an array based on the order of the provided keys.
 * This function searches for the keys in the `$address` array and returns
 * the first value found. If no keys are found, the function returns `null`.
 *
 * @param array $address The array containing address data or other information.
 * @param array $keys The array of keys to search for in the given order.
 *
 * @return mixed The first value found based on the available keys, or `null`
 *               if none of the keys are found.
 */
function getAddressValue($address, $keys) {
    foreach ($keys as $key) {
        if (isset($address[$key])) {
            return $address[$key];
        }
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
        'DK Jakarta'
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