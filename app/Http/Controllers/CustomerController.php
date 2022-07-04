<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\users;
use App\Models\customer;
use App\Models\medicine;
use App\Models\carts;
use App\Models\order;
use App\Models\orders_cart;



class CustomerController extends Controller
{
    // 
 
    //Customer
    public function customerHome()
    {
        $u_id=session()->get('logged.customer');
        $customer=customer::where('u_id',$u_id)->first();
        session()->put('name',$customer->customer_name);
        session()->put('customer_id',$customer->customer_id);
        return view('CustomerView.home')->with('name',$customer->customer_name);
    }

    public function customerAccount()
    {
        $u_id=session()->get('logged.customer');
        $customer=customer::where('u_id',$u_id)->first();
        return view('CustomerView.account')->with('customer',$customer);
    }

    //MODIFY CUSTOMER ACCOUNT
    public function customerModifyAccount($name)
    {
        $u_id=session()->get('logged.customer');
        $customer=customer::where('u_id',$u_id)->first();
        return view('CustomerView.modify')->with('customer',$customer);
    }

    public function customerModifiedAccount(Request $req,$name)
    {
        $name=$req->name;
        $u_id=$req->u_id;
        $this->validate($req,
        [
            // "name"=> "required|regex:/^[A-Za-z- .,]+$/i",
            // "password"=>"required|min:8|regex:/^.*(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$ %^&*~><.,:;]).*$/i",
            // "confirmPassword"=>"required|same:password",
            // "email"=>"required"
        ]);
        //
        // if ($req->hasFile('profilepic'))
        // {
        //     return 'true';
        // }
        // else
        // {
        //     return 'false';
        // }
        $name = $req->file('profilepic')->getClientOriginalName();
        $req->file('profilepic')->storeAs('public/profilepictures',session()->get('logged.customer').".jpg");
        //
        users::where('u_id',$u_id)
                    ->update(
                        ['u_name'=>$req->name,
                        'u_email'=>$req->email,
                        'u_pass'=>$req->password]
                    );
        customer::where('customer_id',$req->customer_id)
        ->update(
            ['customer_name'=>$req->name,
            'customer_email'=>$req->email
            ]
        );

        session()->put('name',$name);
        return redirect()->route('customer.account',['name'=>$name]);
    }

    //SHOW MEDICINE LIST
    function showMed()
    {
        $meds=medicine::all();
        return view('CustomerView.medlist')->with('meds',$meds);
    }

    //ADD TO CART
    function addToCart(Request $req)
    {
        $this->validate($req,
        [
            'quantity'=> 'required|numeric|gt:0'
        ],
        [
            'quantity.required'=>'Enter a quantity first',
            'quantity.min'=>'Minimum of order quantity=1 is required'
        ]);

        //find cart_id

        $info=order::orderBy('cart_id','DESC')->first();
        
        $med=medicine::where('med_id',$req->med_id)->first();
        $cart= new carts();
        if ($info==NULL)
        {
            $cart->cart_id=1;
        }
        else
        {
            $cart->cart_id=$info->cart_id+1;
        }  
        $cart->customer_id=session()->get('customer_id');
        $cart->med_id= $req->med_id;
        $cart->price_perUnit=$med->price_perUnit;
        $cart->med_name=$med->med_name;
        $cart->quantity=$req->quantity;
        $cart->total=$req->quantity*$med->price_perUnit;
        $cart->save();
        $subtotal=session()->get('subtotal')+$req->quantity*$med->price_perUnit;
        session()->put('subtotal',$subtotal);

        $meds=medicine::all();
        return view('CustomerView.medlist')->with('meds',$meds);
    }

    //SHOW CART

    public function showCart()
    {
        $cart=carts::all();
        return view('CustomerView.showcart')->with('cart',$cart);
    }

    //CONFIRM ORDER

    public function confirmOrder(Request $req)
    {
        $info=order::orderBy('cart_id','DESC')->first();
        $order=new order();
        $order->customer_id=session()->get('customer_id');
        $order->totalbill=session()->get('subtotal');
        if ($info==NULL)
        {
            $order->cart_id=1;
        }
        else
        {
            $order->cart_id=$info->cart_id+1;
        }  
        $order->save();

        $information=order::orderBy('order_id','DESC')->first();


        $it=carts::all();

        // return $items;
        
        foreach($it as $item)
        {
            $add=new orders_cart();
            $add->order_id=$information->order_id;
            $add->cart_id=$information->cart_id;
            $add->items=$item->med_name;
            $add->quantity=$item->quantity;
            $add->med_id=$item->med_id;
            $add->save();
        }
        mail::to('ayesha.akhtar.1999@gmail.com')->send(new OrderConfirmation("ORDER CONFIRMATION MAIL",session()->get('customer_id'),
                                                                                session()->get('name'),$information->cart_id));
        // if (mail::failures()) {
        //     return response()->Fail('Failed to send email');
        // }else{
        //     return response()->success('An invoice has been successfully send to your mail');
        //     }
        
        return redirect()->route('customer.check.out');
    }

    //Clear CART

    public function clearCart()
    {
        carts::truncate();
        session()->flash('msg','CART CLEARED');
        session()->forget('subtotal');
        return redirect()->route('customer.show.cart');
    }

    //Check out
    public function checkOut()
    {
        $order=order::where('customer_id',session()->get('customer_id'))
            ->orderBy('order_id','DESC')
            ->first();
        carts::truncate();
        session()->put('subtotal',0);
        return view('CustomerView.checkout')->with('order',$order);
    }

    //DELETE FROM CART
    function deleteItem($item_id)
    {
        $total=carts::where('item_id',$item_id)->first();
        carts::where('item_id',$item_id)->delete();
        $subtotal=session()->get('subtotal')-$total->total;
        session()->put('subtotal',$subtotal);
        return back();
    }

    //ORDER LIST

    function showOrders()
    {
        $orders=order::where('customer_id',session()->get('customer_id'))->get();

        // return $orders;
        // return $orders->orders_cart;
        return view('CustomerView.showOrders')->with('orders',$orders);
    }

    //OrderDetails

    function showOrderDetails($order_id)
    {
        $orders=order::where('customer_id',session()->get('customer_id'))->get();
        
        $collection=order::where('customer_id',session()->get('customer_id'))
                    ->where('order_id',$order_id)                
                    ->get();
        // return $items;
        // return $items[0]->orders_cart;
        return view('CustomerView.showOrderdetails')->with('orders',$orders)
                                                ->with('collection',$collection);
        
    }
}