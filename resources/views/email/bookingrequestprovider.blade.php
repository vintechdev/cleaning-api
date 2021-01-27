@include('email.header_new')
<style>
  span { padding-left:10px;}
  table td{border: 1px solid #000;padding: 10px;}
  .grey{background-color:#00000008;}
</style>

<?php //dd($data['bookings']);;exit;?>
  

<h3 style="text-align: left;">{{__('Hello')}}, {{ ucwords($data['providers_name']) }}</h3>
<p style="font-size: 15px;text-align: left;">
  {{__('New Service Request has been Received. Please find below the Service Request Details.')}}
</p>

<table cellpadding="5" cellspacing ="0" style="width: 100%;border: 1px solid #000;font-size:16px" border="1">
<tbody>
<tr>
<?php //dd($data['userdetails']);?>
      <td class="grey" style="width:60%;"><span>Customer</span></td>
      <td  class="grey" style="width: 40%;"><span>{{ucwords($data['userdetails']['first_name'].' '.$data['userdetails']['last_name'])}}<br>
      {{$data['userdetails']['email']}}</span></td>
   </tr>
   <tr>
      <td class="grey" style="width: 60%;"><span>Date</span></td>
      <td  class="grey" style="width: 40%;"><span>{{date('d/M/Y',strtotime($data['bookings']['booking_date']))}} {{date('H:i',strtotime($data['bookings']['booking_time']))}}</span></td>
   </tr>
    <tr>
      <td class="grey" style="width: 60%;"><span>Address</span></td>
      <td  class="grey" style="width: 40%;"><span>{{ucwords($data['address']['address_line1'])}}, {{ucwords($data['address']['address_line2'])}}<br>
      {{ucwords($data['address']['suburb'])}}, {{ucwords($data['address']['state'])}}, {{$data['address']['postcode']}}</span></td>
   </tr>
    <tr>
      <td class="grey" style="width: 60%;"><span>Frequency</span></td>
      <td  class="grey" style="width: 40%;"><span>{{$data['bookings']['plan_name']}}</span></td>
   </tr>
<tr style="background-color: #3f9672;font-weight:bold">
<td style="width: 72.7273%;color:white" colspan="2">&nbsp;Service Details</td>

</tr >
      <?php 
    //  dd($data['services']);
      if(count($data['services'])>0){
          foreach($data['services'] as $key=>$val){
              $h = $val['initial_number_of_hours'];
              $converthours = ['hours'=>floor($h),'mins'=> (floor($h * 60) % 60),'secs'=> floor($h * 3600) % 60];
              $hourstr = (($converthours['hours']!=0)?$converthours['hours'].' hours ':'').(($converthours['mins']!=0)?$converthours['mins'].' minutes ':' ').(($converthours['secs']!=0)?$converthours['secs'].' seconds':'');
             
            ?>
     
        <tr><td style="width: 60%;" ><span>{{$val['service_name']}}{{'('.$hourstr.')'}}</span></td>
        
        <td  style="width:40%;">{{Config::get('const.currency').$val['initial_service_cost']}}</td>
        </tr>
      <?php } }
      
       if($data['bookings']['discount']==null){
      ?>

<tr class="grey">
<td style="width: 60%;"><span><b>Total</span></b></td>
<td style="width: 40%;"><span><b>{{Config::get('const.currency').$data['bookings']['final_cost']}}</b></span></td>
</tr>
<?php }else{ ?>
  <tr class="grey">
      <td style="width: 60%;"><span>Total</span></td>
      <td style="width: 40%;"><span>{{Config::get('const.currency').$data['bookings']['total_cost']}}</span></td>
  </tr>
  <tr class="grey">
      <td style="width: 60%;"><span>Discount({{$data['booking']['promocode']}})</span></td>
      <td style="width: 40%;"><span>{{Config::get('const.currency').$data['bookings']['discount']}}</span></td>
  </tr>
  <tr class="grey">
      <td style="width: 60%;"><span><b>Final Total</b></span></td>
      <td style="width: 40%;"><span><b>{{Config::get('const.currency').$data['bookings']['final_cost']}}</b></span></td>
  </tr>
  <?php } ?>
</tbody>
</table>
<!-- DivTable.com -->

<p>
  {{__('Thanks a lot for being with us.')}} <br/>
 
</p>
@include('email.footer_new')
