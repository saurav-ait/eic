<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            font-family: Arial, sans-serif;
            color: #333333;
        }

        .email-body {
            text-align: left !important;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333333;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .email-body p,
        .email-body div,
        .email-body li,
        .email-body h1,
        .email-body h2,
        .email-body h3 {
            text-align: left !important;
            line-height: 1.4 !important;
        }

        .email-body p {
            margin: 0 0 8px !important;
        }

        .email-body h1,
        .email-body h2,
        .email-body h3 {
            margin: 12px 0 6px !important;
        }

        .email-body ul,
        .email-body ol {
            margin-top: 6px !important;
            margin-bottom: 8px !important;
            padding-left: 22px !important;
        }

        .email-body li {
            margin-bottom: 3px !important;
        }

        @media only screen and (max-width: 480px) {
            .email-container {
                padding: 15px !important;
            }

            .email-title {
                font-size: 17px !important;
            }

            .action-button {
                font-size: 10px !important;
                padding: 8px 2px !important;
            }

            .action-icon {
                width: 13px !important;
                height: 13px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background:#ffffff; font-family:Arial,sans-serif;">

    <!-- Full-width white email background -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
           border="0" bgcolor="#ffffff"
           style="width:100%; background:#ffffff; margin:0; padding:0;">
        <tr>
            <td align="center" bgcolor="#ffffff" style="background:#ffffff;">

                <!-- Main email container -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                       border="0" bgcolor="#ffffff"
                       style="width:100%; background:#ffffff;
                              border:1px solid #e0e0e0; border-radius:8px;">
                    <tr>
                        <td class="email-container" style="padding:25px;">

                            <!-- Header -->
                            <table role="presentation" width="100%" cellpadding="0"
                                   cellspacing="0" border="0">
                                <tr>
                                    <td align="center"
                                        style="border-bottom:3px solid #1E4BA6;
                                               padding-bottom:15px;">
                                        <h2 class="email-title"
                                            style="margin:0; color:#1E4BA6;
                                                   font-size:20px; line-height:1.4;
                                                   text-align:center;">
                                            HireAsia Workforce -<br>
                                            Agencija za Zapošljavanje
                                            Stranih Radnika
                                        </h2>
                                    </td>
                                </tr>
                            </table>

                            <!-- Email body: normal left-aligned reading layout -->
                            <table role="presentation" width="100%" cellpadding="0"
                                   cellspacing="0" border="0">
                                <tr>
                                    <td class="email-body" align="left"
                                        style="padding-top:25px; text-align:left;
                                               font-family:Arial,sans-serif;
                                               font-size:14px; line-height:1.4;
                                               color:#333333;">
                                        {!! $body !!}
                                    </td>
                                </tr>
                            </table>

                            <!-- Contact action buttons -->
                            <table role="presentation" align="center" width="100%"
                                   cellpadding="0" cellspacing="0" border="0"
                                   style="width:100%; margin:25px auto 0;">
                                <tr>
                                    <!-- WhatsApp -->
                                    <td align="center" width="33.33%" style="padding:4px;">
                                        <a href="https://wa.me/message/SEFRUPYMXID7A1"
                                           target="_blank"
                                           class="action-button"
                                           style="display:block; background:#25D366;
                                                  color:#ffffff; text-decoration:none;
                                                  padding:9px 3px; border-radius:5px;
                                                  font-size:11px; font-weight:bold;
                                                  text-align:center; white-space:nowrap;">
                                            <img class="action-icon"
                                                 src="https://img.icons8.com/color/48/whatsapp--v1.png"
                                                 width="14" height="14" alt="WhatsApp"
                                                 style="vertical-align:middle; border:0;">
                                            &nbsp;Let's Chat
                                        </a>
                                    </td>

                                    <!-- Website -->
                                    <td align="center" width="33.33%" style="padding:4px;">
                                        <a href="https://www.hire-asia.work/"
                                           target="_blank"
                                           class="action-button"
                                           style="display:block; background:#1E4BA6;
                                                  color:#ffffff; text-decoration:none;
                                                  padding:9px 3px; border-radius:5px;
                                                  font-size:11px; font-weight:bold;
                                                  text-align:center; white-space:nowrap;">
                                            <img class="action-icon"
                                                 src="https://img.icons8.com/ios-filled/50/ffffff/globe.png"
                                                 width="14" height="14" alt="Website"
                                                 style="vertical-align:middle; border:0;">
                                            &nbsp;Visit Us
                                        </a>
                                    </td>

                                    <!-- Schedule meeting -->
                                    <td align="center" width="33.33%" style="padding:4px;">
                                        <a href="https://event.whatsapp.com/rKNeqgAGapvyqnkYRXVybyBJbW1pZ3JhdGlvbiBDb25zdWx0aW5nIFNybA"
                                           target="_blank"
                                           class="action-button"
                                           style="display:block; background:#6C63FF;
                                                  color:#ffffff; text-decoration:none;
                                                  padding:9px 3px; border-radius:5px;
                                                  font-size:11px; font-weight:bold;
                                                  text-align:center; white-space:nowrap;">
                                            <img class="action-icon"
                                                 src="https://img.icons8.com/ios-filled/50/ffffff/calendar.png"
                                                 width="14" height="14" alt="Calendar"
                                                 style="vertical-align:middle; border:0;">
                                            &nbsp;Schedule Meeting
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Unsubscribe -->
                            <table role="presentation" width="100%" cellpadding="0"
                                   cellspacing="0" border="0"
                                   style="margin-top:25px; border-top:1px dashed #dddddd;">
                                <tr>
                                    <td align="center" style="padding-top:18px;">
                                        <p style="font-size:12px; color:#777777;
                                                  margin:0 0 10px;">
                                            No longer wish to receive our emails?
                                        </p>
                                        <a href="{{ $unsubscribeUrl ?? '#' }}"
                                           style="display:inline-block; background:#dc3545;
                                                  color:#ffffff; text-decoration:none;
                                                  padding:6px 14px; border-radius:4px;
                                                  font-size:11px; font-weight:bold;">
                                            Unsubscribe
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer -->
                            <table role="presentation" width="100%" cellpadding="0"
                                   cellspacing="0" border="0"
                                   style="margin-top:25px; border-top:1px solid #e0e0e0;">
                                <tr>
                                    <td align="center"
                                        style="padding-top:15px; font-size:11px;
                                               line-height:1.6; color:#888888;">
                                        <p style="margin:4px 0;">
                                            This email was sent from HireAsia Workforce -
                                            Agencija za Zapošljavanje Stranih Radnika.
                                        </p>
                                        <p style="margin:4px 0;">
                                            &copy; {{ date('Y') }} HireAsia Workforce.
                                            All rights reserved.
                                        </p>
                                        <p style="margin:4px 0;">
                                            <a href="https://www.hire-asia.work/"
                                               style="color:#1E4BA6; text-decoration:none;">
                                                www.hire-asia.work
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
                <!-- End main email container -->

            </td>
        </tr>
    </table>
</body>
</html>
