@include('email.header_new')

<?php //dd($data);exit;?>

<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
    <tr>
        <td style="padding-left: 5px; padding-right: 5px;">
            <h3 style="font-size: 18px; font-weight: bold; color: #3d4852; margin-bottom: 12px; text-align: left;">
                {{__('Hello')}}, {{ucwords($data['name']) }}
            </h3>
            <p style="font-size: 16px; color: #718096; text-align: left;">
                {{$data['text']}}
            </p>
        </td>
    </tr>
</table>

<h3 style="font-size: 16px; font-weight: bold; color: #3d4852; margin-bottom: 0; text-align: center; background: #f5f5f5; padding: 7px 10px;">
    Provider Details
</h3>
<table cellpadding="10" cellspacing="0" border="0" width="100%" style="font-size: 16px;">
    <tr>
        <td style="border-bottom: 1px solid #ddd;">
            <b style="color: #3d4852;">{{ucwords($data['provider_name'])}}</b><br/>
            <?php if($data['avgrate']>0){ ?>
            <span><i class="fas fa-star mr-2"></i>{{$data['avgrate']}}</span>
            <?php } ?>
        </td>
    </tr>
    <?php if(count($data['badge'])>0) { ?>
        <tr>
            <td style="text-align: center;">
                <span style="font-weight: bold; color: #3d4852;">Badges</span>
                <ul style="list-style: none; text-align: center; padding: 0; margin: 0;">
                    <?php foreach($data['badge'] as $k=>$v) { ?>
                    {{--{{ $image = display_base64(config('const.UPLOAD_PATH').'badge_icons/'.$v['badge_icon']) }}--}}
                    <li style="display: block;">
                        {{--<img src="{{ $image }}" alt="{{$v['badge_label']}}" title="{{$v['badge_label']}}" width="80"
                             style="max-width: 100%; display: inline-block; vertical-align: middle; margin-right: 10px;"
                        >--}}
                        <span style="display: inline-block; vertical-align: middle;">{{$v['badge_label']}}</span>
                    </li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td style="height: 15px;"></td>
    </tr>
</table>

<h3 style="font-size: 16px; font-weight: bold; color: #3d4852; margin-bottom: 0; text-align: center; background: #f5f5f5; padding: 7px 10px;">
    Booking Details
</h3>
<table cellpadding="10" cellspacing="0" border="0" width="100%" style="font-size: 16px;">
    <tr>
        <td style="border-bottom: 1px solid #ddd;">
            <b style="color: #3d4852;">Date:</b>
            <span style="color: #718096; font-weight: normal;">
                {{date('d/M/Y',strtotime($data['booking']['booking_date']))}} {{date('H:i',strtotime($data['booking']['booking_time']))}}
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <b style="color: #3d4852;">Frequency:</b>
            <span style="color: #718096; font-weight: normal;">
                {{ucwords($data['booking']['plan_name'])}}
            </span>
        </td>
    </tr>
    <tr>
        <td style="height: 15px;"></td>
    </tr>
</table>

<table cellpadding="5" cellspacing="0" border="1"
       style="width: 100%; border: 1px solid #ddd; font-size:16px; border-collapse: collapse;">
<tbody>
    <tr>
        <td style="background-color: #3f9672; font-weight:bold; color:white" colspan="2">Service Details</td>
    </tr>

      <?php 
    //  dd($data['services']);
      if(count($data['services'])>0){
          foreach($data['services'] as $key=>$val){
            if($data['booking']['booking_status_id']==4){
                $hour = $val['final_number_of_hours'];
                $price = $val['final_service_cost'];
            }else{
                $hour = $val['initial_number_of_hours'];
                $price = $val['initial_service_cost'];
            }
            ?>
        <tr>
            <td style="width: 70%; border: 1px solid #ddd;"><span>{{$val['service_name']}}{{'('.$hour.'h)'}}</span></td>
            <td  style="width:30%; border: 1px solid #ddd;">{{Config::get('const.currency').$price}}</td>
        </tr>
      <?php } } ?>
      
       <?php if($data['booking']['discount']==null){ ?>

        <tr>
            <td style="border: 1px solid #ddd; color: #3d4852;"><span><b>Total</b></span></td>
            <td style="border: 1px solid #ddd; color: #3d4852;">
                <span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span>
            </td>
        </tr>

    <?php } else { ?>

  <tr>
      <td style="border: 1px solid #ddd;"><span>Total</span></td>
      <td style="border: 1px solid #ddd;"><span>{{Config::get('const.currency').$data['booking']['total_cost']}}</span></td>
  </tr>
  <tr>
      <td style="border: 1px solid #ddd;">
          <span>Discount
              <span style="background-color: #e7f3ef; border-radius: 6px; color: #52b68d; padding: 3px 5px; display: inline-block; font-size: 12px;">
                {{$data['booking']['promocode']}}
              </span>
          </span>
      </td>
      <td style="border: 1px solid #ddd;"><span>{{Config::get('const.currency').$data['booking']['discount']}}</span></td>
  </tr>
  <tr>
      <td style="border: 1px solid #ddd; color: #3d4852;"><span><b>Final Total</b></span></td>
      <td style="border: 1px solid #ddd; color: #3d4852;"><span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span></td>
  </tr>
  <?php } ?>
</tbody>
</table>
<!-- DivTable.com -->

<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
    <tr>
        <td style="height: 15px;"></td>
    </tr>
    <tr>
        <td style="padding-left: 5px; padding-right: 5px;">
            <p>
                {{__('Thanks a lot for being with us.')}}
            </p>
        </td>
    </tr>
</table>

@include('email.footer_new')
