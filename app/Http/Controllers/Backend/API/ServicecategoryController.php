<?php

namespace App\Http\Controllers\Backend\API;
use DB;
use Validator;
use Illuminate\Pagination\Paginator;
use App\Servicecategory;
use App\Postcode;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ServicecategoryRequest;
use App\Http\Resources\ServicecategoryCollection;
use App\Http\Resources\Servicecategory as ServicecategoryResource;
use App\Http\Controllers\Controller;

class ServicecategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(){
        $this->ServiceCategory = new \App\Servicecategory();
     }

    public function GetAllCategories(Request $request){
       return $this->get_all_servicecategory($request);
    }

    public function provider_sort(Request $request){      
          //pagination
                $paging['perpage']  = 0;
                $paging['callpage'] = 0;
                $paging['next']     = 0;
                $paging['previous'] = 0;
                $paging['pages']    = 0;
                $paging['result']   = 0;
                if (empty($request->page)) {
                   $request->page=1;
                }
                else{
                    $request->page;
                }
                $result_perpage            = 10;
                $current_page              = $request->page;
                $current_page_first_result = ($current_page - 1) * $result_perpage;
                

                $orderby=$request->get('orderby');
                $query='SELECT
                    users.first_name,
                    users.last_name,
                    provider_service_maps.amount,
                    TRUNCATE(SUM(user_reviews.rating)*5/(COUNT(user_reviews.user_review_by)*5), 1) AS rating,
                    comp.comp_total AS cleanings,
                    users.profilepic
                  FROM users
                    INNER JOIN role_user
                      ON (role_user.user_id = users.id)
                    INNER JOIN roles
                      ON (role_user.role_id = roles.id)              
                    INNER JOIN user_reviews
                        ON user_reviews.user_review_for = users.id
                    INNER JOIN provider_service_maps
                      ON provider_service_maps.provider_id = users.id
                    INNER JOIN bookings
                      ON bookings.user_id = users.id
                    INNER JOIN booking_status
                      ON bookings.booking_status_id = booking_status.id
                    INNER JOIN completed_bookings as comp
                      ON comp.user_id = users.id
                    INNER JOIN provider_metadata
                      ON provider_metadata.provider_id = users.id    
                  WHERE roles.id=2
                  AND provider_metadata.verify=1 
                  GROUP BY users.first_name,
                  users.last_name,
                  provider_service_maps.amount,
                  user_reviews.user_review_for,
                  users.profilepic,
                  comp.comp_total
                  ';
                    if($orderby=='rating'){

                          $query=$query.' ORDER BY rating DESC ';
                    }
                    if($orderby=='pricehightolow'){

                          $query=$query.' ORDER BY amount DESC  
                     ';
                    }
                    if($orderby=='pricelowtohigh'){

                          $query=$query.' ORDER BY amount ASC';
                    }
                    if($orderby=='new'){

                          $query=$query.' ORDER BY users.created_at DESC';
                    }
                    if($orderby=='highest_cleans'){

                          $query=$query.' ORDER BY cleanings DESC';
                    } 

                    $query=$query. ' LIMIT ' . $current_page_first_result . ',' . $result_perpage;
                    
                    $query = DB::select($query);

                    //Paginastion
                    $total_result = count($query);
                    $number_of_pages = ceil($total_result / $result_perpage);
                    $paging['perpage']  = $result_perpage;
                    $paging['callpage'] = $current_page;
                    $paging['result']   = $total_result;
                    $paging['next']     = $current_page < $number_of_pages ? $current_page + 1 : null;
                    $paging['previous'] = $current_page == 1 ? null : $current_page - 1;
                    $paging['pages']    = $number_of_pages;

      return response()->json(['data' => $query,'paging' => $paging], 200);
    }
    public function provider_filter(Request $request)
    {
        //pagination
                $paging['perpage']  = 0;
                $paging['callpage'] = 0;
                $paging['next']     = 0;
                $paging['previous'] = 0;
                $paging['pages']    = 0;
                $paging['result']   = 0;
                if (empty($request->page)) {
                   $request->page=1;
                }
                else{
                    $request->page;
                }
                $result_perpage            = 10;
                $current_page              = $request->page;
                $current_page_first_result = ($current_page - 1) * $result_perpage;

        $price=$request->get('price');
        $minimum_rating=$request->get('minimum_rating');
        $minimum_cleans=$request->get('minimum_cleans');

        $users = DB::select("SELECT
              users.first_name,
              users.last_name,
              provider_service_maps.amount,
              TRUNCATE(SUM(user_reviews.rating)*5/(COUNT(user_reviews.user_review_by)*5), 1) AS rating,
              comp.comp_total AS cleanings,
              users.profilepic
            FROM users
              INNER JOIN role_user
                ON (role_user.user_id = users.id)
              INNER JOIN roles
                ON (role_user.role_id = roles.id)
              INNER JOIN user_reviews
                ON user_reviews.user_review_for = users.id
              INNER JOIN provider_service_maps
                ON provider_service_maps.provider_id = users.id
              INNER JOIN bookings
                ON bookings.user_id = users.id
              INNER JOIN booking_status
                ON bookings.booking_status_id = booking_status.id
              INNER JOIN completed_bookings as comp
                ON comp.user_id = users.id  
              INNER JOIN provider_metadata
                ON provider_metadata.provider_id = users.id  
            WHERE roles.id=2
            AND provider_service_maps.type = 'billingrateperhour'
            AND provider_service_maps.amount >= '$price'
            AND bookings.booking_status_id = 4
            AND provider_metadata.verify=1 
            GROUP BY users.first_name,
                     users.last_name,
                     provider_service_maps.amount,
                     user_reviews.user_review_for,
                     users.profilepic,
                     comp.comp_total
            HAVING 
                    COUNT(bookings.user_id) >= '$minimum_cleans' AND
                    TRUNCATE(SUM(user_reviews.rating)*5/(COUNT(user_reviews.user_review_by)*5), 1) >= '$minimum_rating'

            LIMIT $current_page_first_result, $result_perpage
            ");

          //Paginastion
              $total_result = count($users);
              $number_of_pages = ceil($total_result / $result_perpage);
              $paging['perpage']  = $result_perpage;
              $paging['callpage'] = $current_page;
              $paging['result']   = $total_result;
              $paging['next']     = $current_page < $number_of_pages ? $current_page + 1 : null;
              $paging['previous'] = $current_page == 1 ? null : $current_page - 1;
              $paging['pages']    = $number_of_pages;

        return response()->json(['data' => $users,'paging' => $paging], 200);       
    }

//     public function provider_search(Request $request)
//     {
//         $paging['perpage'] = 0;
//         $paging['callpage'] = 0;
//         $paging['next'] = 0;
//         $paging['previous'] = 0;
//         $paging['pages'] = 0;
//         $paging['result'] = 0;

//         $catagories_id=$request->get('catagories_id');
//         $postcode=$request->get('postcode');
//         $working_day=$request->working_day;
//         $booking_date=$request->booking_date;
//         $start_time=$request->start_time;
//         $work_hours=$request->work_hours;
//         //time conversion
//         $plus_minute='00:00:01';//for time between condition true 
//         //start time plus
//         $start_time_result=date("H:i:s", strtotime($start_time));
//         $secs = strtotime($plus_minute)-strtotime("00:00:00");
//         $start_time_result_plus = date("H:i:s",strtotime($start_time_result)+$secs);
//         $work_hours_result=floor($work_hours) . ' hours ' . (($work_hours * 60) % 60).' minutes';
//         $end_time_result = date("H:i:s",strtotime($start_time_result.$work_hours_result));
//         //end_time_minus
//         $secs = strtotime($plus_minute)-strtotime("00:01:01");
//         $end_time_result_minus = date("H:i:s",strtotime($end_time_result)+$secs);
//         $validator = Validator::make($request->all(),[ 
//             'catagories_id' => 'required',
//             'postcode' => 'required|numeric',

//         ]);
        
//         if($validator->fails()){
//             $respond = 0;
//             $message = implode(",", $validator->messages()->all());
//             return response()->json(['respond' => $respond, 'paging' => $paging, 'message' => $message, 'result' => ''], 401);
//         }


//             //pagination
//               if (empty($request->page)) {
//                    $request->page=1;
//                 }
//                 else{
//                     $request->page;
//                 }
//                 $result_perpage            = 10;
//                 $current_page              = $request->page;
//                 $current_page_first_result = ($current_page - 1) * $result_perpage;



// // $users = DB::select("SELECT provider_id FROM bookings WHERE booking_time NOT BETWEEN '16:00:01' AND '17:00:00'
// // AND booking_end_time NOT BETWEEN '16:00:01' AND '17:00:00' 
// // AND booking_date='2020-05-26'
// // AND booking_status_id!=4");

// // $test=array();
// // foreach($users as $row)
// // {
// //     $sql=DB::select("SELECT provider_id FROM bookings WHERE
// //                         booking_end_time>'17:00:00'
// //                         AND booking_date='2020-05-26'
// //                         AND provider_id=$row->provider_id");
                        

// //                         if(!empty($sql))
// //                         {
                          
// //                           foreach ($sql as $row1){

// //                               $sql = DB::select("SELECT * FROM users WHERE id!=$row1->provider_id");
// //                                 $test[]=$sql;    
// //                             }
// //                             // $provider_id=$sql[0]->provider_id;
// //                         }                       

// // }
// // print_r($test);
// // exit;


//                         $users = DB::select("SELECT
//                               users.first_name,
//                               users.last_name,
//                               provider_service_maps.amount,
//                               TRUNCATE(SUM(user_reviews.rating)*5/(COUNT(user_reviews.user_review_by)*5), 1) AS rating,
//                               comp.comp_total AS cleanings,
//                               users.profilepic,
//                               postcodes.postcode,
//                               postcodes.suburb,
//                               service_categories.name,
//                               provider_working_hours.start_time,
//                               provider_working_hours.end_time
//                             FROM users 
//                               INNER JOIN role_user
//                                 ON role_user.user_id = users.id
//                               INNER JOIN roles
//                                 ON role_user.role_id = roles.id 
//                               INNER JOIN provider_working_hours
//                                 ON provider_working_hours.provider_id = users.id
//                               INNER JOIN provider_postcode_maps
//                                 ON provider_postcode_maps.provider_id = users.id
//                               INNER JOIN postcodes
//                                 ON postcodes.id = provider_postcode_maps.postcode_id
//                               INNER JOIN provider_service_maps
//                                 ON provider_service_maps.provider_id = users.id
//                               INNER JOIN user_reviews
//                                 ON user_reviews.user_review_for = users.id  
//                               INNER JOIN services
//                                 ON provider_service_maps.service_id = services.id
//                               INNER JOIN service_categories
//                                 ON services.category_id = service_categories.id
//                               INNER JOIN bookings
//                                 ON bookings.user_id = users.id
//                               INNER JOIN booking_services
//                                 ON booking_services.booking_id = bookings.id  
//                               INNER JOIN booking_status
//                                 ON bookings.booking_status_id = booking_status.id
//                               INNER JOIN completed_bookings as comp
//                                 ON comp.provider_id = users.id    
//                               INNER JOIN provider_metadata
//                                 ON provider_metadata.provider_id = users.id    
//                                 WHERE  roles.id=2
//                                   AND provider_metadata.verify=1  
//                                   AND postcodes.postcode LIKE '$postcode'
//                             AND service_categories.id = '$catagories_id'
//                             AND FIND_IN_SET('$working_day',provider_working_hours.working_days)
//                             AND provider_working_hours.start_time<='$start_time_result' AND provider_working_hours.end_time>='$end_time_result'
//                             AND bookings.booking_time NOT BETWEEN '$start_time_result_plus' AND '$end_time_result'
//                             AND bookings.booking_end_time NOT BETWEEN '$start_time_result_plus' AND '$end_time_result' 
//                             AND bookings.booking_date='$booking_date' 
//                             AND booking_status_id!=4
//                             GROUP BY users.first_name,
//                                   users.last_name,
//                                   provider_service_maps.amount,
//                                   user_reviews.user_review_for,
//                                   users.profilepic,
//                                   postcodes.postcode,
//                                   postcodes.suburb,
//                                   service_categories.name,
//                                   provider_working_hours.start_time,
//                                   provider_working_hours.end_time,
//                                   comp.comp_total  
//                             LIMIT $current_page_first_result, $result_perpage
//                             ");
//         //Paginastion
//               $total_result = count($users);
//               $number_of_pages = ceil($total_result / $result_perpage);
//               $paging['perpage']  = $result_perpage;
//               $paging['callpage'] = $current_page;
//               $paging['result']   = $total_result;
//               $paging['next']     = $current_page < $number_of_pages ? $current_page + 1 : null;
//               $paging['previous'] = $current_page == 1 ? null : $current_page - 1;
//               $paging['pages']    = $number_of_pages;
//         return response()->json(['data' => $users,'paging' => $paging], 200);
//     }

    public function provider_search(Request $request)
    {

        $paging['perpage'] = 0;
        $paging['callpage'] = 0;
        $paging['next'] = 0;
        $paging['previous'] = 0;
        $paging['pages'] = 0;
        $paging['result'] = 0;

        $catagories_id=$request->get('catagories_id');
        $postcode=$request->get('postcode');
        $is_flexible=$request->get('is_flexible');
        $working_day=$request->working_day;
        $booking_date=$request->booking_date;

        $start_time=$request->start_time;
        $work_hours=$request->work_hours;
        

        //time conversion
        $plus_minute='00:00:01';//for time between condition true 

        //start time plus
        $start_time_result=date("H:i:s", strtotime($start_time));
        $secs = strtotime($plus_minute)-strtotime("00:00:00");
        $start_time_result_plus = date("H:i:s",strtotime($start_time_result)+$secs);

        $work_hours_result=floor($work_hours) . ' hours ' . (($work_hours * 60) % 60).' minutes';
        $end_time_result = date("H:i:s",strtotime($start_time_result.$work_hours_result));
        //end_time_minus
        $secs = strtotime($plus_minute)-strtotime("00:01:01");
        $end_time_result_minus = date("H:i:s",strtotime($end_time_result)+$secs);

        $validator = Validator::make($request->all(),[ 
            'catagories_id' => 'required',
            'postcode' => 'required|numeric',

        ]);
        
        if($validator->fails()){
            $respond = 0;
            $message = implode(",", $validator->messages()->all());
            return response()->json(['respond' => $respond, 'paging' => $paging, 'message' => $message, 'result' => ''], 401);
        }


            //pagination
              if (empty($request->page)) {
                   $request->page=1;
                }
                else{
                    $request->page;
                }
                $result_perpage            = 10;
                $current_page              = $request->page;
                $current_page_first_result = ($current_page - 1) * $result_perpage;



// $users = DB::select("SELECT provider_id FROM bookings WHERE booking_time NOT BETWEEN '16:00:01' AND '17:00:00'
// AND booking_end_time NOT BETWEEN '16:00:01' AND '17:00:00' 
// AND booking_date='2020-05-26'
// AND booking_status_id!=4");

// $test=array();
// foreach($users as $row)
// {
//     $sql=DB::select("SELECT provider_id FROM bookings WHERE
//                         booking_end_time>'17:00:00'
//                         AND booking_date='2020-05-26'
//                         AND provider_id=$row->provider_id");
                        

//                         if(!empty($sql))
//                         {
                          
//                           foreach ($sql as $row1){

//                               $sql = DB::select("SELECT * FROM users WHERE id!=$row1->provider_id");
//                                 $test[]=$sql;    
//                             }
//                             // $provider_id=$sql[0]->provider_id;
//                         }                       

// }
// print_r($test);
// exit;


                        $users = DB::select("SELECT
                            users.id,
                             users.uuid,
                            users.first_name,
                            users.last_name,
                            provider_service_maps.amount,
                            TRUNCATE(SUM(user_reviews.rating)*5/(COUNT(user_reviews.user_review_by)*5), 1) AS rating,
                            comp.comp_total AS cleanings,
                            users.profilepic,
                            postcodes.postcode,
                            service_categories.name,
                            provider_working_hours.start_time,
                            provider_working_hours.end_time,
                            bookings.booking_date
                          FROM users
                            INNER JOIN provider_working_hours
                              ON provider_working_hours.provider_id = users.id
                            INNER JOIN provider_postcode_maps
                              ON provider_postcode_maps.provider_id = users.id
                            INNER JOIN postcodes
                              ON postcodes.id = provider_postcode_maps.postcode_id
                            INNER JOIN provider_service_maps
                              ON provider_service_maps.provider_id = users.id
                            INNER JOIN user_reviews
                              ON user_reviews.user_review_for = users.id
                            INNER JOIN services
                              ON provider_service_maps.service_id = services.id
                            INNER JOIN service_categories
                              ON services.category_id = service_categories.id
                            INNER JOIN bookings
                              ON bookings.user_id = users.id
                            INNER JOIN booking_services
                              ON booking_services.booking_id = bookings.id
                            INNER JOIN booking_status
                              ON bookings.booking_status_id = booking_status.id
                            INNER JOIN completed_bookings comp
                              ON comp.user_id = users.id
                            INNER JOIN provider_metadata
                              ON provider_metadata.provider_id = users.id
                            INNER JOIN role_user
                              ON role_user.user_id = users.id
                          WHERE provider_metadata.verify = 1
                           AND postcodes.postcode = '$postcode'
                          AND service_categories.id ='$catagories_id'
                          AND FIND_IN_SET('$working_day', provider_working_hours.working_days)
                          AND provider_working_hours.start_time <= '$start_time_result'
                          AND provider_working_hours.end_time >= '$end_time_result'
                          AND bookings.booking_date !='$booking_date'
                          AND role_user.role_id = 2
                          GROUP BY users.first_name,
                                   users.last_name,
                                   provider_service_maps.amount,
                                   user_reviews.user_review_for,
                                   users.profilepic,
                                   postcodes.postcode,
                                   service_categories.name,
                                   provider_working_hours.start_time,
                                   provider_working_hours.end_time,
                                   comp.comp_total,
                                   bookings.booking_date
                          
                            LIMIT $current_page_first_result, $result_perpage
                            ");

        //Paginastion
              $total_result = count($users);
              $number_of_pages = ceil($total_result / $result_perpage);
              $paging['perpage']  = $result_perpage;
              $paging['callpage'] = $current_page;
              $paging['result']   = $total_result;
              $paging['next']     = $current_page < $number_of_pages ? $current_page + 1 : null;
              $paging['previous'] = $current_page == 1 ? null : $current_page - 1;
              $paging['pages']    = $number_of_pages;

        return response()->json(['data' => $users,'paging' => $paging], 200);
    }
    public function get_all_servicecategory(Request $request)
    {
	  // $servicecategories = Servicecategory::query();
      $servicecategories = Servicecategory::select('id','uuid','name','description','created_at','updated_at','deleted_at')->where('active', 1)->orderby('position','ASC');

	  return $servicecategories->get()->toArray();
     // return (new ServicecategoryCollection($servicecategories));
    }

    public function postcode_search(Request $request)
    {

      $postcode = Postcode::query();
      if ($request->has('postcode')) {

            $postcode = postcode::select('postcode','suburb','state')->where('postcode', 'LIKE', '%'.$request->get('postcode').'%')->orwhere('suburb', 'LIKE', '%'.$request->get('postcode').'%');
        }

        $postcode = $postcode->paginate(10);
        return (new ServicecategoryCollection($postcode));
    }

    public function get(Request $request,$users_uuid,$servicecategorys_uuid)
    {
        $servicecategories = Servicecategory::query();
        
        if ($servicecategorys_uuid) {
            $servicecategories = $servicecategories->where('id',$servicecategorys_uuid)->orWhere('uuid',$servicecategorys_uuid);
        }

        if ($request->has('id')) {
            $servicecategories = $servicecategories->where('id', 'LIKE', '%'.$request->get('id').'%');
        }   
        
        if ($request->has('name')) {
            $servicecategories = $servicecategories->where('name', 'LIKE', '%'.$request->get('name').'%');
        }
        if ($request->has('description')) {
            $servicecategories = $servicecategories->where('description', 'LIKE', '%'.$request->get('description').'%');
        }
        if ($request->has('position')) {
            $servicecategories = $servicecategories->where('position', 'LIKE', '%'.$request->get('position').'%');
        }
        if ($request->has('active')) {
            $servicecategories = $servicecategories->where('active', 'LIKE', '%'.$request->get('active').'%');
        }
        $servicecategories = $servicecategories->paginate(20);
        return (new ServicecategoryCollection($servicecategories));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_servicecategory(ServicecategoryRequest $request,$users_uuid)
    {
        $servicecategory = Servicecategory::firstOrNew(['id' => $request->get('users_uuid')]);
        $servicecategory->name = $request->get('name');
        $servicecategory->description = $request->get('description');
        $servicecategory->position = $request->get('position');
        $servicecategory->active = $request->get('active');

        $servicecategory->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    public function edit_servicecategory(ServicecategoryRequest $request,$users_uuid,$servicecategorys_uuid)
    {

        $servicecategory = Servicecategory::firstOrNew(['uuid' => $servicecategorys_uuid]);
        $servicecategory->name = $request->get('name');
        $servicecategory->description = $request->get('description');
        $servicecategory->position = $request->get('position');
        $servicecategory->active = $request->get('active');

        $servicecategory->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $servicecategory], $responseCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_servicecategory(Request $request,$users_uuid,$servicecategorys_uuid)
    {
      
      $servicecategory =Servicecategory::where('uuid',$servicecategorys_uuid)->orWhere('id',$servicecategorys_uuid)->first();
        if ($servicecategory != null) {
            $servicecategory->delete();
            return response()->json(['no_content' => $servicecategory], 200);
        }
        else{
            return response()->json(['no_content' => 'Wrong ID!!']);
        }
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function restore(Request $request)
    // {
    //     $servicecategory = Servicecategory::withTrashed()->find($request->get('id'));
    //     $servicecategory->restore();
    //     return response()->json(['no_content' => true], 200);
    // }
}
