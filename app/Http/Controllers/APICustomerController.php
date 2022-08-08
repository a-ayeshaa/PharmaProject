<?php

namespace App\Http\Controllers;

use App\Models\carts;
use App\Models\customer;
use App\Models\medicine;
use App\Models\order;
use App\Models\Token;
use App\Models\users;
use Illuminate\Http\Request;

class APICustomerController extends Controller
{
    public function getID($token)
    {
        $u_id=Token::where('token',$token)->first();
        $info=users::where('u_id',$u_id->u_id)->first();
        if ($info->u_type=='CUSTOMER')
        {
            $customer_id=customer::where('customer_id',$info->u_id)->first();
            // return "hello";
            return $customer_id->customer_id;
        }
    }
    //
    function home()
    {
        return response()->json(200);
    }

    //SHOW MEDICINE
    function showMed()
    {
        $med=medicine::all();
        return response()->json($med,200);
    }

    //ADD TO CART
    function addToCart(Request $req)
    {
        // return response()->json([$req->med_id,$req->quantity]);
        //find cart_id
        $customer_id=$this->getID($req->header("Authorization"));
        $med=medicine::where('med_id',$req->med_id)->first();
        // return response()->json($med,200);
        $carts=carts::where('med_id',$req->med_id)->first();
        if ($carts==NULL)
        {
            $info=order::orderBy('cart_id','DESC')->first();
        
            $cart= new carts();
            if ($info==NULL)
            {
                $cart->cart_id=1;
            }
            else
            {
                $cart->cart_id=$info->cart_id+1;
            }  
            $cart->customer_id=$customer_id;
            $cart->med_id= $req->med_id;
            $cart->price_perUnit=$med->price_perUnit;
            $cart->med_name=$med->med_name;
            $cart->quantity=$req->quantity;
            $cart->total=$req->quantity*$med->price_perUnit;
            $cart->save();

            
        }
        else
        {
            $cart=carts::where('med_id',$req->med_id)
                ->update(['quantity'=>$carts->quantity+$req->quantity,'total'=>$carts->total+$req->quantity*$med->price_perUnit]);
        }

        medicine::where('med_id',$req->med_id)->update(['Stock'=>$req->Stock-$req->quantity]);
        // $subtotal=session()->get('subtotal')+$req->quantity*$med->price_perUnit;
        // session()->put('subtotal',$subtotal);

        // $meds=medicine::paginate(10);
        return response()->json($cart,200);

    }

    // function 
}