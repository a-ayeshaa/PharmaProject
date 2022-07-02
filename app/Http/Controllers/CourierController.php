<?php

namespace App\Http\Controllers;

use App\Models\courier;
use App\Models\order;
use Illuminate\Http\Request;

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
        $u_id=session()->get('logged.courier');
        $courier=courier::where('u_id',$u_id)->first();
        session()->put('name',$courier->courier_name);
        return view ('CourierView.AcceptedOrders')->with('name',$courier->courier_name);
    }
}
