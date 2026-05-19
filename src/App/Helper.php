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
     * Escape output for HTML contexts.
     *
     * @param mixed $value
     * @return string
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Sanitize rich blog content before rendering.
     *
     * @param string $html
     * @return string
     */
    public static function cleanHtml(string $html): string
    {
        $allowedTags = '<p><br><strong><b><em><i><u><ol><ul><li><blockquote><pre><code><h2><h3><h4><a>';
        $html = strip_tags($html, $allowedTags);

        return preg_replace('/<([a-z0-9]+)(?:\s[^>]*)?>/i', '<$1>', $html) ?? '';
    }

    /**
     * Create a signed cookie payload for a user email.
     *
     * @param string $email
     * @return string
     */
    public static function authCookie(string $email): string
    {
        $signature = hash_hmac('sha256', $email, AUTH_COOKIE_SECRET);

        return base64_encode($email . '|' . $signature);
    }

    /**
     * Read and validate a signed auth cookie payload.
     *
     * @param string $cookie
     * @return string|null
     */
    public static function authCookieEmail(string $cookie): ?string
    {
        $payload = base64_decode($cookie, true);
        if ($payload === false || !str_contains($payload, '|')) {
            return null;
        }

        [$email, $signature] = explode('|', $payload, 2);
        $expected = hash_hmac('sha256', $email, AUTH_COOKIE_SECRET);

        return hash_equals($expected, $signature) ? $email : null;
    }

    /**
     * Set the web auth cookie consistently.
     *
     * @param string $email
     * @param int|null $expires
     * @return bool
     */
    public static function setAuthCookie(string $email, ?int $expires = null): bool
    {
        return setcookie('loggedin', self::authCookie($email), [
            'expires' => $expires ?? time() + (86400 * COOKIE_DAYS),
            'path' => '/',
            'secure' => parse_url(URL_ROOT, PHP_URL_SCHEME) === 'https',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    /**
     * Check Cross-site request forgery token
     *
     * @param string $token
     * @return bool
     */
    public static function csrf(string $token): bool
    {
        if (isset($_SESSION['token'], $_SESSION['token-expire']) && hash_equals($_SESSION['token'], $token)) {
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
    public static function render(string $view, array $data = []): void
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
     * @return void
     */
    #[NoReturn] public static function dd(mixed $var, bool $pretty = true): void
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
            $mail->isHTML();
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = strip_tags($message);

            $mail->send();

            return true;
        } catch (Exception) {
            return "Message could not be sent. Mailer Error: $mail->ErrorInfo";
        }
    }

    /**
     * Log custom data to the log file
     *
     * @param string $message
     * @return void
     */
    public static function log(string $message): void
    {
        $logInfo = '[' . date('D Y-m-d h:i:s A') . '] [client ' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . '] ';

        // Create file and make sure if written by root, then accessible by www
        $logFile = LOG_FILE_BASENAME . date('Ymd') . '.log';
        $fHandler = fopen('/var/www/' . LOG_DIR . $logFile, 'a+');
        fwrite($fHandler, $logInfo . $message . PHP_EOL);
        fclose($fHandler);
        chown('/var/www/' . LOG_DIR . $logFile, 'www');
    }
}
