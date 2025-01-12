<?php

use Sipro\Util\MessageUtil;
use Sipro\Util\NotificationUtil;

$notifSize = 8;
$totalNotif = 0;
$resultNotif = array();
$totalNotifStr = "";

$notifUtil = new NotificationUtil($database);
$pageDataNotif = $notifUtil->userNotif($currentUser->getUserId(), 1, $notifSize, false);
if($pageDataNotif != null)
{
    $totalNotif = $pageDataNotif->getTotalResult();
    $resultNotif = $pageDataNotif->getResult();
    if($totalNotif > 99)
    {
        $totalNotifStr = "99+";
    }
    else
    {
        $totalNotifStr = $totalNotif;
    }
}

?>

<ul class="header-nav d-md-flex ms-auto">
    <li class="nav-item dropdown"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <svg class="icon icon-lg my-1 mx-2">
            <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-bell"></use>
        </svg><span class="badge rounded-pill position-absolute top-0 end-0 bg-danger-gradient"><?php echo $totalNotifStr; ?></span></a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg pt-0">

            <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2" data-coreui-i18n="notificationsCounter, { 'counter': <?php echo $totalNotif;?> }">
            <?php 
                echo $totalNotif > 0 ? sprintf($appLanguage->getYouHaveNotification(), $totalNotif) : $appLanguage->getYouDontHaveNotification();
            ?>
            </div>
            
            <div class="dropdown-menu-body">
            <?php
            
            foreach($resultNotif as $notif)
            {
            ?>    
            <a class="dropdown-item" href="notifikasi.php?open=<?php echo $notif->getNotifikasiId();?>">              
                <svg class="icon me-2 text-primary">
                    <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-envelope-closed"></use>
                </svg>
                <span class="notification-subject"><?php echo $notif->getSubjek();?></span>
            </a>
            <?php
            }
            
            ?>
            
        </div>
        
        <div class="dropdown-menu-body">
            <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="server"><?php echo $appLanguage->getAllNotifications();?></div>
                <a class="dropdown-item" href="notifikasi.php">
                    <span class="notification-subject"><?php echo $appLanguage->getAllNotifications();?></span>
                </a>        
            </div>
        </div>
    </li>
    
<?php
$maxMessageLength = 75;
$messageSize = 8;
$totalMessage = 0;
$resultMessage = array();
$totalMessageStr = "";

$messageUtil = new MessageUtil($database);
$pageDataMessage = $messageUtil->userInbox($currentUser->getUserId(), 1, $messageSize, false);
if($pageDataMessage != null)
{
    $totalMessage = $pageDataMessage->getTotalResult();
    $resultMessage = $pageDataMessage->getResult();
    if($totalMessage > 99)
    {
        $totalMessageStr = "99+";
    }
    else
    {
        $totalMessageStr = $totalMessage;
    }
}

?>

    <li class="nav-item dropdown">
        <a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-lg my-1 mx-2">
                <use xlink:href="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
            </svg><span class="badge rounded-pill position-absolute top-0 end-0 bg-info-gradient"><?php echo $totalMessageStr;?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-md pt-0 dropdown-message-header">
            <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2" data-coreui-i18n="messagesCounter, { 'counter': <?php echo $totalMessage;?> }">
                <?php 
                echo $totalMessage > 0 ? sprintf($appLanguage->getYouHaveMessage(), $totalNotif) : $appLanguage->getYouDontHaveMessage();
                ?>
            </div>
            <div class="dropdown-menu-body-with-footer">
                
            <?php
            if(is_array($resultMessage))
            {
                foreach($resultMessage as $idx=>$msg)
                {
                    ?>

                <a class="dropdown-item" href="pesan.php?user_action=detail&pesan_id=<?php echo $msg->getPesanId();?>">
                    <div class="d-flex">
                        
                        <div class="avatar flex-shrink-0 my-3 me-3">
                            <img class="avatar-img" src="<?php echo $baseAssetsUrl; ?><?php echo $themePath; ?>assets/img/avatars/1.jpg">
                            <span class="avatar-status bg-success"></span>
                        </div>
                        
                        <div class="message text-wrap">
                            <div class="d-flex justify-content-between mt-1">
                                <div class="small text-body-secondary"><?php echo $messageUtil->getSenderFromUser($msg);?> </div>
                                <div class="small text-body-secondary"><?php echo $messageUtil->getTime($msg->getWaktuBuat());?></div>
                            </div>
                            <div class="fw-semibold"><span class="text-danger"></span><?php echo $messageUtil->getBrief($msg->getSubjek(), 60);?></div>
                            <div class="small text-body-secondary"><?php echo $messageUtil->getBrief($msg->getIsi(), $maxMessageLength);?>...</div>
                        </div>
                    </div>
                </a>

                    <?php
                }
            }
            ?>
                
            </div>
            <div class="dropdown-divider"></div><a class="dropdown-item text-center" href="pesan.php" data-coreui-i18n="viewAllMessages"><?php echo $appLanguage->getAllMessages();?></a>
        </div>
    </li>
</ul>