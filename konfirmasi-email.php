<?php

use MagicApp\UserAction;
use MagicObject\Request\InputPost;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Sipro\Entity\Data\KonfirmasiPendaftaranSupervisor;
use Sipro\Entity\Data\Supervisor;
use Sipro\Util\StringUtil;

require_once __DIR__ . "/inc.app/default.php";
require_once __DIR__ . "/inc.app/session.php";

$inputPost = new InputPost();

if($inputPost->getUserAction() == UserAction::CREATE)
{
	if($inputPost->getCaptcha() == $sessions->captcha)
	{
		$now = date('Y-m-d H:i:s');
		$ip = $_SERVER['REMOTE_ADDR'];

        $email = $inputPost->getEmail();

        $supervisor = new Supervisor(null, $database);
        $supervisor->findOneByEmail($email);

        $konfirmasiPendaftaranSupervisor = new KonfirmasiPendaftaranSupervisor(null, $database);

        $konfirmasiPendaftaranSupervisor->setSupervisorId($supervisor->getSupervisorId());
        $konfirmasiPendaftaranSupervisor->setEmail($supervisor->getEmail());
        $konfirmasiPendaftaranSupervisor->setKodeKonfirmasiEmail($hashKodeKonfirmasiEmail);
        $konfirmasiPendaftaranSupervisor->setAktif(true);
        $konfirmasiPendaftaranSupervisor->setWaktuBuat($now);
        $konfirmasiPendaftaranSupervisor->setIpBuat($ip);
        $konfirmasiPendaftaranSupervisor->setWaktuUbah($now);
        $konfirmasiPendaftaranSupervisor->setIpUbah($ip);

        $konfirmasiPendaftaranSupervisor->insert();

        // nama pengguna
        $emailObject->setNamaPengguna($supervisor->getNama());

        // email pengguna
        $emailObject->setEmailPengguna($supervisor->getEmail());

        // Set application links and data for the email
        $emailObject->setNamaAplikasi($appConfig->getSite()->getName());
        $emailObject->setLinkKonfirmasiEmail($appConfig->getSite()->getBaseUrl() . "/konfirmasi-pendaftaran.php?hash=".$kodeKonfirmasiEmail);
        $emailObject->setLinkKebijakanPrivasi($appConfig->getSite()->getBaseUrl() . "/kebijakan-privasi.php");
        $emailObject->setLinkSyaratKetentuan($appConfig->getSite()->getBaseUrl() . "/syarat-dan-ketentuan.php");

        // Create an instance of PHPMailer
        $mail = new PHPMailer(true);
        $resetPasswordConfig = $appConfig->getMailer();

        // Load the email template
        $resetPasswordTemplate = file_get_contents(__DIR__ . "/template/konfirmasi-pendaftaran.html");

        try {
            // Server settings
            if ($resetPasswordConfig->isSebug()) {
                $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
            }
            $mail->isSMTP(); // Send using SMTP
            $mail->Host = $resetPasswordConfig->getHost(); // Set SMTP server
            $mail->SMTPAuth = $resetPasswordConfig->isAuth(); // Enable SMTP authentication
            $mail->Username = $resetPasswordConfig->getUsername(); // SMTP username
            $mail->Password = $resetPasswordConfig->getPassword(); // SMTP password

            if ($resetPasswordConfig->isTls()) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
            }

            $mail->Port = intval($resetPasswordConfig->getPort()); // Set TCP port

            // Recipients
            $mail->setFrom($resetPasswordConfig->getSenderAddress(), $resetPasswordConfig->getSenderName());
            $mail->addAddress($emailObject->getEmailPengguna(), $emailObject->getNamaPengguna());  // Add recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $resetPasswordConfig->getSubject();

            // Replace placeholders in the email template
            $bodyHtml = StringUtil::replacePlaceholders($resetPasswordTemplate, $emailObject);
            $bodyPlain = StringUtil::convertHtmlToText($bodyHtml);

            $mail->Body = $bodyHtml;
            $mail->AltBody = $bodyPlain;

            // Send email
            $mail->send();
            $sessions->resetPassworSuccess = true;
            header("Location: ".basename($_SERVER['PHP_SELF'])."?success=true");
        } catch (Exception $e) {
            $sessions->message = "Alamat email tidak ditemukan. Silakan ulangi lagi.";
            $sessions->resetPassworSuccess = false;
            header("Location: ".basename($_SERVER['PHP_SELF'])."?success=false");
        }

	}
	else
	{
		$sessions->message = "Captcha salah";
	}
	require_once __DIR__ . "/inc.app/email-confirmation.php";
	exit();
}
else
{
	$appIserImpl = null;
	require_once __DIR__ . "/inc.app/email-confirmation.php";
	exit();
}
