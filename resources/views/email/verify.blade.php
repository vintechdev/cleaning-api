@include('email.header_new')
<h3>{{__('Hello')}}, {{ $data->name }}</h3>
<p>
  {{__('We need to verify your email address. In order to verify your account please click on the following link or paste the link on address bar of your browser and hit -')}}
</p>
<p>
  <a style="text-decoration: none;background: #4A9A4D;color: #fff;padding: 5px 10px;border-radius: 3px;" href="<?php  echo route('verifyEmail',['id'=>$data->id,'v_code'=>$key]); ?>">{{__('Verify')}}</a>
</p>
<p>
  {{__('Thanks a lot for being with us.')}} <br/>
  {{allSetting()['app_title']}}
</p>
@include('email.footer_new')
