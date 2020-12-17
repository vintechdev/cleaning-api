@include('email.header_new')

<h3>{{__('Hello')}}, {{ __('Admin') }} </h3>
<p>
    {{ isset($subject) ? $subject : '' }}
</p>
<p>
    {{ isset($description) ? $description : '' }}
</p>
<p>
  {{__('Thanks a lot for being with us.')}} <br/>
  {{allsetting()['app_title']}}
</p>
@include('email.footer_new')

