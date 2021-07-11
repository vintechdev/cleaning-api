                <!-- Start email footer area -->
                <table border="0" cellpadding="0" cellspacing="0"
                       style="width:100%;">
                    <tr>
                        <td style="padding: 10px 5px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 14px;">
                            <p style="margin:0;padding:0;">
                                If you have any questions at all
                                <a href="https://help.cleaning.com.au/" target="_blank" style="color: #52b68d;">we can help</a>.
                            </p>
                            <p style="margin:0;padding:0;">
                                Cheers,<br/>The Cleaning Team
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>
                </table>

                </td>
            </tr>
        </table>

    </td>
</tr>
<tr>
    <td align="center" style="text-align: center; padding-top: 10px;">
        <table cellpadding="5" cellspacing="0" border="0" width="100%" style="width: 100%; max-width: 570px;" align="center">
            <tr>
                <td align="center" style="text-align: center; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
                    <p style="text-align: center; margin-bottom: 5px;">Download our mobile app:</p>
                    <a href="{{ env('APP_STORE_LINK') }}" style="display: inline-block; vertical-align: middle; margin-right: 4px;" target="_blank">
                        <img src="{{asset('/images/email/google-play-store-logo.png')}}" alt="Cleaning - Android App" height="48">
                    </a>
                    <a href="{{ env('PLAY_STORE_LINK') }}" style="display: inline-block; vertical-align: middle; margin-top: 4px;" target="_blank">
                        <img src="{{asset('/images/email/apple-app-store-logo.png')}}" alt="Cleaning - IOS App" height="48">
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="padding-top: 15px; padding-bottom: 15px; font-size: 12px; line-height: 18px; color: #b0adc5; text-align: center;">
        Copyright &copy;<?php echo date('Y');?> {{env('APP_NAME')}}. All Rights Reserved.
    </td>
</tr>
</table>

</body>
</html>
