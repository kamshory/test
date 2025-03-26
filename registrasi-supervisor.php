<?php

use MagicApp\UserAction;
use MagicApp\WaitingFor;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSpecification;
use MagicObject\Exceptions\InvalidInputFormatException;
use MagicObject\MagicObject;
use MagicObject\Request\InputPost;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\Util\PicoPasswordUtil;
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

		$email = $inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);

		$specs = PicoSpecification::getInstance()->addAnd(PicoPredicate::getInstance()->equals(PicoPredicate::functionLower('email'), strtolower($email)));

		$supervisorCheck = new Supervisor(null, $database);
		
		$exists = false;
		try
		{
			$existing = $supervisorCheck->findAll($specs);
			$exists = true;
		}
		catch(Exception $e)
		{
			// do nothing
		}

		if(!$exists)
		{
			$supervisor = new Supervisor(null, $database);
			$supervisor->setNip($inputPost->getNip(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setNamaDepan($inputPost->getNamaDepan(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setNamaBelakang($inputPost->getNamaBelakang(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setNama(trim($supervisor->getNamaDepan().' '.$supervisor->getNamaBelakang()));
			$supervisor->setKoordinator($inputPost->getKoordinator(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
			$supervisor->setJabatanId($inputPost->getJabatanId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true));
			$supervisor->setJenisKelamin($inputPost->getJenisKelamin(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setTempatLahir($inputPost->getTempatLahir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setTanggalLahir($inputPost->getTanggalLahir(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setEmail($inputPost->getEmail(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setTelepon($inputPost->getTelepon(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setUkuranBaju($inputPost->getUkuranBaju(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setUkuranSepatu($inputPost->getUkuranSepatu(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true));
			$supervisor->setIpTerakhirAktif($ip);
			$supervisor->setBlokir(false);
			$supervisor->setAktif(true);
			$supervisor->setDraft(true);
			$supervisor->setWaitingFor(WaitingFor::CREATE);
			$supervisor->setWaktuBuat($now);
			$supervisor->setIpBuat($ip);
			$supervisor->setWaktuUbah($now);
			$supervisor->setIpUbah($ip);

			$util = new PicoPasswordUtil(PicoPasswordUtil::ALG_SHA1);
			$emailObject = new MagicObject();
			try
			{
				$password = $inputPost->getPassword(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);
				$passwordHash = $util->getHash($password, false, true);
				$passwordHash = $util->getHash($passwordHash, false, false);
				$supervisor->setPassword($passwordHash);

				$kodeKonfirmasiEmail = hash("sha512", time().$_SERVER['REMOTE_ADDR'].mt_rand(100000, 999999));
				$hashKodeKonfirmasiEmail = hash("sha512", $kodeKonfirmasiEmail);

				$supervisor->insert();
			
				// konfirmasi email supervisor
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
					$sessions->message = "Sistem tidak dapat mengirimkan email. Silakan ulangi lagi.";
					$sessions->resetPassworSuccess = false;
					header("Location: ".basename($_SERVER['PHP_SELF'])."?success=true");
				}

				
				exit();
			}
			catch(InvalidInputFormatException $e)
			{
				$sessions->message = $e->getMessage();
			}
			catch(Exception $e)
			{
				// do nothing
				$sessions->message = "Terjadi kesalahan";
			}
		}
		else
		{
			$sessions->message = "Email sudah terdaftar";
		}
	}
	else
	{
		$sessions->message = "Captcha salah";
	}
	require_once __DIR__ . "/inc.app/registration.php";
	exit();
}
else
{
	$appIserImpl = null;
	require_once __DIR__ . "/inc.app/registration.php";
	exit();
}
