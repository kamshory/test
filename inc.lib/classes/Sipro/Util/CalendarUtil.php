<?php

namespace Sipro\Util;

/**
 * Class CalendarUtil
 *
 * A utility class for generating a calendar page for a specific month and year.
 * It allows for the inclusion of previous and next month days, and can be customized
 * for different calendar types.
 *
 * @package Sipro\Util
 */
class CalendarUtil
{
    /**
     * @var array The rows of the calendar
     */
    private $rows = array(); 
    
    /**
     * @var boolean Flag to indicate if previous and next month days are included
     */
    private $withNextPrev = false;

    /**
     * @var int The type of calendar layout
     */
    private $type = 0;

    /**
     * @var string The start date of the calendar in 'Y-m-d' format
     */
    private $startDate;

    /**
     * @var string The end date of the calendar in 'Y-m-d' format
     */
    private $endDate;
    
    /**
     * Constructor
     *
     * Initializes the calendar with the given year, month, type, and an option 
     * to include the previous and next month's days.
     *
     * @param integer $year The year for the calendar
     * @param integer $month The month for the calendar (1-12)
     * @param integer $type The type of calendar layout (default is 0)
     * @param boolean $withNextPrev Flag to create previous and next month days (default is false)
     */
    public function __construct($year, $month, $type = 0, $withNextPrev = false)
    {
        $this->type = $type;
        $this->withNextPrev = $withNextPrev;
        $this->createCalendarPage($year, $month);      
    }
    
    /**
     * Create calendar page
     *
     * Generates the rows of the calendar for the specified year and month.
     * It calculates the start and end dates based on the calendar type
     * and whether to include days from the previous and next month.
     *
     * @param integer $year The year for the calendar
     * @param integer $month The month for the calendar (1-12)
     * @return array The generated calendar rows
     */
    public function createCalendarPage($year, $month)
    {   
        $this->rows = array();       
        $this->rows[0] = array();
        $raw = sprintf("%04d-%02d-01", $year, $month);
        $startTime = strtotime($raw);
        $t = date('t', $startTime);
        $endTime = $startTime + $t * 86400;
        
        if($this->type == 0)
        {
            $w1 = date('w', $startTime);
            $w2 = date('w', $endTime);
            $start = $startTime - ($w1 * 86400);
            $end = $endTime + ((7-$w2) * 86400);       
        }
        else
        {
            $w1 = date('w', $startTime);
            $w2 = date('w', $endTime);
            $start = $startTime + 86400 - ($w1 * 86400);
            $end = $endTime + 86400 + ((7-$w2) * 86400);       
        }
        
        if($this->withNextPrev)
        {
            $this->startDate = date('Y-m-d', $start);
            $this->endDate = date('Y-m-d', $end);
        }
        else
        {
            $this->startDate = date('Y-m-d', $startTime);
            $this->endDate = date('Y-m-d', $endTime);
        }
        
        
        for($i = $start, $j = 0; $i<$end; $i += 86400, $j++)
        {
            $row = floor($j / 7);
            $col = $j % 7;
            
            if(!isset($this->rows[$row]))
            {
                $this->rows[$row] = array();
            }
            $date = date('Y-m-d', $i);
            $day = date('j', $i);
            
            $printDay = false;
            if($i < $startTime)
            {
                $class = 'prev-month';
            }
            else if($i > $endTime)
            {
                $class = 'next-month';
            }
            else
            {
                $class = 'cur-month';
                $printDay = true;
            }
            if($this->withNextPrev)
            {
                $printDay = true;
            }
            
            $this->rows[$row][$col] = array(
                'date'=>$date, 
                'day'=>$day,
                'dow'=>$col,
                'class'=>$class,
                'print'=>$printDay
            );
        }
    }

    /**
     * Get the minimum date in the calendar
     *
     * @return array The first row of the calendar, which contains the minimum date information
     */
    public function getMinDate()
    {
        return $this->rows[0];
    }

    /**
     * Get the maximum date in the calendar
     *
     * @return array The last row of the calendar, which contains the maximum date information
     */
    public function getMaxDate()
    {
        return end($this->rows);
    }
    
    /**
     * Get the entire calendar
     *
     * @return array The rows of the calendar
     */
    public function getCalendar()
    {
        return $this->rows;
    }
    
    /**
     * Get the calendar in a flat inline format
     *
     * @return array The flattened calendar rows
     */
    public function getCalendarInline()
    {
        $rows = array();
        foreach($this->rows as $row)
        {
            foreach($row as $col)
            {
                $rows[] = $col;
            }
            
        }
        return $rows;
    }

    /**
     * Get the value of withNextPrev
     *
     * @return boolean Indicates if previous and next month days are included
     */ 
    public function getWithNextPrev()
    {
        return $this->withNextPrev;
    }

    /**
     * Get the value of type
     *
     * @return integer The type of calendar layout
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the value of startDate
     *
     * @return string The start date of the calendar in 'Y-m-d' format
     */ 
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Get the value of endDate
     *
     * @return string The end date of the calendar in 'Y-m-d' format
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }
}