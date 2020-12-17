@include('email.header_new')
<style>
  span { padding-left:10px;}
  table td{border: 1px solid #000;padding: 10px;}
  .grey{background-color:#00000008;}
</style>
  

<h3 style="text-align: left;">{{__('Hello')}}, {{ ucwords($data['name']) }}</h3>
<p style="font-size: 15px;text-align: left;">
  {{__('Your Service has been booked successfully. You will be notified once provider will accept your service request.')}}
</p>

<table cellpadding="5" cellspacing ="0" style="width: 100%;border: 1px solid #000;font-size:16px" border="1">
<tbody>
    <tr>
      <td class="grey" style="width: 72.7273%;"><span>Frequency</span></td>
      <td  class="grey" style="width: 27.135%;"><span>{{$data['plan']}}</span></td>
   </tr>
<tr style="background-color: #95e8c6;font-weight:bold">
<td style="width: 72.7273%;" colspan="2">&nbsp;Service Details</td>

</tr >
      <?php 
      //dd($data['services']);
      if(count($data['services'])>0){
          foreach($data['services'] as $key=>$val){ ?>
     
        <tr><td style="width: 72.7273%;" ><span>{{$val['service_name']}}</span></td>
        
        <td  style="width: 27.135%;"><span>{{Config::get('const.currency').$val['initial_service_cost']}}</span></td>
        </tr>
      <?php } }
      if($data['booking']['discount']==null){
      ?>

<tr class="grey">
<td style="width: 72.7273%;"><span><b>Total</span></b></td>
<td style="width: 27.135%;"><span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span></td>
</tr>
<?php }else{ ?>
  <tr class="grey">
      <td style="width: 72.7273%;"><span>Total</span></td>
      <td style="width: 27.135%;"><span>{{Config::get('const.currency').$data['booking']['total_cost']}}</span></td>
  </tr>
  <tr class="grey">
      <td style="width: 72.7273%;"><span>Discount({{$data['booking']['promocode']}})</span></td>
      <td style="width: 27.135%;"><span>{{Config::get('const.currency').$data['booking']['discount']}}</span></td>
  </tr>
  <tr class="grey">
      <td style="width: 72.7273%;"><span><b>Final Total</b></span></td>
      <td style="width: 27.135%;"><span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span></td>
  </tr>
  <?php } ?>
</tbody>
</table>
<!-- DivTable.com -->

<p>
  {{__('Thanks a lot for being with us.')}} <br/>
 
</p>
@include('email.footer_new')
