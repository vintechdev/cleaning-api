@include('email.template.header')
<body>
{!! $email_message !!}
<br/>
@if(isset($email_footer))
    {!! $email_footer !!}
@else
    <p>
        {{__('Thanks a lot for being with us.')}} <br />
        {{allSetting()['app_title']}}
    </p>
@endif
@include('email.template.footer')
</body>
</html>