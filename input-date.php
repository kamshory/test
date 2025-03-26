<?php

use MagicApp\Field;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use Sipro\Entity\Data\HariLibur;

require_once __DIR__ . "/inc.app/auth-supervisor.php";

?>
<style>

    .calender-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .calendar-btn {
        width: 100px;
        padding: 8px 10;
        font-size: 12px;
        cursor: pointer;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 3px;
    }

    .calendar-btn:hover {
        background-color: #0056b3;
    }


    #calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        font-family: Arial, sans-serif;
        margin: 20px 0px 0px 0px;
        font-size: 12px;
    }

    .month {
        grid-column: span 7;
        text-align: center;
        background-color: #9dcfe8;
        padding: 5px;
        color: #484848;
        border-radius: 3px;
        font-weight: bold;
    }

    .day {
        font-weight: bold;
        text-align: center;
        color: #5a5a5a;
    }

    .date-button {
        padding: 4px 0;
        text-align: center;
        border: 1px solid #d1d1d1;
        border-radius: 3px;
        background-color: #f0f8ff;
        cursor: pointer;
        font-size: 12px;
        color: #333;
    }
    .date-button[data-weekend="true"] {
        color: #d63384;
        background-color: #ffebee;
        border-color: #eac9ce;
    }
    .date-button[data-holiday="true"] {
        background-color: #ea6c6c;
        color: #fff;
        border-color: #cc9096;
    }

    .date-button[data-weekday="true"]:hover {
        background-color: #e6f7ff;
    }
    .date-button.date-selected[data-weekday="true"] {
        background-color: #1a92b7;
        color: white;
        border-color: #148bb0;
    }
    .date-button[disabled]
    {
        cursor: not-allowed;
    }


    .selected-date {
        display: none;
    }
</style>


<?php
/**
 * Render a calendar within a specified date range.
 *
 * The function generates a calendar in HTML format using DOMDocument. It includes weekends
 * and public holidays retrieved from a database. Each date is displayed as a button,
 * and holidays and weekends are marked and disabled.
 *
 * @param object $database  The database connection object.
 * @param int $numberOfMonths Number of months
 * @param string $startDate The start date of the calendar in 'YYYY-MM-DD' format.
 * @param string[] $selectedDate
 *
 * @return string The generated HTML of the calendar as a string.
 */
function renderCalendar($database, $numberOfMonths, $startDate, $selectedDate = null)
{
    $start = new DateTime($startDate); // Ubah string menjadi objek DateTime
    $end = clone $start; // Clone agar tidak mengubah tanggal awal
    $nm = $numberOfMonths - 1;
    $end->modify("+$nm months"); 
    $end->modify('last day of this month'); // Dapatkan tanggal terakhir bulan tersebut
    $endDate = $end->format('Y-m-d');

    $prev = clone $start;
    $prev->modify('-1 months');
    $prevDate = $prev->format('Y-m-d');

    $next = clone $start;
    $next->modify('+1 months');
    $nextDate = $next->format('Y-m-d');


    // Initialize the holiday data object
    $hariLibur = new HariLibur(null, $database);

    // Set search specifications for holidays within the given date range
    $specs = PicoSpecification::getInstance()->addAnd(
        PicoPredicate::getInstance()->between(Field::of()->tanggal, $startDate, $endDate)
    );

    // Define sorting rules to sort holidays by date in ascending order
    $sorts = PicoSortable::getInstance()->addSortable(
        new PicoSort(Field::of()->tanggal, PicoSort::ORDER_TYPE_ASC)
    );

    $holidays = [];
    // Retrieve holiday data from the database
    try
    {
        $pageData = $hariLibur->findAll($specs, null, $sorts);

        foreach ($pageData->getResult() as $hariLibur) {
            $date = $hariLibur->getTanggal();
            $holidays[$date] = $date; // Store holiday dates
        }
    }
    catch(Exception $e)
    {
        // do nothing
    }

    // Array of days of the week and weekends
    $daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    $weekends = [0, 6]; // Sunday (0) and Saturday (6)

    // Array of month names
    $months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // Create a DOMDocument object for HTML generation
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput = true;

    $calendar = $doc->createElement('div');
    $calendar->setAttribute('id', 'calendar');

    $calendarContainer = $doc->createElement('div');
    $calendarContainer->setAttribute('id', 'calendar-container');


    $buttonArea = $doc->createElement('div');
    $buttonArea->setAttribute('class', 'calender-nav');

    $prevBtn = $doc->createElement('button');
    $prevBtn->setAttribute('type', 'button');
    $prevBtn->setAttribute('class', 'calendar-btn');
    $prevBtn->setAttribute('id', 'prev-date');
    $prevBtn->setAttribute('data-date', $prevDate);
    $prevBtn->textContent = 'Sebelumnya'; 

    $nextBtn = $doc->createElement('button');
    $nextBtn->setAttribute('type', 'button');
    $nextBtn->setAttribute('class', 'calendar-btn');
    $nextBtn->setAttribute('id', 'next-date');
    $nextBtn->setAttribute('data-date', $nextDate);
    $nextBtn->textContent = 'Berikutnya'; 

    $buttonArea->appendChild($prevBtn);
    $buttonArea->appendChild($nextBtn);

    $calendarContainer->appendChild($buttonArea);
    $calendarContainer->appendChild($calendar);


    // Add headers for days of the week
    foreach ($daysOfWeek as $day) {
        $dayDiv = $doc->createElement('div', substr($day, 0, 3)); // Use 3-letter abbreviations
        $dayDiv->setAttribute('class', 'day');
        $calendar->appendChild($dayDiv);
    }

    $currentDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    // Loop through each date within the specified range
    while ($currentDate <= $endDate) {
        // Create a row for the current month
        $monthDiv = $doc->createElement('div', $months[date("n", $currentDate) - 1] . ' ' . date("Y", $currentDate));
        $monthDiv->setAttribute('class', 'month');
        $calendar->appendChild($monthDiv);

        // Add empty cells for days before the start of the month
        for ($i = 0; $i < date("w", $currentDate); $i++) {
            $emptyDiv = $doc->createElement('div');
            $calendar->appendChild($emptyDiv);
        }

        // Add buttons for each day of the month
        while (date("n", $currentDate) == date("n", strtotime($startDate))) {
            $date = date("Y-m-d", $currentDate);
            $dateButton = $doc->createElement('button', date("j", $currentDate)); // Day of the month

            $className = 'date-button';
            if(isset($selectedDate) && is_array($selectedDate) && in_array($date, $selectedDate))
            {
                $className .= ' date-selected';
            }
            $dateButton->setAttribute('class', $className);
            $dateButton->setAttribute('data-date', $date);
            $dateButton->setAttribute('onclick', "selectDate(this)");

            markDate($dateButton, $holidays, $weekends, $currentDate, $date);

            // Append the button to the calendar
            $calendar->appendChild($dateButton);

            // Move to the next day
            $currentDate = strtotime("+1 day", $currentDate);
            if ($currentDate > $endDate) {
                break;
            }
        }

        $startDate = date("Y-m-d", $currentDate); // Update to the next month's start date
    }

    // Append the calendar container to the document
    $doc->appendChild($calendarContainer);

    // Add a container for the selected date input
    $inputContainer = $doc->createElement('div');
    $inputContainer->setAttribute('class', 'selected-date');

    if(isset($selectedDate) && is_array($selectedDate))
    {
        foreach($selectedDate as $date)
        {
            if(strtotime($date) >= strtotime($startDate) && strtotime($date) <= strtotime($endDate))
            {
                $input = $doc->createElement('input');
                $input->setAttribute('type', 'hidden');
                $input->setAttribute('value', $date);
                $inputContainer->appendChild($input);
            }
        }
    }

    $doc->appendChild($inputContainer);

    // Return the generated HTML as a string
    return $doc->saveHTML();
}

/**
 * Marks the given date button with appropriate attributes based on its status.
 *
 * The function checks whether the date is a public holiday, weekend, or weekday, 
 * and assigns the appropriate attributes such as `data-holiday`, `data-weekend`, 
 * or `data-weekday`. It also disables the button for holidays and weekends.
 *
 * @param DOMElement $dateButton       The DOMElement button representing the date.
 * @param array      $holidays         An associative array of holiday dates (keys).
 * @param array      $weekends         An array of integers representing weekend days (0 for Sunday, 6 for Saturday).
 * @param int        $currentDate      The current date as a timestamp.
 * @param string     $date             The current date in 'YYYY-MM-DD' format.
 *
 * @return DOMElement The modified DOMElement button with the appropriate attributes applied.
 */
function markDate($dateButton, $holidays, $weekends, $currentDate, $date)
{
    $dow = date("w", $currentDate); // Day of the week (0=Sunday, 6=Saturday)

    // Mark and disable weekends
    if (in_array($dow, $weekends)) {
        $dateButton->setAttribute('data-weekend', 'true');
        $dateButton->setAttribute('disabled', 'disabled');
    }

    // Mark and disable holidays
    if (isset($holidays[$date])) {
        $dateButton->setAttribute('data-holiday', 'true');
        $dateButton->setAttribute('disabled', 'disabled');
    }

    // Mark weekdays
    if (!isset($holidays[$date]) && !in_array($dow, $weekends)) {
        $dateButton->setAttribute('data-weekday', 'true');
    }

    return $dateButton;
}

$inputGet = new InputGet();
$startDate = $inputGet->getStartDate();
$selectedDate = $inputGet->getSelectedDate();
if(!isset($inputGet) || empty($startDate))
{
    $start = new DateTime();
    $start->modify('first day of this month');
    $startDate = $start->format('Y-m-d');
}
echo renderCalendar($database, 3, $startDate, $selectedDate);

?>
<script>
    function selectDate(element)
    {
        element.classList.toggle('date-selected');
        let date = element.getAttribute('data-date');
        if(element.classList.contains('date-selected'))
        {
            let inputDate = document.createElement('input');
            inputDate.setAttribute('type', 'hidden');
            inputDate.setAttribute('name', 'selected_date[]');
            inputDate.setAttribute('value', date);
            document.querySelector('.selected-date').append(inputDate);
        }
        else
        {
            let inputDate = document.querySelector('.selected-date input[value="'+date+'"]');
            if(inputDate != null)
            {
                inputDate.parentNode.removeChild(inputDate);
            }
        }
    }
    document.querySelector('#prev-date').addEventListener('click', function(e){
        e.preventDefault();
        prevDate(e);
    });
    document.querySelector('#next-date').addEventListener('click', function(e){
        e.preventDefault();
        nextDate(e);
    });
    function prevDate(e)
    {
        let prevStartDate = e.target.getAttribute('data-date');
        let url = createUrl(prevStartDate, getSelectedDate());
        window.location = url;
    }
    function nextDate(e)
    {
        let nextStartDate = e.target.getAttribute('data-date');
        let url = createUrl(nextStartDate, getSelectedDate());
        window.location = url;
    }
    function getSelectedDate()
    {
        let selected = [];
        let container = document.querySelectorAll('#calendar .date-button.date-selected');
        if(container != null)
        {
            container.forEach((date) => {
                selected.push(date.getAttribute('data-date'));
            })
            
        }
        return selected;
    }
    function createUrl(startDate, selectedDate)
    {
        let selected = [];
        if(selectedDate != null && typeof selectedDate == 'object' && selectedDate.length > 0)
        {
            selectedDate.forEach((date)=>{
                selected.push(`&selected_date[]=${date}`);
            });
        } 
        return `?start_date=${startDate}${selected.join('')}`;
    }
</script>