<?php


namespace App\Http\Controllers;

use App\Mail\orderAccepted;
use App\Models\courier;
use App\Models\customer;
use App\Models\order;
use App\Models\users;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDO;
use App\Models\account;
use Carbon\Carbon;


class ApiCourierController extends Controller
{
    public function getID($token)
    {
        $u_id=Token::where('token',$token)->first();
        $info=users::where('u_id',$u_id->u_id)->first();
        if ($info->u_type=='COURIER')
        {
            $courier_id=courier::where('courier_id',$info->u_id)->first();
            // return "hello";
            return $courier_id->courier_id;
        }
    }

    public function getUID($token)
    {
        $u_id=Token::where('token',$token)->first();
        return $u_id->u_id;
    }

    public function orderView(){
        $data = order::all();
        return response()->json($data);
    }


    //accepted orders view
    public function AcceptedOrderView(){
        $status="accepted";
        $AcceptedOrders=order::where('order_status',$status)->get();
        return response()->json($AcceptedOrders);
    }

    //accepting orders
    public function acceptOrder($order_id){
        $dateNtime=Carbon::now();
        $modified = order::where('order_id',$order_id)
        ->update(
            [
                'order_status'=>'accepted',
                'accepted_time'=>$dateNtime
            ]
            );
        return response()->json("done");
    }

    //delivered orders
    public function deliveredOrder($req,$order_id){
        $dateNtime=Carbon::now();
        $modified = order::where('order_id',$order_id)
        ->update(
            [
                'order_status'=>'delivered',
                'delivery_time'=>$dateNtime,
            ]
        );

        
        $date=Carbon::today()->toDateString();
        $new= order::where('order_id',$order_id)->first();
        $rev=account::where('date',$date)->first();
        if($rev)
        {
            $temp=$rev->revenue+$new->totalbill;
            account::where('date',$date)
            ->update(['revenue'=> $temp]);
        }
        else
        {
            $item= new account();
            
            $item->date= $date;
            $item->revenue= $new->totalbill;
            $item->save();
        }

        //$order=order::where('order_id',$order_id);
        $u_id=$this->getUID($req->header("Authorization"));
        $courier=courier::where('u_id',$u_id)->first();
        $modified1=courier::where('u_id',$u_id)
        // ->increment('due_delivery_fee',15);
        ->update(
            [
                'due_delivery_fee'=>$courier->due_delivery_fee+15
            ]
            );
        return response()->json("done");
    }
}
