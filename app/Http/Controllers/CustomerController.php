<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Mail\sendComplain;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\users;
use App\Models\customer;
use App\Models\medicine;
use App\Models\carts;
use App\Models\order;
use App\Models\orders_cart;
use Symfony\Component\HttpKernel\Event\RequestEvent;

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
        session()->put('email',$customer->customer_email);
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
            "name"=> "required|regex:/^[A-Za-z- .,]+$/i",
            "password"=>"required|min:8|regex:/^.*(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$ %^&*~><.,:;]).*$/i",
            "confirmPassword"=>"required|same:password",
            "profilepic"=>"mimes:jpg,png,jpeg"
        ],
        [
            "password.regex"=>"Password must contain minimum 1 special character and minimum 1 upper case letter."

        ]);
        
        if ($req->hasFile('profilepic'))
        {
            $imgname = session()->get('logged.customer').".jpg";
            $req->file('profilepic')->storeAs('public/profilepictures',$imgname);
            //
            users::where('u_id',$u_id)
                        ->update(
                            ['u_name'=>$req->name,
                            'u_pass'=>$req->password
                            ]
                        );
            customer::where('customer_id',$req->customer_id)
                        ->update(
                            ['customer_name'=>$req->name,
                            'img'=>$imgname
                            ]
                        );
        }
        else
        {
            users::where('u_id',$u_id)
                        ->update(
                            ['u_name'=>$req->name,
                            'u_email'=>$req->email,
                            'u_pass'=>$req->password
                            ]
                        );
            customer::where('customer_id',$req->customer_id)
            ->update(
                ['customer_name'=>$req->name,
                'customer_email'=>$req->email
                ]
            );
        }
        

        session()->put('name',$name);
        return redirect()->route('customer.account',['name'=>$name]);
    }

    //SHOW MEDICINE LIST
    function showMed()
    {
        // $meds=medicine::all();
        $meds=medicine::paginate(10);

        return view('CustomerView.medlist',compact('meds'));
    }

    //ADD TO CART
    function addToCart(Request $req)
    {
        if($req->add=="SEARCH")
        {
            $meds=medicine::where('med_name','LIKE',$req->search.'%')->paginate(10);
        }
        else if($req->add=="ORDER BY PRICE HIGHEST TO LOWEST")
        {
            $meds=medicine::orderBy('price_perUnit','DESC')->paginate(10);
            // return $meds;
        }
        else if($req->add=="ORDER BY PRICE LOWEST TO HIGHEST")
        {
            $meds=medicine::orderBy('price_perUnit','ASC')->paginate(10);
            // return $meds;
        }
        else
        {
            $this->validate($req,
            [
                'quantity'=> 'required|numeric|max:'.$req->Stock.'|gt:0'
            ],
            [
                'quantity.required'=>'Enter a quantity first',
                'quantity.gt'=>'Minimum of order quantity=1 is required',
                'quantity.max'=>'Quantity must be greater than stock'
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

            medicine::where('med_id',$req->med_id)->update(['Stock'=>$req->Stock-$req->quantity]);
            $subtotal=session()->get('subtotal')+$req->quantity*$med->price_perUnit;
            session()->put('subtotal',$subtotal);
            $meds=medicine::paginate(10);

        }
        return view('CustomerView.medlist')->with('meds',$meds);
    }

    //SHOW CART

    public function showCart()
    {
        $cart=carts::paginate(7);
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
        $info=medicine::where('med_id',$total->med_id)->first();
        // return $info;
        medicine::where('med_id',$total->med_id)->update(['Stock'=>$info->Stock+$total->quantity]);
        carts::where('item_id',$item_id)->delete();
        $subtotal=session()->get('subtotal')-$total->total;
        session()->put('subtotal',$subtotal);
        return redirect()->route('customer.show.cart');

    }

    //ORDER LIST

    function showOrders()
    {
        $orders=order::where('customer_id',session()->get('customer_id'))
        ->orderBy('order_id','DESC')
        ->paginate(5);

        // return $orders;
        // return $orders->orders_cart;
        return view('CustomerView.showOrders')->with('orders',$orders);
    }

    //OrderDetails

    function showOrderDetails($order_id)
    {
        $orders=order::where('customer_id',session()->get('customer_id'))
                
        ->orderBy('order_id','DESC')
        ->paginate(5);
        
        $collection=order::where('customer_id',session()->get('customer_id'))
                    ->where('order_id',$order_id)                
                    ->get();
        // return $items;
        // return $items[0]->orders_cart;
        return view('CustomerView.showOrderdetails')->with('orders',$orders)
                                                ->with('collection',$collection);
        
    }

    //return item

    public function returnItem()
    {
        $order=order::where('customer_id',session()->get('customer_id'))
                    ->where('order_status','delivered')->paginate(3);
        return view('CustomerView.returnItem')->with('order',$order);
    }

    public function returnedItem(Request $req)
    {
        if($req->return=='RETURN')
        {
            orders_cart::where('id',$req->id)->update(['return_status'=>'true']);
        }
        return back();
    }

    //CANCEL ORDER

    function cancelOrder($order_id)
    {
        order::where('order_id',$order_id)->update(['order_status'=>'Cancelled']);
        return back();
    }

    //FILE A COMPLAIN
    function complain()
    {
        return view('CustomerView.complain');
    }
    function complainEmail(Request $req)
    {
        mail::to('ayesha.akhtar.1999@gmail.com')->send(new sendComplain("Complain from Customer#".$req->customer_id,$req->customer_id,
                                                                               $req->msg));
        session()->flash('emailsent','EMAIL SENT SUCCESSFULLY');
        return back();                                                                      
    }
}