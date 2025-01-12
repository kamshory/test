<?php

use MagicObject\MagicDto;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\BukuDireksi;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

$inputGet = new InputGet();
$bukuDireksi = new BukuDireksi(null, $database);

class BukuDireksiDto extends MagicDto
{
    /**
     * Buku Direksi name.
     *
     * This property holds the name of the "Buku Direksi" (Director's Book).
     *
     * @JsonProperty("Buku Direksi")
     * @var string
     */
    protected $nama;

    /**
     * The date of the event.
     *
     * This property stores the date in the format "j F Y" (e.g., "9 December 2024").
     *
     * @JsonFormat(pattern="j F Y")
     * @JsonProperty("Tanggal")
     * @var DateTime
     */
    protected $tanggal;

    /**
     * The status of the event.
     *
     * This property holds the status of the "Buku Direksi" entry.
     *
     * @JsonProperty("Status")
     * @var string
     */
    protected $status;

    /**
     * Problem description.
     *
     * This property stores the problem related to the "Buku Direksi" entry.
     *
     * @JsonProperty("Permasalahan")
     * @var string
     */
    protected $permasalahan;

    /**
     * Estimated resolution time.
     *
     * This property holds the estimated time required to resolve the issue, in minutes.
     *
     * @JsonProperty("Perkiraan Lama Penyelesaian")
     * @var string
     */
    protected $perkiraanLamaPenyelesaian;

    /**
     * Resolution description.
     *
     * This property stores the description of the resolution for the issue.
     *
     * @JsonProperty("Penyelesaian")
     * @var string
     */
    protected $penyelesaian;
    
    /**
     * Actual resolution time.
     *
     * This property holds the actual time taken to resolve the issue, in minutes.
     *
     * @JsonProperty("Lama Penyelesaian")
     * @var string
     */
    protected $lamaPenyelesaian;

    /**
     * On load data method.
     *
     * This method is used to modify and format the loaded data by converting minutes to a human-readable time format (days, hours, minutes).
     *
     * @param stdClass $data The data object to be processed.
     * @return stdClass The modified data object with formatted time values.
     */
    public function onLoadData($data)
    {
        // Convert the estimated resolution time and actual resolution time from minutes to human-readable format
        $data->perkiraanLamaPenyelesaian = $this->convertMinutesToTime($data->perkiraanLamaPenyelesaian);
        $data->lamaPenyelesaian = $this->convertMinutesToTime($data->lamaPenyelesaian);

        // Return the modified data
        return $data;
    }

    /**
     * Convert minutes to a human-readable time format (days, hours, minutes).
     *
     * This function takes a number of minutes as input and converts it to a format like "X days, Y hours, Z minutes".
     * If the input is 0, it returns an empty string.
     *
     * @param int $minutes The number of minutes to convert.
     * @return string The converted time in a readable format.
     */
    private function convertMinutesToTime($minutes) {
        // If the input is 0 minutes, return an empty string
        if ($minutes == 0) {
            return "";
        }

        // Calculate the number of days
        $days = floor($minutes / 1440); // 1440 minutes in a day

        // Calculate the remaining minutes after calculating days
        $remainingMinutes = $minutes % 1440;

        // Calculate the number of hours
        $hours = floor($remainingMinutes / 60); // 60 minutes in an hour

        // Calculate the remaining minutes after calculating hours
        $remainingMinutes = $remainingMinutes % 60;

        // Construct the result string
        $result = "";
        if ($days > 0) {
            $result .= $days . " hari";
        }
        if ($hours > 0) {
            if ($result != "") $result .= ", "; // Add a comma if there were previous days
            $result .= $hours . " jam";
        }
        if ($remainingMinutes > 0 || $result == "") {
            if ($result != "") $result .= ", "; // Add a comma if there were previous days/hours
            $result .= $remainingMinutes . " menit";
        }

        // Return the final formatted result
        return $result;
    }
}

function generateTableFromData(
    $tableClass, 
    $firstColClass, 
    $secondColClass, 
    $data, 
    $tableWidth = 800, // Default lebar tabel 800px
    $firstColWidth = 250, // Default lebar kolom pertama 250px
    $secondColWidth = 550 // Default lebar kolom kedua 550px
) {
    // Membuka tag <table> dan menambahkan kelas serta lebar tabel
    $html = '<table class="' . htmlspecialchars($tableClass) . '" style="width:' . $tableWidth . 'px;">';
    
    // Menambahkan elemen <tbody>
    $html .= '<tbody>';
    
    // Iterasi melalui data dan buat satu baris untuk setiap elemen
    foreach ($data as $key => $value) {
        $html .= '<tr>';
        
        // Kolom pertama (data key) dengan kelas tertentu dan lebar kolom pertama
        $html .= '<td class="' . htmlspecialchars($firstColClass) . '" style="width:' . $firstColWidth . 'px;">';
        // Memeriksa apakah key mengandung HTML atau hanya teks
        if ($key !== strip_tags($key)) {
            // Jika mengandung HTML, kita biarkan itu menjadi HTML
            $html .= $key;
        } else {
            // Jika hanya teks biasa, gunakan htmlspecialchars untuk mencegah XSS
            $html .= htmlspecialchars($key);
        }
        $html .= '</td>';
        
        // Kolom kedua (data value) dengan kelas tertentu dan lebar kolom kedua
        $html .= '<td class="' . htmlspecialchars($secondColClass) . '" style="width:' . $secondColWidth . 'px;">';
        // Memeriksa apakah value mengandung HTML atau hanya teks
        if ($value !== strip_tags($value)) {
            // Jika mengandung HTML, kita biarkan itu menjadi HTML
            $html .= $value;
        } else {
            // Jika hanya teks biasa, gunakan htmlspecialchars untuk mencegah XSS
            $html .= htmlspecialchars($value);
        }
        $html .= '</td>';
        
        $html .= '</tr>';
    }
    
    // Menutup tag <tbody> dan <table>
    $html .= '</tbody>';
    $html .= '</table>';
    
    return $html;
}

try
{
    $bukuDireksi->find($inputGet->getBukuDireksiId());
    $bukuDireksiDto = new BukuDireksiDto($bukuDireksi);
    echo generateTableFromData('tabel', 'col1', 'col2', $bukuDireksiDto->toJson());
}
catch(Exception $e)
{
    // do nothing
}