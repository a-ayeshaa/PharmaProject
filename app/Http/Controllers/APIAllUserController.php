<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\users;
use Illuminate\Http\Request;
use App\Models\customer;
use App\Models\manager;
use App\Models\vendor;
use App\Models\courier;

class APIAllUserController extends Controller
{
    //
    function getUsers()
    {
        $data = users::all();
        return response()->json($data);
    }

    //LOGIN

    function getUser($email)
    {
        // $validator = Validator::make($req->all(),[
        //     "name"=>"required",
        //     "email"=>"required"
        // ]);
        // if ($validator->fails())
        // {
        //     return response()->json($validator->errors());
        // }
        $data = users::where('u_email',$email)->first();
        return response()->json($data);
    }

    //Create User
    function createUser(Request $req)
    {
        // $validator = Validator::make($req->all(),[
        //     "name"=>"required",
        //     // "email"=>"required|unique:users,u_email",    
        //     "email"=>"required",    
        //     "password"=>"required",
        //     // "confirmpassword"=>"required,same:password"
        //     "confirmpassword"=>"required"
            
        // ]);
        
        // if ($validator->fails())
        // {
        //     return response()->json($validator->errors(),422);
        // }

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

        return response()->json([
            "msg"=>"User created successfully",
            "user"=>$user
        ]);
        
    }
}