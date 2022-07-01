<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\customer;

class CustomerController extends Controller
{
    //

        //Customer
        public function customerHome()
        {
            $u_id=session()->get('logged.customer');
            $customer=customer::where('u_id',$u_id)->first();
            session()->put('name',$customer->customer_name);
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
            $u_id=$req->u_id;
            $this->validate($req,
            [
                // "name"=> "required|regex:/^[A-Za-z- .,]+$/i",
                // "password"=>"required|min:8|regex:/^.*(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$ %^&*~><.,:;]).*$/i",
                // "confirmPassword"=>"required|same:password",
                // "email"=>"required"
            ]);

            $modified = users::where('u_id',$u_id)
                                ->update(
                                    ['u_name'=>$req->name,
                                    'u_email'=>$req->email,
                                    'u_pass'=>$req->password]
                                );
            $u_id=session()->get('logged.customer');
            return redirect()->route('customer.account');
        }
}
