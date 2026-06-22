<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 12px 32px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);padding:28px 32px;color:#ffffff;">
                            <div style="font-size:14px;opacity:.9;letter-spacing:.04em;text-transform:uppercase;">
                                <?= esc($appName) ?>
                            </div>
                            <div style="font-size:28px;line-height:1.2;font-weight:700;margin-top:8px;">
                                Bienvenue à bord
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.7;">
                                Bonjour <strong><?= esc($userName !== '' ? $userName : 'utilisateur') ?></strong>,
                            </p>
                            <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#374151;">
                                <?= esc($welcomeMessage) ?>
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;margin:0 0 24px;">
                                <tr>
                                    <td style="padding:14px 16px;background:#f8fafc;font-weight:700;width:180px;">Nom</td>
                                    <td style="padding:14px 16px;"><?= esc($userName !== '' ? $userName : '-') ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 16px;background:#f8fafc;font-weight:700;">Adresse email</td>
                                    <td style="padding:14px 16px;"><?= esc($email) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 16px;background:#f8fafc;font-weight:700;">Mot de passe temporaire</td>
                                    <td style="padding:14px 16px;font-family:Consolas,Monaco,monospace;"><?= esc($password) ?></td>
                                </tr>
                            </table>
                            <div style="text-align:center;margin:32px 0;">
                                <a href="<?= esc($loginUrl) ?>" style="display:inline-block;background:#0d6efd;color:#ffffff;text-decoration:none;padding:14px 26px;border-radius:10px;font-weight:700;">
                                    Se connecter
                                </a>
                            </div>
                            <p style="margin:0;font-size:14px;line-height:1.7;color:#6b7280;">
                                Si vous n’êtes pas à l’origine de cette création, contactez immédiatement l’administration.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
