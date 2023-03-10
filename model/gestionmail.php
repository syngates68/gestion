<?php
ini_set('max_execution_time', 0);

require_once(ROOT.'vendor/autoload.php');

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class GestionMail
{
    public static function confirmation($user)
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
		$mail->SMTPAuth = MAIL_SMTPAUTH;
		$mail->Username = MAIL_USERNAME;
		$mail->Password = MAIL_PASSWORD;
		$mail->SMTPSecure = MAIL_SMTPSECURE;
		$mail->Port = MAIL_PORT;

        $mail->From = 'mailer@coprotec.net';
        $mail->FromName = 'GROGU';

        $mail->addAddress('q.schifferle@coprotec.net', '');

        $mail->Subject = utf8_decode('Confirmation de votre adresse mail.');

        $mail->Body = utf8_decode(include('./view/mail/confirmation.inc.php'));
        $mail->AltBody = utf8_decode('Veuillez confirmer votre adresse mail afin de finaliser votre inscription.');
        $mail->isHTML(true);

        $mail->send();
    }

    public static function invite($mail, $user, $page, $invitation)
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
		$mail->SMTPAuth = MAIL_SMTPAUTH;
		$mail->Username = MAIL_USERNAME;
		$mail->Password = MAIL_PASSWORD;
		$mail->SMTPSecure = MAIL_SMTPSECURE;
		$mail->Port = MAIL_PORT;

        $mail->From = 'mailer@coprotec.net';
        $mail->FromName = 'GROGU';

        $mail->addAddress('q.schifferle@coprotec.net', '');

        $mail->Subject = utf8_decode('Invitation à rejoindre la page '.$page->name());

        $mail->Body = utf8_decode(include('./view/mail/invitation.inc.php'));
        $mail->AltBody = utf8_decode('Vous avez été invité à rejoindre une page sur GROGU.');
        $mail->isHTML(true);

        $mail->send();
    }

    public static function reset_password($mail, $user)
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
		$mail->SMTPAuth = MAIL_SMTPAUTH;
		$mail->Username = MAIL_USERNAME;
		$mail->Password = MAIL_PASSWORD;
		$mail->SMTPSecure = MAIL_SMTPSECURE;
		$mail->Port = MAIL_PORT;

        $mail->From = 'mailer@coprotec.net';
        $mail->FromName = 'GROGU';

        $mail->addAddress('q.schifferle@coprotec.net', '');

        $mail->Subject = utf8_decode('Réinitialisation du mot de passe');

        $mail->Body = utf8_decode(include('./view/mail/reset_password.inc.php'));
        $mail->AltBody = utf8_decode('Veuillez suivre la procédure ci-jointe pour réinitiliser votre mot de passe.');
        $mail->isHTML(true);

        $mail->send();
    }
}