<?php

namespace Sipro\Util;

use Exception;
use MagicObject\Database\PicoDatabase;
use MagicObject\MagicObject;
use Sipro\Entity\Data\Admin;

class UserUtil
{
    /**
     * Retrieve an admin user based on the provided Admin ID.
     *
     * This method searches for an admin user using the given Admin ID.
     * If no admin is found, an empty Admin object is returned.
     *
     * @param PicoDatabase $database Database connection instance.
     * @param int $adminId The unique Admin ID of the admin.
     * @return MagicObject The admin user object, or an empty object if not found.
     */
    public static function getAdmin($database, $adminId)
    {
        $admin = new Admin(null, $database);
        try
        {
            $admin->findOneByAdminId($adminId);
        }
        catch(Exception $e)
        {
            // Exception suppressed, returning an empty Admin object.
        }
        return $admin;
    }

    /**
     * Retrieve an admin user based on the provided KTSK ID.
     *
     * This method searches for an admin user using the given KTSK ID.
     * If no admin is found, an empty Admin object is returned.
     *
     * @param PicoDatabase $database Database connection instance.
     * @param int $ktskId The unique KTSK ID of the admin.
     * @return MagicObject The admin user object, or an empty object if not found.
     */
    public static function getAdminFromKtskId($database, $ktskId)
    {
        $admin = new Admin(null, $database);
        try
        {
            $database->setCallbackDebugQuery(function($sql){
                echo $sql."\r\n";
            });
            $admin->findOneByKtskId($ktskId);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            // Exception suppressed, returning an empty Admin object.
        }
        return $admin;
    }

    /**
     * Retrieve an admin user based on the provided Supervisor ID.
     *
     * This method searches for an admin user using the given Supervisor ID.
     * If no admin is found, an empty Admin object is returned.
     *
     * @param PicoDatabase $database Database connection instance.
     * @param int $supervisorId The unique Supervisor ID of the admin.
     * @return MagicObject The admin user object, or an empty object if not found.
     */
    public static function getAdminFromSupervisorId($database, $supervisorId)
    {
        $admin = new Admin(null, $database);
        try
        {
            $admin->findOneBySupervisorId($supervisorId);
        }
        catch(Exception $e)
        {
            // Exception suppressed, returning an empty Admin object.
        }
        return $admin;
    }
}
