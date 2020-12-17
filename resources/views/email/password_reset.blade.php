@include('email.header_new')
<h3>{{__('Hello')}}, {{ $name }}</h3>

<p>
    You are receiving this email because we received a password reset request for your account.
    Please Use bellow code to Reset Password. {{$token}}
    If you did not request a password reset, no further action is required.
</p>
<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{allSetting()['app_title']}}
</p>

@include('email.footer_new')
