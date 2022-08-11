<?php

namespace App\Http\Controllers;

use App\Models\contract;
use Illuminate\Http\Request;
use App\Models\supply;
use App\Models\medicine;
use App\Models\users;
use App\Models\order;
use App\Models\supply_cart;

class ApiManagerController extends Controller
{
    //homepage
    function homepage()
    {
        return response()->json(200);
    }

    //view medicine table
    function viewMed()
    {
        $val=medicine::all();
        return response()->json($val,200);
    }

    //view user table
    function viewUser()
    {
        $val=users::all();
        return response()->json($val,200);
    }

    //view order table
    function viewOrders()
    {
        $val=order::all();
        return response()->json($val,200);
    }

    //delete medicine
    function deleteMed(Request $req)
    {
        medicine::where('med_id',$req->m_id)->delete();
        return response()->json(["msg"=>"Medicine deleted successfully!"],200);
    }

    //show supply table
    function showSupply()
    {
        $val=supply::all();
        return response()->json($val,200);
    }

    //ADD TO CART
    function addItem(Request $req)
    {
        $val=supply::where("supply_id",$req->supply_id)->first();
        if($req->quantity>$val->stock) //stock check
        {
            return response()->json(['msg'=>'Sorry, we are currently low on stock!']);
        }
        $total=$req->quantity*$val->price_perUnit;
        $dat=supply_cart::all();
        for ($i=0; $i < count($dat); $i++) //checking if item already added
        { 
            if($val->med_id==$dat[$i]->med_id)
            {
                $cart=supply_cart::where('med_id',$val->med_id)
                ->update(
                    ['quantity'=>$req->quantity+$dat[$i]->quantity,
                    'total_price'=>$total+$dat[$i]->total_price]
                );
                supply::where('supply_id',$req->supply_id)->update(['stock'=>$val->stock-$req->quantity]);
                return response()->json($cart,200);
            }
        }
        $item = new supply_cart();
        $item->med_name=$val->med_name;
        $item->med_id=$val->med_id;
        $item->vendor_id=$val->vendor_id;
        $item->price_perUnit=$val->price_perUnit;
        $item->quantity=$req->quantity;
        $item->total_price=$total;
        $item->save();

        supply::where('supply_id',$req->supply_id)->update(['stock'=>$val->stock-$req->quantity]);
        return response()->json($item,200);

    }

    //final cart
    function finalCart()
    {
        $dat=supply_cart::all();
        $tot=0;
        foreach($dat as $d)
        {
            $tot+=$d->total_price;
        }
        return response()->json($tot,200);
    }

    //view cart
    function viewCart()
    {
        $val=supply_cart::all();
        return response()->json($val,200);
    }

    //confirm order
    public function confirm(Request $req)
    {
        $v=supply_cart::all();
        $dat=contract::orderby('order_id','DESC')->first();
        //$vend=vendor::where('vendor_id',$v[0]->vendor_id)->first();
        if($dat==NULL)
        {
            $id=1;
        }
        else
        {
           $id=$dat->contract_id+1;
        }

        foreach($v as $val)
        {
            $item = new contract();

            $item->contract_id=$id;
            $item->vendor_id=$val->vendor_id;
            $item->manager_id=session()->get('manager_id');
            $item->med_name=$val->med_name;
            $item->quantity=$val->quantity;
            $item->total_price=$val->total_price;
            $item->save();
        }
        // $name=users::where('u_id',session()->get('logged.manager'))->first();

        //mail::to('faiyazkhondakar@gmail.com')->send(new SupplyOrder("Suppy Order Placement","Hi",session()->get('logged.manager'),$v));
        supply_cart::truncate();
        return response()->json(["msg"=>"Order Confirmed"],200);
    }
}