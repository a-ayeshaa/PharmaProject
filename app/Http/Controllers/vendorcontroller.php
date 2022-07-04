<?php

namespace App\Http\Controllers;
use App\Models\users;
use App\Models\vendor;

use Illuminate\Http\Request;

class vendorcontroller extends Controller
{
    public function home(){
        return view ('vendor.home');
    }
    public function profile(Request $request){
        $u_id=session()->get('logged.vendor');
        $vendor=vendor::where('u_id',$u_id)->first();
        return view('vendor.profile')->with('vendor',$vendor);
        
    }
    
    public function editprofile(){
        $u_id=session()->get('logged.vendor');
        $vendor=vendor::where('u_id',$u_id)->first();
        return view('vendor.editprofile')->with('vendor',$vendor);
    }
    public function editedprofile(Request $req){
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
        //return $modified;
        //bojha lagbe amr .....bojhay dio kew
        $vendor=vendor::where('u_id',$u_id)
                            ->update(
                                ['vendor_name' =>$req->name,
                                'vendor_email' =>$req->email]
                            );
        
        $u_id=session()->get('logged.vendor');
        return redirect()->route('vendor.profile');
    }

    public function contracts(){
        return view ('vendor.contracts');
    }
    public function market(){
        return view ('vendor.market');
    }
    public function supply(){
        return view ('vendor.supply');
    }
}
