@include('email.header_new')
<style>
  .grey{ background-color: #00000008; }
</style>


  
<?php //dd($data);exit;?>
<h3 style="text-align: left;">{{__('Hello')}}, {{ucwords($data['name']) }}</h3>
<p style="font-size: 15px;text-align: left;">
 {{$data['text']}}
</p>
<table cellpadding="5" cellspacing="0" border="1"
       style="width: 100%; border: 1px solid #ddd; font-size:16px">
<tbody>
    <tr>
        <td style="background-color: #3f9672; font-weight:bold; color:white" colspan="2">Provider Details</td>
    </tr>

   <tr>
      <td class="grey" style="width: 60%;"><span>Name</span></td>
      <td  class="grey" style="width: 40%;"><span>{{ucwords($data['provider_name'])}}</span></td>
   </tr>

   @if($data['avgrate']>0)
   <tr>
      <td class="grey" style="width: 60%;"><span>Rating</span></td>
      <td  class="grey" style="width: 40%;"><span><i class="fas fa-star mr-2"></i>{{$data['avgrate']}}</span></td>
   </tr>
   @endif

   @if(count($data['badge'])>0)
    <tr>
        <td class="grey" style="width: 60%;"><span>Badges</span></td>
        <td class="grey" style="width: 40%;">
            <ul>
                @foreach($data['badge'] as $k=>$v)
                    <li><span>{{$v['badge_label']}}</span></li>
                @endforeach
            </ul>
        </td>
    </tr>
   @endif

    <tr>
        <td height="20"></td>
    </tr>

    <tr>
        <td style="background-color: #3f9672; font-weight:bold; color:white" colspan="2">Booking Details</td>
    </tr>

   <tr>
      <td class="grey" style="width: 60%;"><span>Date</span></td>
      <td  class="grey" style="width: 40%;"><span>{{date('d/M/Y',strtotime($data['booking']['booking_date']))}} {{date('H:i',strtotime($data['booking']['booking_time']))}}</span></td>
   </tr>
    
    <tr>
      <td class="grey" style="width: 60%;"><span>Frequency</span></td>
      <td  class="grey" style="width: 40%;"><span>{{ucwords($data['booking']['plan_name'])}}</span></td>
   </tr>

    <tr>
        <td height="20"></td>
    </tr>

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
            <td style="width: 60%;" ><span>{{$val['service_name']}}{{'('.$hour.'h)'}}</span></td>
            <td  style="width:40%;">{{Config::get('const.currency').$price}}</td>
        </tr>
      <?php } }
      
       if($data['booking']['discount']==null){ ?>

    <tr class="grey">
        <td style="width: 60%;"><span><b>Total</span></b></td>
        <td style="width: 40%;"><span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span>
        </td>
    </tr>

<?php }else{ ?>
  <tr class="grey">
      <td style="width: 60%;"><span>Total</span></td>
      <td style="width: 40%;"><span>{{Config::get('const.currency').$data['booking']['total_cost']}}</span></td>
  </tr>
  <tr class="grey">
      <td style="width: 60%;"><span>Discount({{$data['booking']['promocode']}})</span></td>
      <td style="width: 40%;"><span>{{Config::get('const.currency').$data['booking']['discount']}}</span></td>
  </tr>
  <tr class="grey">
      <td style="width: 60%;"><span><b>Final Total</b></span></td>
      <td style="width: 40%;"><span><b>{{Config::get('const.currency').$data['booking']['final_cost']}}</b></span></td>
  </tr>
  <?php } ?>
</tbody>
</table>
<!-- DivTable.com -->

<p>
  {{__('Thanks a lot for being with us.')}} <br/>
 
</p>

@include('email.footer_new')
