<tr>
    <td align="center" style="text-align: center; padding-top: 10px;">
        <table cellpadding="5" cellspacing="0" border="0" width="100%" style="width: 100%; max-width: 570px;" align="center">
            <tr>
                <td align="center" style="text-align: center; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
                    <p style="text-align: center; margin-bottom: 5px;">Download our mobile app:</p>
                    <a href="{{ Config::get('const.ANDROID_APP_URL') }}" style="display: inline-block; vertical-align: middle; margin-right: 4px;" target="_blank">
                        <img src="{{asset('/images/email/google-play-store-logo.png')}}" alt="Cleaning - Android App" height="48">
                    </a>
                    <a href="{{ Config::get('const.IOS_APP_URL') }}" style="display: inline-block; vertical-align: middle; margin-top: 4px;" target="_blank">
                        <img src="{{asset('/images/email/apple-app-store-logo.png')}}" alt="Cleaning - IOS App" height="48">
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td class="copyright">
        {{ Illuminate\Mail\Markdown::parse($slot) }}
    </td>
</tr>
