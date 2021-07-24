@include('email.header_new')
  
<?php //dd($data);exit;?>

<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
    <tr>
        <td style="padding-left: 5px; padding-right: 5px;">
            <h3 style="font-size: 18px; font-weight: bold; color: #3d4852; margin-bottom: 12px; text-align: left;">
                {{__('Hello')}}, {{ucwords($data['providers_name']) }}
            </h3>
            <p style="font-size: 16px; color: #718096; text-align: left;">
                {{$data['text']}}
            </p>
        </td>
    </tr>
</table>

<h3 style="font-size: 16px; font-weight: bold; color: #3d4852; margin-bottom: 0; text-align: center; background: #f5f5f5; padding: 7px 10px;">
    User Details
</h3>

<table cellpadding="10" cellspacing="0" border="0" width="100%" style="font-size: 16px;">
    <tr>
        <td style="border-bottom: 1px solid #ddd;">
            <b style="color: #3d4852;">Customer:</b>
            <span style="color: #718096; font-weight: normal;">
                {{ucwords($data['userdetails']['first_name'].' '.$data['userdetails']['last_name'])}}
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #ddd;">
            <b style="color: #3d4852;">Email:</b>
            <span style="color: #718096; font-weight: normal;">
                {{$data['userdetails']['email']}}
            </span>
        </td>
    </tr>

    <tr>
        <td style="border-bottom: 1px solid #ddd;">
            <b style="color: #3d4852;">Address:</b>
            <span style="color: #718096; font-weight: normal;">
                {{ucwords($data['address']['address_line1'])}}, {{ucwords($data['address']['address_line2'])}},
                {{ucwords($data['address']['suburb'])}}, {{ucwords($data['address']['state'])}}, {{$data['address']['postcode']}}
            </span>
        </td>
    </tr>
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
                {{date('d/M/Y',strtotime($data['bookings']['booking_date']))}} {{date('H:i',strtotime($data['bookings']['booking_time']))}}
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #ddd;">
            <b style="color: #3d4852;">Frequency:</b>
            <span style="color: #718096; font-weight: normal;">
                {{ucwords($data['bookings']['plan_name'])}}
            </span>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 10px; padding-bottom: 10px; text-align: center;">
            <span class="status-big status-{{$data['status']}}" style="padding: 10px 20px;">
                {{ ucwords($data['status']) }}
            </span>
        </td>
    </tr>
    <tr>
        <td style="height: 15px;"></td>
    </tr>
</table>


<table cellpadding="5" cellspacing ="0" border="1"
       style="width: 100%; border: 1px solid #ddd; font-size:16px; border-collapse: collapse;">
<tbody>
<tr>
    <td style="background-color: #52b68d; font-weight:bold; color:white; text-align: center;" colspan="2">{{ $data['service_category_name'] }}</td>
</tr>
      <?php 
    //  dd($data['services']);
      if(count($data['services'])>0){
          foreach($data['services'] as $key=>$val){
            if($data['bookings']['booking_status_id']==4){
              $hour = $val['final_number_of_hours'];
              $price = $val['final_service_cost'];
          }else{
              $hour = $val['initial_number_of_hours'];
              $price = $val['initial_service_cost'];
          } 
            ?>

        <tr>
            <td style="width: 70%; border: 1px solid #ddd;"><span>{{$val['service_name']}}{{'('.$hour.')'}}</span></td>
            <td  style="width:30%; border: 1px solid #ddd;">{{Config::get('const.currency').$price}}</td>
        </tr>
      <?php } }
      
       if($data['bookings']['discount']==null){
      ?>

        <tr>
            <td style="border: 1px solid #ddd; color: #3d4852;"><span><b>Total</b></span></td>
            <td style="border: 1px solid #ddd; color: #3d4852;">
                <span><b>{{Config::get('const.currency').$data['bookings']['final_cost']}}</b></span>
            </td>
        </tr>

        <?php }else{ ?>

    <tr>
        <td style="border: 1px solid #ddd;"><span>Total</span></td>
        <td style="border: 1px solid #ddd;"><span>{{Config::get('const.currency').$data['bookings']['total_cost']}}</span></td>
    </tr>
    <tr>
        <td style="border: 1px solid #ddd;">
              <span>Discount
                  <span style="background-color: #e7f3ef; border-radius: 6px; color: #52b68d; padding: 3px 5px; display: inline-block; font-size: 12px;">
                    {{$data['bookings']['promocode']}}
                  </span>
              </span>
        </td>
        <td style="border: 1px solid #ddd;"><span>{{Config::get('const.currency').$data['bookings']['discount']}}</span></td>
    </tr>
    <tr>
        <td style="border: 1px solid #ddd; color: #3d4852;"><span><b>Final Total</b></span></td>
        <td style="border: 1px solid #ddd; color: #3d4852;"><span><b>{{Config::get('const.currency').$data['bookings']['final_cost']}}</b></span></td>
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
