@include('email.template.header')
<body>
{!! $email_message !!}
<br/>
@if(isset($email_footer))
    {!! $email_footer !!}
@else
    <table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
        <tr>
            <td style="padding-left: 5px; padding-right: 5px;">
                <p>
                    {{__('Thanks a lot for being with us.')}} <br />
                    {{allSetting()['app_title']}}
                </p>
            </td>
        </tr>
    </table>
@endif
@include('email.template.footer')
</body>
</html>
