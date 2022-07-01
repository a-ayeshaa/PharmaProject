<?php

namespace App\Http\Controllers;

use App\Mail\orderAccepted;
use App\Models\courier;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDO;

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
        $modified = order::where('order_id',$order_id)
        ->update(
            [
                'order_status'=>'accepted'
            ]
        );
        return redirect()->route('courier.order');
    }

    public function deliveredOrder($order_id){
        $modified = order::where('order_id',$order_id)
        ->update(
            [
                'order_status'=>'deliverd'
            ]
        );
        return redirect()->route('courier.AcceptedOrder');
    }

    public function sendMail(){
        Mail::to('fake@mail.com')->send(new orderAccepted("Tahmid"));
    }

}
