@include('email.header_new')

<table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#fff" style="font-size: 16px; background-color: #fff;">
    <tr>
        <td>
            <h3 style="text-align: left;">{{__('Hello')}}, {{ ucwords($data['name']) }}</h3>
            <p style="font-size: 15px;text-align: left;">
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

<table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#f5f5f5" style="font-size: 16px; background-color: #f5f5f5;">
    <tr>
        <td>
            <b>Frequency:</b>&nbsp;
            <span style="color: #999; font-weight: normal;">{{ucwords($data['plan'])}}</span>
        </td>
    </tr>
    <tr>
        <td>
            <b>Date</b>&nbsp;
            <span style="color: #999; font-weight: normal;">{{date('d/M/Y',strtotime($data['booking']['booking_date']))}} {{date('H:i',strtotime($data['booking']['booking_time']))}}</span>
        </td>
    </tr>
</table>

<table cellpadding="5" cellspacing="0" style="width: 100%;border: 1px solid #000; font-size:16px" border="1">
    <tbody>
    <tr style="background-color: #52b68d;">
        <td style="font-weight:bold; color:white; text-align: center;" colspan="2">Service Details</td>
    </tr>
    <?php
    //dd($data['services']);
    if(count($data['services']) > 0){
    foreach($data['services'] as $key=>$val){ ?>
    <tr>
        <td style="width: 70%;"><span>{{ucwords($val['service_name'])}}</span></td>
        <td style="width: 30%;"><span>{{Config::get('const.currency').$val['initial_service_cost']}}</span></td>
    </tr>
    <?php } }
    if($data['booking']['discount'] == null){
    ?>
    <tr>
        <td style="width: 70%;"><span><b>Total</b></span></td>
        <td style="width: 30%;">
            <span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span>
        </td>
    </tr>
    <?php }else{ ?>
    <tr>
        <td style="width: 70%;"><span>Total</span></td>
        <td style="width: 30%;"><span>{{Config::get('const.currency').$data['booking']['total_cost']}}</span></td>
    </tr>
    <tr>
        <td><span>Discount({{$data['booking']['promocode']}})</span></td>
        <td><span>{{Config::get('const.currency').$data['booking']['discount']}}</span></td>
    </tr>
    <tr>
        <td><span><b>Final Total</b></span></td>
        <td><span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<!-- DivTable.com -->

<p>
    {{__('Thanks a lot for being with us.')}} <br/>
</p>
@include('email.footer_new')
