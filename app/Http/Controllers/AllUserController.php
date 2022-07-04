<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\customer;
use App\Models\manager;
use App\Models\vendor;
use App\Models\courier;
use App\Models\carts;

use function PHPUnit\Framework\isNull;

class AllUserController extends Controller
{
    //

    public function welcome()
    { 
        return view('user.welcome');
    }

    //REGISTRATION FOR ALL USERS 

    //SELECTING USER-TYPE
    public function registration()
    {
        return view('AllUserView.Registration');
    }

    public function registrationSubmit(Request $req)
    {
        return redirect()->route('user.register',['type'=>$req->type]);
    }

    //REGISTRATION PAGE FOR SPECIFIC USER

    public function register($type)
    {
        return view('AllUserView.Register')->with('type',$type);
    }
    
    public function registerSubmit($type, Request $req)
    {

        $this->validate($req,
        [
            "name"=> "required|regex:/^[A-Za-z- .,]+$/i",
            "password"=>"required|min:8|regex:/^.*(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$ %^&*~><.,:;]).*$/i",
            "confirmPassword"=>"required|same:password",
            "email"=>"required|unique:users,u_email"
        ],
        [
            "password.regex"=>"Password must contain minimum 1 special character and minimum 1 upper case letter."

        ]);

        $user= new users();
        $user->u_name = $req->name;
        $user->u_email =$req->email;
        $user->u_pass =$req->password;
        $user->u_type = $req->type;
        $user->save();

        $user=users::where('u_email',$req->email)
                    ->where('u_pass',$req->password)
                    ->first();

        //user table to customer table
        if ($req->type == 'CUSTOMER')
        {
            $customer= new customer();
            $customer->u_id=$user->u_id;
            $customer->customer_name = $req->name;
            $customer->customer_email =$req->email;
            $customer->save();
        }
        
        //user table to vendor table
        if ($req->type == 'VENDOR')
        {
            $vendor= new vendor();
            $vendor->u_id=$user->u_id;
            $vendor->vendor_name = $req->name;
            $vendor->vendor_email =$req->email;
            $vendor->save();
        }
        //user table to manager table
        if ($req->type == 'MANAGER')
        {
            $manager= new manager();
            $manager->u_id=$user->u_id;
            $manager->manager_name = $req->name;
            $manager->manager_email =$req->email;
            $manager->save();
        }
        //user table to courier table
        if ($req->type == 'COURIER')
        {
            $courier= new courier();
            $courier->u_id=$user->u_id;
            $courier->courier_name = $req->name;
            $courier->courier_email =$req->email;
            $courier->save();
        }

        
        return redirect()->route('user.login');
    }

    // LOGIN 

    public function login()
    {
        return view('AllUserView.Login');
    }
    
    public function loginSubmit(Request $req)
    {
        $this->validate($req,
        [
            "email"=>"required",
            "password"=>"required"
        ]);

        $user=users::where('u_email',$req->email)
                    ->where('u_pass',$req->password)
                    ->first();
        
        if($user)
        {
            if($user->u_type=="VENDOR")
            {
                session()->put('logged.vendor',$user->u_id);
                return redirect()->route('vendor.home');
            }
            else if($user->u_type=="MANAGER")
            {
                session()->put('logged.manager',$user->u_id);
                return redirect()->route('manager.home');
            }

            else if($user->u_type=="COURIER")
            {
                session()->put('logged.courier',$user->u_id);
                return redirect()->route('courier.home');
            }
            else if($user->u_type=="CUSTOMER")
            {
                session()->put('logged.customer',$user->u_id);
                return redirect()->route('customer.home');
            }
        }

        else
        {
            session()->flash('msg','User not valid');
            return back();
        }

    }

    //LOGOUT

    public function logout()
    {
        session()->flush();
        carts::truncate();
        session()->flash('msg','Sucessfully Logged out');
        return redirect()->route('user.login');
    }
}
