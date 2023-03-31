<?php

use Dotenv\Dotenv;
use Me\BjoernBuettner\Antivirus;
use Me\BjoernBuettner\Database;
use PhpImap\Exceptions\ConnectionException;
use PhpImap\IncomingMail;
use PhpImap\Mailbox;
use PHPMailer\PHPMailer\PHPMailer;

require_once (__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

$antivirus = new Antivirus();

$mailbox = $mailbox = new Mailbox(
    '{' . $_ENV['TICKET_MAIL_HOST'] . ':' . $_ENV['TICKET_MAIL_PORT_IMAP'] . '/imap/ssl}INBOX',
    $_ENV['TICKET_MAIL_USER'],
    $_ENV['TICKET_MAIL_PASSWORD']
);
$mailbox->setConnectionArgs(CL_EXPUNGE);

$database = Database::get();
try {
    $mailIds = $mailbox->searchMailbox('ALL');
    if(!$mailIds) {
        return;
    }
    foreach ($mailIds as $mailId) {
        $mail = $mailbox->getMail($mailId);
        if ($mail instanceof IncomingMail) {
            if ($mail->subject === 'Undelivered Mail Returned to Sender') {
                $mailbox->deleteMail($mailId);
                continue;
            }
            if ($mail->textPlain ?? $mail->textHtml === null || $mail->textPlain ?? $mail->textHtml === '') {
                $mailbox->deleteMail($mailId);
                continue;
            }
            $stmt = $database->prepare('SELECT * FROM `user` WHERE email=:email');
            $stmt->execute([':email' => $mail->fromAddress]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                $mailer = new PHPMailer();
                $mailer->setFrom($_ENV['TICKET_MAIL_FROM'], $_ENV['TICKET_MAIL_FROM_NAME']);
                $mailer->addAddress($mail->senderAddress, $mail->senderName ?? '');
                $mailer->Host = $_ENV['TICKET_MAIL_HOST'];
                $mailer->Username = $_ENV['TICKET_MAIL_USER'];
                $mailer->Password = $_ENV['TICKET_MAIL_PASSWORD'];
                $mailer->Port = intval($_ENV['TICKET_MAIL_PORT_IMAP'], 10);
                $mailer->CharSet = 'utf-8';
                $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mailer->Timeout = 60;
                $mailer->Mailer ='smtp';
                $mailer->Subject = 'RE: '.$mail->subject;
                $mailer->Body = 'Sadly your eMail-Account is unknown to the ticketing system. Please have it registered via a ticket by an Administrator.';
                $mailer->SMTPAuth = true;
                if (!$mailer->smtpConnect()) {
                    error_log('Mailer failed smtp connect. ' . $mailer->ErrorInfo);
                    continue;
                }
                if (!$mailer->send()) {
                    error_log('Mailer failed sending mail. ' . $mailer->ErrorInfo);
                    continue;
                }
                $mailbox->deleteMail($mailId);
                continue;
            }
            if (preg_match('/BBTicket\s+#([0-9]+)/i', $mail->subject ?? '', $matches)) {
                $id = $matches[1];
                $stmt = $database->prepare('SELECT * FROM ticket WHERE aid=:aid');
                $stmt->execute([':aid' => $id]);
                $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($ticket) {
                    if ($user['organisation']!==null && $ticket['customer'] !== $user['organisation']) {
                        $mailer = new PHPMailer();
                        $mailer->setFrom($_ENV['TICKET_MAIL_FROM'], $_ENV['TICKET_MAIL_FROM_NAME']);
                        $mailer->addAddress($mail->senderAddress, $mail->senderName ?? '');
                        $mailer->Host = $_ENV['TICKET_MAIL_HOST'];
                        $mailer->Username = $_ENV['TICKET_MAIL_USER'];
                        $mailer->Password = $_ENV['TICKET_MAIL_PASSWORD'];
                        $mailer->Port = intval($_ENV['TICKET_MAIL_PORT_IMAP'], 10);
                        $mailer->CharSet = 'utf-8';
                        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mailer->Timeout = 60;
                        $mailer->Mailer ='smtp';
                        $mailer->Subject = 'RE: '.$mail->subject;
                        $mailer->Body = 'You don\'t have access to the given ticket.';
                        $mailer->SMTPAuth = true;
                        if (!$mailer->smtpConnect()) {
                            error_log('Mailer failed smtp connect. ' . $mailer->ErrorInfo);
                            continue;
                        }
                        if (!$mailer->send()) {
                            error_log('Mailer failed sending mail. ' . $mailer->ErrorInfo);
                            continue;
                        }
                        $mailbox->deleteMail($mailId);
                        continue;
                    }
                    $database
                        ->prepare('INSERT INTO ticket_comment (`ticket`,`raw_mail`,`creator`,`content`) VALUES (:ticket,:raw_mail,:creator,:content)')
                        ->execute([
                            ':ticket' => $id,
                            ':content' => $mail->textPlain ?? $mail->textHtml ?? 'Empty mail.',
                            ':creator' => $user['aid'],
                            ':raw_mail' => $mail->headersRaw ?? '',
                        ]);
                    $database
                        ->prepare('INSERT IGNORE INTO ticket_watcher (`ticket`,`watcher`) VALUES (:ticket,:watcher)')
                        ->execute([':ticket' => $id, ':watcher' => $user['aid']]);
                    $database
                        ->prepare('INSERT IGNORE INTO ticket_watcher (`ticket`,`watcher`) VALUES (:ticket,:watcher)')
                        ->execute([':ticket' => $id, ':watcher' => 1]);
                    if ($mail->hasAttachments()) {
                        foreach ($mail->getAttachments() as $attachment) {
                            $content = $attachment->getContents();
                            if ($antivirus->sclean($content)) {
                                $database
                                    ->prepare('INSERT IGNORE INTO ticket_attachment (`name`,`ticket`,`uploader`,`hash`,`data`,`mime`) VALUES(:name,:ticket,:uploader,:hash,:data,:mime)')
                                    ->execute([
                                        ':name' => $attachment->name ?? 'unknown',
                                        ':ticket' => $id,
                                        ':uploader' => $user['aid'],
                                        ':hash' => md5($content),
                                        ':data' => $content,
                                        ':mime' => $attachment->mime ?? 'application/octet-stream'
                                    ]);
                            }
                        }
                    }
                    $mailbox->deleteMail($mailId);
                    continue;
                }
            }
            $database
                ->prepare('INSERT INTO ticket (customer,subject) VALUES (:customer,:subject)')
                ->execute([':customer' => $user['organisation'], ':subject' => $mail->subject ?? 'Unknown Subject']);
            $ticket = $database->lastInsertId();
            $database
                ->prepare('INSERT INTO ticket_comment (`ticket`,`raw_mail`,`creator`,`content`) VALUES (:ticket,:raw_mail,:creator,:content)')
                ->execute([
                    ':ticket' => $ticket,
                    ':raw_mail' => $mail->headersRaw ?? '',
                    ':creator' => $user['aid'],
                    ':content' => $mail->textPlain ?? $mail->textHtml ?? 'Empty mail.',
                ]);
            $database
                ->prepare('INSERT IGNORE INTO ticket_watcher (`ticket`,`watcher`) VALUES (:ticket,:watcher)')
                ->execute([':ticket' => $ticket, ':watcher' => $user['aid']]);
            $database
                ->prepare('INSERT IGNORE INTO ticket_watcher (`ticket`,`watcher`) VALUES (:ticket,:watcher)')
                ->execute([':ticket' => $ticket, ':watcher' => 1]);
            if ($mail->hasAttachments()) {
                foreach ($mail->getAttachments() as $attachment) {
                    $content = $attachment->getContents();
                    if ($antivirus->sclean($content)) {
                        $database
                            ->prepare('INSERT IGNORE INTO ticket_attachment (`name`,`ticket`,`uploader`,`hash`,`data`,`mime`) VALUES(:name,:ticket,:uploader,:hash,:data,:mime)')
                            ->execute([
                                ':name' => $attachment->name ?? 'unknown',
                                ':ticket' => $ticket,
                                ':uploader' => $user['aid'],
                                ':hash' => md5($content),
                                ':data' => $content,
                                ':mime' => $attachment->mime ?? 'application/octet-stream'
                            ]);
                    }
                }
            }
            $mailbox->deleteMail($mailId);
        }
    }
} catch(ConnectionException $ex) {
    echo "IMAP connection failed: " . implode(",", $ex->getErrors('all'));
    die(1);
}