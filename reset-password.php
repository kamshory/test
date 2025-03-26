<?php

use MagicObject\MagicObject;
use MagicObject\Request\InputPost;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Sipro\Entity\Data\SupervisorResetPassword;
use Sipro\Entity\Data\UserResetPassword;
use Sipro\Util\StringUtil;

require_once __DIR__ . "/inc.app/app.php";
require_once __DIR__ . "/inc.app/session.php";
require_once __DIR__ . "/inc.app/default.php";

$inputPost = new InputPost();
$errorMessage = null;
$appIserImpl = null;

if ($inputPost->getEmail() !== null && $inputPost->getAnswer() !== null) {
    if ($inputPost->getAnswer() !== $sessions->captcha) {
        $sessions->message = "Captcha salah";
    } else {
        $found = false;
        $tipePengguna = null;
        $emailPenerima = null;
        $namaPengguna = null;
        $emailObject = new MagicObject();
        $resetPasswordHash = hash("sha512", $database->generateNewId());

        try {
            // Search in table supervisor
            $supervisor = new SupervisorResetPassword(null, $database);
            $supervisor->findOneByEmail($inputPost->getEmail());
            if ($supervisor->issetSupervisorId()) {
                $found = true;
                $tipePengguna = "supervisor";
                $namaPengguna = trim($supervisor->getNamaDepan() . " " . $supervisor->getNamaBelakang());
                $emailObject->setNamaPengguna($namaPengguna);
                $emailObject->setEmailPengguna($inputPost->getEmail());
                $supervisor->setAuth($resetPasswordHash);
                $supervisor->update();
            }
        } catch (Exception $e) {
            // Do nothing
        }

        if (!$found) {
            try {
                // Search in table user
                $user = new UserResetPassword(null, $database);
                $user->findOneByEmail($inputPost->getEmail());
                if ($user->issetUserId()) {
                    $found = true;
                    $tipePengguna = "user";
                    $emailPenerima = $inputPost->getEmail();
                    $namaPengguna = trim($user->getFirstName() . " " . $user->getLastName());
                    $emailObject->setNamaPengguna($namaPengguna);
                    $emailObject->setEmailPengguna($inputPost->getEmail());
                    $user->setToken($resetPasswordHash);
                    $user->update();
                }
            } catch (Exception $e) {
                // Do nothing
            }
        }

        if ($found) {
            // Set reset password link based on user type (supervisor or user)
            if ($tipePengguna === "supervisor") {
                // Reset password for supervisor
                $emailObject->setLinkResetPassword($appConfig->getSite()->getBaseUrl() . "/reset-password-supervisor.php?hash=$resetPasswordHash");
            } elseif ($tipePengguna === "user") {
                // Reset password for user
                $emailObject->setLinkResetPassword($appConfig->getSite()->getBaseUrl() . "/reset-password-user.php?hash=$resetPasswordHash");
            }

            // Set application links and data for the email
            $emailObject->setNamaAplikasi($appConfig->getSite()->getName());
            $emailObject->setLinkKebijakanPrivasi($appConfig->getSite()->getBaseUrl() . "/kebijakan-privasi.php");
            $emailObject->setLinkSyaratKetentuan($appConfig->getSite()->getBaseUrl() . "/syarat-dan-ketentuan.php");

            // Create an instance of PHPMailer
            $mail = new PHPMailer(true);
            $resetPasswordConfig = $appConfig->getMailer();

            // Load the email template
            $resetPasswordTemplate = file_get_contents(__DIR__ . "/template/reset-password.html");

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
                $mail->Subject = "Reset Password";

                // Replace placeholders in the email template
                $bodyHtml = StringUtil::replacePlaceholders($resetPasswordTemplate, $emailObject);
                $bodyPlain = StringUtil::convertHtmlToText($bodyHtml);

                $mail->Body = $bodyHtml;
                $mail->AltBody = $bodyPlain;

                // Send email
                $mail->send();
                $sessions->resetPassworSuccess = true;
            } catch (Exception $e) {
                $sessions->message = "Sistem tidak dapat mengirimkan email. Silakan ulangi lagi.";
            }
        } else {
            $sessions->message = "Email tidak ditemukan. Silakan periksa lagi email Anda.";
        }
    }
}

if ($appIserImpl === null) {
    require_once __DIR__ . "/inc.app/reset-password.php";
    exit();
}
