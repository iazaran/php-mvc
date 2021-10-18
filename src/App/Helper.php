<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Helper
 * @package App
 */
class Helper
{
    /**
     * Check Cross-site request forgery token
     *
     * @param string $token
     * @return bool
     */
    public static function csrf(string $token): bool
    {
        if ($_SESSION['token'] === $token) {
            if (time() <= $_SESSION['token-expire']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Load a view file like Home/home and assign data to it
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public static function render(string $view, array $data = [])
    {
        $file = APP_ROOT . '/src/Views/' . $view . '.php';

        if (is_readable($file)) require_once $file;
        else die('404 Page not found');
    }

    /**
     * Slugify string to make user friendly URL
     *
     * @param string $str
     * @param string $delimiter
     * @param bool $addDate
     * @return string
     */
    public static function slug(string $str, string $delimiter = '-', bool $addDate = true): string
    {
        $slug = strtolower(
            trim(
                preg_replace(
                    '/[\s-]+/',
                    $delimiter,
                    preg_replace(
                        '/[^A-Za-z0-9-]+/',
                        $delimiter,
                        preg_replace(
                            '/[&]/',
                            'and',
                            preg_replace(
                                '/[\']/',
                                '',
                                iconv('UTF-8', 'ASCII//TRANSLIT', $str)
                            )
                        )
                    )
                ),
                $delimiter
            )
        );
        return $slug . ($addDate ? '-' . date('d-m-Y') : '');
    }

    // Thanks for great codes: https://gist.github.com/lindelius/4881d2b27fa04356b5736cad81b8c9de
    /**
     * Dumps a given variable along with some additional data
     *
     * @param mixed $var
     * @param bool $pretty
     */
    #[NoReturn] public static function dd(mixed $var, bool $pretty = true)
    {
        $backtrace = debug_backtrace();

        echo "<style>
            pre {
                background: dimgrey;
                border-left: 10px solid darkorange;
                color: whitesmoke;
                page-break-inside: avoid;
                font-family: monospace;
                font-size: 15px;
                line-height: 1.4;
                margin-bottom: 1.4em;
                max-width: 100%;
                overflow: auto;
                padding: 1em 1.4em;
                display: block;
                word-wrap: break-word;
            }
        </style>";
        echo "\n<pre>\n";
        if (isset($backtrace[0]['file'])) {
            echo "<i>" . $backtrace[0]['file'] . "</i>\n\n";
        }
        echo "<small>Type:</small> <strong>" . gettype($var) . "</strong>\n";
        echo "<small>Time: " . date('c') . "</small>\n";
        echo "--------------------------\n\n";
        ($pretty) ? print_r($var) : var_dump($var);
        echo "</pre>\n";
        die;
    }

    /**
     * Send HTML Email
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool|string
     */
    public static function mailto(string $to, string $subject, string $message): bool|string
    {
        // Passing `true` enables exceptions
        $mail = new PHPMailer();

        try {
            // Enable verbose debug output
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            // Send using SMTP
            if (MAIL_MAILER === 'smtp') $mail->isSMTP();
            // Set the SMTP server to send through
            $mail->Host = MAIL_HOST;
            // Enable SMTP authentication
            $mail->SMTPAuth = (MAIL_ENCRYPTION !== 'null');
            // SMTP username
            $mail->Username = MAIL_USERNAME;
            // SMTP password
            $mail->Password = MAIL_PASSWORD;
            // ENCRYPTION_SMTPS (implicit TLS on port 465) or ENCRYPTION_STARTTLS (explicit TLS on port 587)
            $mail->SMTPSecure = MAIL_ENCRYPTION ?? PHPMailer::ENCRYPTION_STARTTLS;
            // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->Port = MAIL_PORT;

            // Recipients
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to, 'Dear');
            $mail->addReplyTo(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addCC(MAIL_CC);
            $mail->addBCC(MAIL_BCC);

            // Attachments
            // $mail->addAttachment('/tmp/image.jpg', 'filename.jpg');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = strip_tags($message);

            $mail->send();

            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    /**
     * Log custom data to the log file
     *
     * @param string $message
     */
    public static function log(string $message)
    {
        $logInfo = '[' . date('D Y-m-d h:i:s A') . '] [client ' . $_SERVER['REMOTE_ADDR'] . '] ';

        // Create DIR if needed
        if (!file_exists(LOG_DIR)) {
            mkdir(LOG_DIR, 0755, true);
        }

        // Create file
        $logFile = LOG_FILE_BASENAME . date('Ymd') . '.log';
        $fHandler = fopen(LOG_DIR . $logFile,'a+');
        fwrite($fHandler, $logInfo . $message . PHP_EOL);
        fclose($fHandler);
    }
}
