<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\customer;
use App\Models\medicine;
use App\Models\carts;

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
            $med=medicine::where('med_id',$req->med_id)->first();
            $cart= new carts();
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
}