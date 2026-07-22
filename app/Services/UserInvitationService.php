<?php

namespace App\Services;

use CodeIgniter\Email\Email;
use RuntimeException;

class UserInvitationService
{
    public function sendWelcomeEmail(array $user, string $plainPassword): void
    {
        helper('url');

        $emailAddress = trim((string) ($user['email'] ?? ''));

        if ($emailAddress === '') {
            throw new RuntimeException('Adresse email manquante pour l’envoi du message.');
        }

        $config = config('Email');
        $appConfig = config('App');

        $fromEmail = trim((string) ($config->fromEmail ?? ''));
        $fromName = trim((string) ($config->fromName ?? ''));
        $appName = trim((string) ($appConfig->appName ?? '')) ?: 'Classe Passerelle';

        if ($fromEmail === '' || $fromName === '') {
            throw new RuntimeException('La configuration email de l’application est incomplète.');
        }

        /** @var Email $email */
        $email = service('email');
        $email->initialize([
            'protocol' => $config->protocol,
            'mailPath' => $config->mailPath,
            'SMTPHost' => $config->SMTPHost,
            'SMTPUser' => $config->SMTPUser,
            'SMTPPass' => $config->SMTPPass,
            'SMTPPort' => $config->SMTPPort,
            'SMTPTimeout' => $config->SMTPTimeout,
            'SMTPKeepAlive' => $config->SMTPKeepAlive,
            'SMTPCrypto' => $config->SMTPCrypto,
            'wordWrap' => $config->wordWrap,
            'wrapChars' => $config->wrapChars,
            'mailType' => 'html',
            'charset' => $config->charset,
            'validate' => $config->validate,
            'priority' => $config->priority,
            'CRLF' => $config->CRLF,
            'newline' => $config->newline,
            'BCCBatchMode' => $config->BCCBatchMode,
            'BCCBatchSize' => $config->BCCBatchSize,
            'DSN' => $config->DSN,
        ]);

        $payload = [
            'appName' => $appName,
            'userName' => trim((string) (($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''))),
            'email' => $emailAddress,
            'password' => $plainPassword,
            'loginUrl' => base_url('login'),
            'resetNote' => 'Lors de votre premiere connexion, vous devrez remplacer ce mot de passe temporaire par un mot de passe personnel.',
            'welcomeMessage' => sprintf(
                'Votre compte a été créé sur %s. Vous pouvez maintenant vous connecter avec les informations ci-dessous.',
                $appName
            ),
        ];

        $message = view('emails/user_welcome_html', $payload);

        $email->setFrom($fromEmail, $fromName);
        $email->setTo($emailAddress);
        $email->setSubject(sprintf('Bienvenue sur %s', $appName));
        $email->setMessage($message);

        if (! $email->send(false)) {
            throw new RuntimeException(
                'L’email de bienvenue n’a pas pu être envoyé. ' . trim($email->printDebugger(['headers', 'subject']))
            );
        }
    }
}
