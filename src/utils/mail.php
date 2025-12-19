<?php
/**
 * Email Utilities
 *
 * Provides email sending functionality for the application.
 * Uses PHPMailer for SMTP email delivery with welcome email template.
 *
 * @uses mail.ini Configuration file for SMTP settings
 *
 * Security: Validates configuration, error logging for failures
 */

namespace Utils;

require_once __DIR__ . '/../classes/PHPMailer/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../classes/PHPMailer/PHPMailer/SMTP.php';
require_once __DIR__ . '/../classes/PHPMailer/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
 
const MAIL_CONFIGURATION_FILE = __DIR__ . "/../config/mail.ini";
 
/**
 * Send welcome email to new user
 *
 * Sends a formatted HTML welcome email to newly registered users.
 * Reads SMTP configuration from mail.ini file.
 *
 * @param string $recipientEmail Email address of the recipient
 * @param string $username Username of the new user
 * @return bool True if email sent successfully, false otherwise
 */
function sendWelcomeEmail(string $recipientEmail, string $username): bool
{
    if (!file_exists(MAIL_CONFIGURATION_FILE)) {
        error_log("Fichier de configuration mail.ini manquant.");
        return false;
    }
    $config = parse_ini_file(MAIL_CONFIGURATION_FILE);
    if ($config === false) {
        error_log("Erreur de lecture du fichier de configuration mail.ini.");
        return false;
    }
 
    $mail = new PHPMailer(true);
 
    try {
        // Mode debug activé pour diagnostic
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = "html";

        $mail->isSMTP();
        $mail->Host = $config["host"];
        $mail->Port = $config["port"];
        $mail->SMTPAuth = (bool) $config["authentication"];
 
        if ($mail->SMTPAuth) {
            $mail->Username = $config["username"];
            $mail->Password = $config["password"];
 
            $mail->SMTPSecure =
                $config["port"] == 465
                    ? PHPMailer::ENCRYPTION_SMTPS
                    : PHPMailer::ENCRYPTION_STARTTLS;
        }
 
        $appName = $config["from_name"] ?? "Libs Project";
        $mail->setFrom($config["from_email"], $appName);
        $mail->addAddress($recipientEmail);
        $mail->CharSet = "UTF-8";
 
        $mail->isHTML(true);
        $mail->Subject = "Bienvenue sur Libs Project - Votre bibliothèque en ligne !";

        $mail->Body = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        line-height: 1.6;
                        color: #333333;
                        background-color: #FFF5F7;
                        margin: 0;
                        padding: 0;
                    }
                    .email-wrapper {
                        background-color: #FFF5F7;
                        padding: 20px;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #FFFFFF;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 6px rgba(255, 182, 193, 0.2);
                    }
                    .header {
                        background: linear-gradient(135deg, #FFB6C1 0%, #FF69B4 100%);
                        color: white;
                        padding: 30px 20px;
                        text-align: center;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 28px;
                        font-weight: 600;
                    }
                    .header p {
                        margin: 5px 0 0 0;
                        font-size: 14px;
                        opacity: 0.9;
                    }
                    .content {
                        padding: 30px 25px;
                        background-color: #FFFFFF;
                    }
                    .content p {
                        margin-bottom: 15px;
                        color: #333333;
                    }
                    .username {
                        color: #FF69B4;
                        font-weight: 600;
                    }
                    .cta-button {
                        display: inline-block;
                        margin: 20px 0;
                        padding: 12px 30px;
                        background: linear-gradient(135deg, #FFB6C1 0%, #FF69B4 100%);
                        color: white;
                        text-decoration: none;
                        border-radius: 8px;
                        font-weight: 600;
                    }
                    .features {
                        background-color: #FFF5F7;
                        padding: 20px;
                        border-radius: 8px;
                        margin: 20px 0;
                    }
                    .features h3 {
                        color: #FF69B4;
                        margin-bottom: 10px;
                        font-size: 18px;
                    }
                    .features ul {
                        margin: 0;
                        padding-left: 20px;
                        color: #666666;
                    }
                    .features li {
                        margin-bottom: 8px;
                    }
                    .footer {
                        text-align: center;
                        padding: 20px;
                        font-size: 12px;
                        color: #999999;
                        background-color: #FFF5F7;
                        border-top: 1px solid #FFD6DD;
                    }
                    .footer a {
                        color: #FF69B4;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class='email-wrapper'>
                    <div class='container'>
                        <div class='header'>
                            <h1>📚 Bienvenue sur Libs Project !</h1>
                            <p>Votre bibliothèque en ligne</p>
                        </div>
                        <div class='content'>
                            <p>Bonjour <span class='username'>" . htmlspecialchars($username) . "</span>,</p>

                            <p>Nous sommes ravis de vous accueillir parmi nous ! 🎉</p>

                            <p>Votre compte a été créé avec succès. Vous pouvez dès maintenant explorer notre bibliothèque et découvrir tous les livres disponibles.</p>

                            <div class='features'>
                                <h3>Ce que vous pouvez faire :</h3>
                                <ul>
                                    <li>📖 Parcourir notre catalogue de livres</li>
                                    <li>🔍 Rechercher vos ouvrages préférés</li>
                                    <li>❤️ Ajouter des livres à vos favoris</li>
                                    <li>📝 Emprunter et gérer vos prêts</li>
                                </ul>
                            </div>

                            <center>
                                <a href='https://libsproject.ch/login.php' class='cta-button'>Se connecter maintenant</a>
                            </center>

                            <p>Bonne lecture ! 📚✨</p>

                            <p style='margin-top: 25px; color: #666666; font-size: 14px;'>
                                L'équipe Libs Project
                            </p>
                        </div>
                        <div class='footer'>
                            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                            <p>
                                <a href='https://libsproject.ch'>libsproject.ch</a> |
                                <a href='mailto:info@libsproject.ch'>info@libsproject.ch</a>
                            </p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ";

        $mail->AltBody = "Bonjour {$username},\n\nNous sommes ravis de vous accueillir sur Libs Project !\n\nVotre compte a été créé avec succès. Vous pouvez dès maintenant explorer notre bibliothèque et découvrir tous les livres disponibles.\n\nCe que vous pouvez faire :\n- Parcourir notre catalogue de livres\n- Rechercher vos ouvrages préférés\n- Ajouter des livres à vos favoris\n- Emprunter et gérer vos prêts\n\nConnectez-vous sur : https://libsproject.ch/login.php\n\nBonne lecture !\n\nL'équipe Libs Project\n\n---\nCet email a été envoyé automatiquement.\nlibsproject.ch | info@libsproject.ch";
 
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log(
            "Échec de l'envoi de l'e-mail de bienvenue. Erreur PHPMailer: {$mail->ErrorInfo}",
        );
        return false;
    }
}