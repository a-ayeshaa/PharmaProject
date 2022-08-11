<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\users;
use Illuminate\Http\Request;
use App\Models\customer;
use App\Models\manager;
use App\Models\carts;
use App\Models\vendor;
use App\Models\courier;
use App\Models\Token;
use DateTime;
use Illuminate\Support\Str;

class APIAllUserController extends Controller
{
    //
    function getUsers()
    {
        $data = users::all();
        return response()->json($data);
    }

    //LOGIN

    function login(Request $req)
    {
        $validator = Validator::make($req->all(),[
            "u_pass"=>"required",
            "u_email"=>"required"
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(),404);
        }
        $email=$req->u_email;
        $password=$req->u_pass;
        $user=users::where('u_email',$email)
                    ->where('u_pass',$password)->first();

        if($user!=NULL)
        {
            $token=Token::where('u_id',$user->u_id)
                    ->whereNull('expired_at')->first();
            if($token!=NULL)
            {
                return response()->json($token,200);
            }
            $key=Str::random(67);  
            $token = new Token();
            $token->token=$key;
            $token->u_id=$user->u_id;
            $token->created_at=new DateTime();
            $token->save();
            return response()->json($token,200);
        }
        else
        {
            return response()->json(["msg"=>"Invalid user"],404);
        }
        
    }

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

    //logout
    public function logout(Request $req)
    {
        $key = $req->header("Authorization");
        // return response()->json($key,200);
        
        $tk = Token::where("token",$key)->first();
        $tk->expired_at = new Datetime();
        $tk->save();
        carts::truncate();
        return response()->json(["msg"=>"logged out"],200);
    }

    //Create User
    function createUser(Request $req)
    {
        $validator = Validator::make($req->all(),[
            "email"=>"required|unique:users,u_email",    
            "name"=> "required|regex:/^[A-Za-z- .,]+$/i",
            "password"=>"required|min:8|regex:/^.*(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$ %^&*~><.,:;]).*$/i",   
            "confirmpassword"=>"required|same:password",
            "type"=>"required"
            // "confirmpassword"=>"required"
            
        ],[
            "confirmpassword.required"=>"Confirm password is required"
        ]);
        
        if ($validator->fails())
        {
            return response()->json($validator->errors(),422);
        }

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