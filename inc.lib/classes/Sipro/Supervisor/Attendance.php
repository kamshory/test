<?php

namespace Sipro\Supervisor;

use MagicObject\MagicObject;

class Attendance extends MagicObject
{
    /**
     * Determines the attendance value for a given date.
     *
     * This function checks if an attendance record exists for the specified date 
     * in the provided array of attendances. If the attendance exists, it returns 
     * a value of `1` (indicating presence). If the attendance is not found for 
     * the given date, it returns `0` (indicating absence).
     *
     * @param array $attendances An associative array of attendance records, where
     *                           the keys are dates and the values are attendance types.
     * @param string $tanggal The date for which attendance needs to be checked, 
     *                        in 'YYYY-MM-DD' format.
     * @return int Returns `1` if the attendance for the specified date exists, 
     *             otherwise returns `0`.
     */
    public function getNilaiKehadiran($attendances, $tanggal)
    {
        if(isset($attendances) && isset($attendances[$tanggal]))
        {
            return 1;
        }
        return 0;
    }
    
    /**
     * Retrieves attendance information for a given supervisor within a specified period.
     *
     * This method calculates the start and end date for the given period and retrieves attendance records 
     * from the database based on the supervisor's ID and the period range.
     *
     * @param int $supervisorId The ID of the supervisor whose attendance is to be fetched.
     * @param string $periodeId The period in 'YYYYMM' format for which attendance needs to be retrieved.
     * 
     * @return array An associative array where the keys are dates (in 'YYYY-MM-DD' format) and 
     *               the values are attendance types (such as 'H' for `hadir`, 'D' for `perjalanan dinas`).
     */
    public function getAttendance($supervisorId, $periodeId)
    {
        $year = substr($periodeId, 0, 4);
        $mothh = substr($periodeId, 4, 2);
        $startDate = $year."-".$mothh."-01";
        $lastDay = date("t", strtotime($startDate));
        $endDate = sprintf("%04d-%02d-%02d", $year, $mothh, $lastDay);
        $attendances = $this->getAttendanceFromDatabase($supervisorId, $startDate, $endDate);
        $result = array();
        if(isset($attendances) && is_array($attendances) && !empty($attendances))
        {
            foreach($attendances as $attendance)
            {
                $result[$attendance->tanggal] = $attendance->tipe_kehadiran;
            }
        }
        return $result;
    }

    /** 
     * Retrieves attendance data directly from the database based on supervisor ID and date range.
     *
     * This method executes a raw SQL query to fetch attendance data for a given supervisor within a 
     * specified date range. The query checks for the supervisor's attendance records where the attendance 
     * type is either 'H' (`hadir`) or 'D' (`perjalan dinas`), and ensures that both the entry and exit times 
     * are valid if the attendance type is 'H'.
     *
     * @param int $supervisorId The ID of the supervisor whose attendance is to be fetched.
     * @param string $startDate The start date for the period to query in 'YYYY-MM-DD' format.
     * @param string $endDate The end date for the period to query in 'YYYY-MM-DD' format.
     * 
     * @return stdClass[] An array of attendance objects containing the `tanggal` (date) and 
     *                    `tipe_kehadiran` (attendance type).
     * 
     * @query("
     * SELECT tanggal, tipe_kehadiran
     * FROM kehadiran
     * WHERE supervisor_id = :supervisorId
     * AND tanggal >= :startDate
     * AND tanggal <= :endDate
     * AND (
     *   (
     *     tipe_kehadiran = 'H'
     *     AND waktu_masuk IS NOT NULL 
     *     AND waktu_masuk != '' 
     *     AND waktu_masuk != '0000-00-00 00:00:00'
     *     AND waktu_pulang IS NOT NULL 
     *     AND waktu_pulang != '' 
     *     AND waktu_pulang != '0000-00-00 00:00:00'
     *   )
     *   OR
     *   (
     *     tipe_kehadiran = 'D'
     *   )
     * )
     * ORDER BY tanggal ASC
     * ")
     */
    public function getAttendanceFromDatabase($supervisorId, $startDate, $endDate)
    {
        return $this->executeNativeQuery();
    }
    
    /**
     * Get nilai hari
     *
     * @param array $arrayBukuHarian
     * @param array $arrayHari
     * @param string $indeksArrayHari
     * @param int $indeksArrayProyek
     * @return int
     */
    public function getNominal($arrayBukuHarian, $arrayHari, $indeksArrayHari, $indeksArrayProyek)
    {
        $mm = 0;
        if (isset($arrayBukuHarian[$indeksArrayHari])) {
            if ($arrayBukuHarian[$indeksArrayHari] > 0) {
                $mm = 1 / $arrayBukuHarian[$indeksArrayHari];
            }
        } else if ($arrayHari[$indeksArrayHari]['cuti_dibayar'] == 1 && $arrayHari[$indeksArrayHari]['proyek_id'] == $indeksArrayProyek) {
            $mm = 1;
        }
        return $mm;
    }
}