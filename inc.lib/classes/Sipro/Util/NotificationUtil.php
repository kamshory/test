<?php

namespace Sipro\Util;

use Exception;
use MagicObject\Database\PicoPageable;
use MagicObject\Database\PicoPageData;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use Sipro\Entity\Data\Notifikasi;

class NotificationUtil
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
     * User notif
     *
     * @param integer $userId
     * @param integer $pageNumber
     * @param integer $pageSize
     * @param boolean $read
     * @return PicoPageData
     */
    public function userNotif($userId, $pageNumber, $pageSize, $read = null)
    {
        $notifFinder = new Notifikasi(null, $this->database);
            
        $specsNotif = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->equals('tipePengguna', 'user'))
            ->addAnd(PicoPredicate::getInstance()->equals('userId', $userId))
        ;

        if(isset($read))
        {
            $specsNotif->addAnd(array('dibaca', $read === true));
        }

        $sortableNotif = PicoSortable::getInstance()
            ->add(new PicoSort('waktuBuat', PicoSort::ORDER_TYPE_DESC))
            ->add(new PicoSort('notifikasiId', PicoSort::ORDER_TYPE_DESC))
        ;

        $pageableNotif = new PicoPageable(array($pageNumber, $pageSize), $sortableNotif);
        try
        {
            return $notifFinder->findAll($specsNotif, $pageableNotif, $sortableNotif, true);
        }
        catch(Exception $e)
        {
            // no notification
        }
        return null;
    }

    /**
     * User notif
     *
     * @param integer $supervisorId
     * @param integer $pageNumber
     * @param integer $pageSize
     * @param boolean $read
     * @return PicoPageData
     */
    public function supervisorNotif($supervisorId, $pageNumber, $pageSize, $read = null)
    {
        $notifFinder = new Notifikasi(null, $this->database);
            
        $specsNotif = PicoSpecification::getInstance()
            ->addAnd(PicoPredicate::getInstance()->equals('tipePengguna', 'supervisor'))
            ->addAnd(PicoPredicate::getInstance()->equals('supervisorId', $supervisorId))
        ;

        if(isset($read))
        {
            $specsNotif->addAnd(array('dibaca', $read === true));
        }

        $sortableNotif = PicoSortable::getInstance()
            ->add(new PicoSort('waktuBuat', PicoSort::ORDER_TYPE_DESC))
            ->add(new PicoSort('notifikasiId', PicoSort::ORDER_TYPE_DESC))
        ;

        $pageableNotif = new PicoPageable(array($pageNumber, $pageSize), $sortableNotif);
        try
        {
            return $notifFinder->findAll($specsNotif, $pageableNotif, $sortableNotif, true);
        }
        catch(Exception $e)
        {
            // no notification
        }
        return null;
    }
}