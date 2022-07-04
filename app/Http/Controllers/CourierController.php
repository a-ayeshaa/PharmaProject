<?php

namespace App\Http\Controllers;

use App\Mail\orderAccepted;
use App\Models\courier;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDO;
use Carbon\Carbon;


class CourierController extends Controller
{
    public function courierHome()
        {
            $u_id=session()->get('logged.courier');
            $courier=courier::where('u_id',$u_id)->first();
            session()->put('name',$courier->courier_name);
            return view('CourierView.home')->with('name',$courier->courier_name);
        }

    public function orderView(){
        $orders=order::all();
        return view ('CourierView.orders')->with('orders',$orders);
    }

    public function AcceptedOrderView(){
        $status="accepted";
        $AcceptedOrders=order::where('order_status',$status)->get();
        return view ('CourierView.AcceptedOrders')->with('AcceptedOrders',$AcceptedOrders);
    }

    public function acceptOrder($order_id){
        $dateNtime=Carbon::now();
        $modified = order::where('order_id',$order_id)
        ->update(
            [
                'order_status'=>'accepted',
                'accepted_time'=>$dateNtime
            ]
            );
        return redirect()->route('courier.mail',['order_id'=>$order_id]);
    }

    public function deliveredOrder($order_id){
        $dateNtime=Carbon::now();
        $modified = order::where('order_id',$order_id)
        ->update(
            [
                'order_status'=>'delivered',
                'delivery_time'=>$dateNtime
            ]
        );
        return redirect()->route('courier.AcceptedOrder');
    }

    public function sendMail($order_id){
        $order=order::where('order_id',$order_id)->first();
        Mail::to('tahmidislam73@gmail.com')->send(new orderAccepted($order));
        return redirect()->route('courier.order');
    }

    public function courierProfile(){
        $u_id=session()->get('logged.courier');
        $courier=courier::where('u_id',$u_id)->first();
        return view('CourierView.profileView')->with('courier',$courier);
    }

}
