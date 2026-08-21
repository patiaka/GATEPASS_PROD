<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $company }} — Welcome</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; -webkit-font-smoothing:antialiased; font-family:'Segoe UI', Roboto, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="width:600px; max-width:100%; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(15,23,42,.08);">

                    {{-- Accent bar --}}
                    <tr><td style="height:6px; background:#134169; line-height:6px; font-size:0;">&nbsp;</td></tr>

                    {{-- Logo --}}
                    <tr>
                        <td align="center" style="padding:28px 24px 6px;">
                            <img src="{{ $message->embed(public_path('assets/img/logo.png')) }}"
                                alt="{{ $company }}" height="52" style="height:52px; display:block; border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:0 24px 6px;">
                            <div style="font-size:12px; letter-spacing:.10em; text-transform:uppercase; color:#94a3b8;">
                                Gate Pass Management
                            </div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:18px 36px 4px; color:#1e293b;">
                            <p style="font-size:16px; font-weight:600; margin:0 0 14px; color:#0f172a;">Hello {{ $name }},</p>
                            <p style="font-size:15px; line-height:1.65; color:#334155; margin:0 0 20px;">
                                Your account for the {{ $company }} Gate Pass Management System has been created.
                                Use the credentials below to sign in.
                            </p>

                            {{-- Credentials card --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; margin:0 0 20px;">
                                <tr>
                                    <td style="padding:16px 20px 4px;">
                                        <div style="font-size:11px; letter-spacing:.06em; text-transform:uppercase; color:#94a3b8; margin-bottom:2px;">Email</div>
                                        <div style="font-size:15px; color:#0f172a; font-weight:600;">{{ $email }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 20px 16px;">
                                        <div style="font-size:11px; letter-spacing:.06em; text-transform:uppercase; color:#94a3b8; margin-bottom:2px;">Temporary password</div>
                                        <div style="font-size:16px; color:#0f172a; font-weight:700; letter-spacing:.02em; font-family:'Courier New', monospace;">{{ $password }}</div>
                                    </td>
                                </tr>
                            </table>

                            {{-- Security note --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background:#fffbeb; border:1px solid #fde68a; border-radius:10px; margin:0 0 24px;">
                                <tr>
                                    <td style="padding:12px 18px; font-size:13px; line-height:1.6; color:#92400e;">
                                        🔒 For your security, you'll be asked to <strong>set a new password</strong> on your first login.
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="border-radius:8px; background:#134169;">
                                        <a href="{{ $loginUrl }}"
                                            style="display:inline-block; padding:13px 30px; color:#ffffff; font-size:14px; font-weight:600; text-decoration:none; border-radius:8px;">
                                            Sign in now
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#64748b; margin:24px 0 0; line-height:1.6;">
                                If the button doesn't work, copy and paste this link into your browser:<br>
                                <a href="{{ $loginUrl }}" style="color:#134169; word-break:break-all;">{{ $loginUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:22px 36px 30px;">
                            <div style="border-top:1px solid #eef2f7; padding-top:16px;">
                                <p style="font-size:12px; color:#94a3b8; margin:0; line-height:1.6;">
                                    This is an automated message from the {{ $company }} Gate Pass Management System.
                                    Please do not reply to this email.
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>

                <p style="font-size:11px; color:#cbd5e1; margin:16px 0 0;">&copy; {{ date('Y') }} {{ $company }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
