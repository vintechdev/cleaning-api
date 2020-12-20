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
      <td class="grey" style="width: 72.7273%;"><span>Address</span></td>
      <td  class="grey" style="width: 27.135%;"><span>{{$data['address']['address_line1']}},{{$data['address']['address_line2']}}<br>
      {{$data['address']['subrub']}},{{$data['address']['state']}},{{$data['address']['postcode']}}</span></td>
   </tr>
    <tr>
      <td class="grey" style="width: 72.7273%;"><span>Frequency</span></td>
      <td  class="grey" style="width: 27.135%;"><span>{{$data['bookings']['plan_name']}}</span></td>
   </tr>
<tr style="background-color: #95e8c6;font-weight:bold">
<td style="width: 72.7273%;" colspan="2">&nbsp;Service Details</td>

</tr >
      <?php 
      //dd($data['services']);
      if(count($data['services'])>0){
          foreach($data['services'] as $key=>$val){ ?>
     
        <tr><td style="width: 72.7273%;" ><span>{{$val['service_name']}}</span></td>
        
        <td  style="width: 27.135%;"></td>
        </tr>
      <?php } }
      ?>
</tbody>
</table>
<!-- DivTable.com -->

<p>
  {{__('Thanks a lot for being with us.')}} <br/>
 
</p>
@include('email.footer_new')
