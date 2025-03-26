<?php

namespace Sipro\Util;

use Exception;
use MagicObject\Database\PicoDatabase;
use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\Pesan;

class MessageUtil
{
    /**
     * Database connection
     *
     * @var PicoDatabase
     */
    private $database;

    /**
     * Constructor
     *
     * @param PicoDatabase $database
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * User inbox
     *
     * @param integer $userId
     * @param integer $pageNumber
     * @param integer $pageSize
     * @param boolean $read
     * @return PicoPageData
     */
    public function userInbox($userId, $pageNumber, $pageSize, $read = null)
    {
        $specification = PicoSpecification::getInstance()
			->addAnd(array('penerimaUserId', $userId)) // user ID
            ->addAnd(array('salinan', false)) // inbox
		;

        if(isset($read))
        {
            $specification->addAnd(array('dibaca', $read === true));
        }

        $sortable = PicoSortable::getInstance()
            ->addSortable(array('waktuBuat', PicoSort::ORDER_TYPE_DESC));
        $pageable = new PicoPageable(array($pageNumber, $pageSize), $sortable);

        $message = new Pesan(null, $this->database);
        try
        {
            return $message->findAll($specification, $pageable, $sortable, true);
        }
        catch(Exception $e)
        {
            // do nothing
        }
        return null;
    }

    /**
     * User outbox
     *
     * @param integer $userId
     * @param integer $pageNumber
     * @param integer $pageSize
     * @param boolean $read
     * @return PicoPageData
     */
    public function userOutbox($userId, $pageNumber, $pageSize, $read = null)
    {
        $specification = PicoSpecification::getInstance()
			->addAnd(array('pengirimUserId', $userId)) // user ID
            ->addAnd(array('salinan', true)) // inbox
		;

        if(isset($read))
        {
            $specification->addAnd(array('dibaca', $read === true));
        }

        $sortable = PicoSortable::getInstance()
            ->addSortable(array('waktuBuat', PicoSort::ORDER_TYPE_DESC));
        $pageable = new PicoPageable(array($pageNumber, $pageSize), $sortable);

        $message = new Pesan(null, $this->database);
        try
        {
            return $message->findAll($specification, $pageable, $sortable, true);
        }
        catch(Exception $e)
        {
            // do nothing
        }
        return null;
    }

    /**
     * Supervisor inbox
     *
     * @param integer $supervisorId
     * @param integer $pageNumber
     * @param integer $pageSize
     * @param boolean $read
     * @return PicoPageData
     */
    public function supervisorInbox($supervisorId, $pageNumber, $pageSize, $read = null)
    {
        $specification = PicoSpecification::getInstance()
			->addAnd(array('penerimaSupervisorId', $supervisorId)) // supervisor ID
            ->addAnd(array('salinan', false)) // inbox
		;

        if(isset($read))
        {
            $specification->addAnd(array('dibaca', $read === true));
        }

        $sortable = PicoSortable::getInstance()
            ->addSortable(array('waktuBuat', PicoSort::ORDER_TYPE_DESC));
        $pageable = new PicoPageable(array($pageNumber, $pageSize), $sortable);

        $message = new Pesan(null, $this->database);
        try
        {
            return $message->findAll($specification, $pageable, $sortable, true);
        }
        catch(Exception $e)
        {
            // do nothing
        }
        return null;
    }

    /**
     * Supervisor outbox
     *
     * @param integer $supervisorId
     * @param integer $pageNumber
     * @param integer $pageSize
     * @param boolean $read
     * @return PicoPageData
     */
    public function supervisorOutbox($supervisorId, $pageNumber, $pageSize, $read = null)
    {
        $specification = PicoSpecification::getInstance()
			->addAnd(array('pengirimSupervisorId', $supervisorId)) // supervisor ID
            ->addAnd(array('salinan', true)) // outbox
		;

        if(isset($read))
        {
            $specification->addAnd(array('dibaca', $read === true));
        }

        $sortable = PicoSortable::getInstance()
            ->addSortable(array('waktuBuat', PicoSort::ORDER_TYPE_DESC));
        $pageable = new PicoPageable(array($pageNumber, $pageSize), $sortable);

        $message = new Pesan(null, $this->database);
        try
        {
            return $message->findAll($specification, $pageable, $sortable, true);
        }
        catch(Exception $e)
        {
            // do nothing
        }
        return null;
    }

    /**
     * Get audience
     *
     * @param Pesan $pesan
     * @param integer $userId
     * @return string
     */
    public function getAudienceFromUser($pesan, $userId)
    {
        if($userId != $pesan->getPengirimUserId() && $pesan->issetPengirimUser())
        {
            return $pesan->getPengirimUser()->getNama();
        }
        if($pesan->issetPengirimSupervisor())
        {
            return $pesan->getPengirimSupervisor()->getNama();
        }
        if($userId != $pesan->getPenerimaUserId() && $pesan->issetPenerimaUser())
        {
            return $pesan->getPenerimaUser()->getNama();
        }
        if($pesan->issetPenerimaSupervisor())
        {
            return $pesan->getPenerimaSupervisor()->getNama();
        }
        return "";

    }

    /**
     * Get audience
     *
     * @param Pesan $pesan
     * @param integer $supervisorId
     * @return string
     */
    public function getAudienceFromSupervisor($pesan, $supervisorId)
    {
        if($pesan->issetPengirimUser())
        {
            return $pesan->getPengirimUser()->getNama();
        }
        if($supervisorId != $pesan->getPengirimSupervisor() && $pesan->issetPengirimSupervisor())
        {
            return $pesan->getPengirimSupervisor()->getNama();
        }
        if($pesan->issetPenerimaUser())
        {
            return $pesan->getPenerimaUser()->getNama();
        }
        if($supervisorId != $pesan->getPenerimaSupervisorId() && $pesan->issetPenerimaSupervisor())
        {
            return $pesan->getPenerimaSupervisor()->getNama();
        }
        return "";

    }

    /**
     * Get sender
     *
     * @param Pesan $pesan
     * @return string
     */
    public function getSenderFromUser($pesan)
    {
        if($pesan->issetPengirimUser())
        {
            return $pesan->getPengirimUser()->getNama();
        }
        if($pesan->issetPengirimSupervisor())
        {
            return $pesan->getPengirimSupervisor()->getNama();
        }
        return "";
    }

    public function getBrief($message, $max)
    {
        $message = strip_tags($message);
        if(strlen($message) > $max)
        {
            return substr($message, 0, $max);
        }
        return $message;
    }

    public function timeToHumanReadable($inputDateTime)
    {
        $timestamp = strtotime($inputDateTime);
        $now = time();

        $difference = $now - $timestamp;

        $timeStr = "";
        if ($difference < 60) {
            $timeStr = "just now";
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            $timeStr = "$minutes minutes ago";
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            $timeStr = "$hours hours ago";
        } else {
            $days = floor($difference / 86400);
            $timeStr = "$days days ago";
        }
        return $timeStr;
    }

    public function getTime($waktu)
    { 
        if($waktu == null || $waktu == '0000-00-00 00:00:00')
        {
            return "";
        }
        $timeStr = $this->timeToHumanReadable($waktu);
        $timeStr = str_ireplace(
            array(
                'seconds', 'second',
                'minutes', 'minute',
                'hours', 'hour',
                'days', 'day',
                'weeks', 'week',
                'months', 'month',
                'years', 'year',
                'ago',
                'just now'
            ),
            array(
                'detik', 'detik',
                'menit', 'menit',
                'jam', 'jam',
                'hari', 'hari',
                'minggu', 'minggu',
                'bulan', 'bulan',
                'tahun', 'tahun',
                'lalu',
                'baru saja'
            ),
            $timeStr
        );

        return $timeStr;
    }
}