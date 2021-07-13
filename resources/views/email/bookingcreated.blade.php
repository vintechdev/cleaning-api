@include('email.header_new')

<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
    <tr>
        <td style="padding-left: 5px; padding-right: 5px;">
            <h3 style="font-size: 18px; font-weight: bold; color: #3d4852; margin-bottom: 12px; text-align: left;">
                {{__('Hello')}}, {{ ucwords($data['name']) }}
            </h3>
            <p style="font-size: 16px; color: #718096; text-align: left;">
                Your Service has been booked successfully.
                @if($data['booking']['booking_provider_type']=='freelancer')
                    You will be notified once provider will accept your service request.
                @else
                    Agency will contact you soon!!
                @endif
            </p>
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
            <b style="color: #3d4852;">Frequency:</b>&nbsp;
            <span style="color: #718096; font-weight: normal;">
                {{ucwords($data['plan'])}}
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <b style="color: #3d4852;">Date:</b>&nbsp;
            <span style="color: #718096; font-weight: normal;">
                {{date('d/M/Y',strtotime($data['booking']['booking_date']))}} {{date('H:i',strtotime($data['booking']['booking_time']))}}
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
    <tr style="background-color: #52b68d;">
        <td style="font-weight:bold; color:white; text-align: center;" colspan="2">{{ $data['service_category_name'] }}</td>
    </tr>
    <?php
    //dd($data['services']);
    if(count($data['services']) > 0){
    foreach($data['services'] as $key=>$val){ 
        $hourstr = $val['initial_number_of_hours'];
        
       /* if ($val['service_type'] === 'hourly'){
            $h = $val['initial_number_of_hours'];
            $converthours = ['hours' => floor($h), 'mins' => (floor($h * 60) % 60), 'secs' => floor($h * 3600) % 60];
            $hourstr = (($converthours['hours'] != 0) ? $converthours['hours'] . ' hours ' : '') . (($converthours['mins'] != 0) ? $converthours['mins'] . ' minutes ' : ' ') . (($converthours['secs'] != 0) ? $converthours['secs'] . ' seconds' : '');
        }  */


    ?>
    <tr>
        <td style="width: 70%; border: 1px solid #ddd;"><span>{{ucwords($val['service_name'])}}</span>
            <span style="background-color: #e8f4e6; border-radius: 6px; color: #1d8f0e; padding: 3px 5px; display: inline-block; font-size: 12px;">{{'('.$hourstr.')'}}</span>
        </td>        
        <td style="width: 30%; border: 1px solid #ddd;"><span>{{Config::get('const.currency').$val['initial_service_cost']}}</span></td>
    </tr>
    <?php } }
    if($data['booking']['discount'] == null){
    ?>
    <tr>
        <td style="border: 1px solid #ddd; color: #3d4852;"><span><b>Total</b></span></td>
        <td style="border: 1px solid #ddd; color: #3d4852;">
            <span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span>
        </td>
    </tr>
    <?php }else{ ?>
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
        <td style="border: 1px solid #ddd; color: #3d4852;"><b>Final Total</b></td>
        <td style="border: 1px solid #ddd; color: #3d4852;"><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></td>
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
