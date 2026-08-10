<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $company }} — Gate Pass Notification</title>
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
                            <p style="font-size:15px; line-height:1.65; color:#334155; margin:0 0 24px;">{{ $body }}</p>

                            {{-- CTA --}}
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="border-radius:8px; background:#134169;">
                                        <a href="{{ $link }}"
                                            style="display:inline-block; padding:13px 30px; color:#ffffff; font-size:14px; font-weight:600; text-decoration:none; border-radius:8px;">
                                            View the request
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#64748b; margin:24px 0 0; line-height:1.6;">
                                If the button doesn't work, copy and paste this link into your browser:<br>
                                <a href="{{ $link }}" style="color:#134169; word-break:break-all;">{{ $link }}</a>
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
