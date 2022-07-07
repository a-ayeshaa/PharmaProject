<?php

namespace App\Http\Controllers;
use App\Models\users;
use App\Models\vendor;
use App\Models\supply;
use App\Models\contract;
use App\Models\medicine;

use Illuminate\Http\Request;

class vendorcontroller extends Controller
{
    public function home(){
        $u_id=session()->get('logged.vendor');
        $vendor=vendor::where('u_id',$u_id)->first();
        session()->put('logged.vendor_id',$vendor->vendor_id);
        return view ('vendor.home');
    }
    public function contractdetails($contract_id){
        $contract=contract::where('contract_id',$contract_id)->get();
        
        session()->put('contract.contract_id',$contract_id);
        return view ('vendor.contractdetails')->with('contract',$contract);

    }

    public function contractstatus(Request $request){
        $status=$request->status;
        if($status=='Accept'){
            //contract update status
            $contract_id=session()->get('contract.contract_id');
            $modified = contract::where('contract_id',$contract_id) 
                        ->update(['contract_status'=>$status]);
        
            $new=contract::where('contract_id',$contract_id)->get();
            

            
            //add money to vendor accout
            $sale=contract::where('contract_id',$contract_id)->value('total_price');
            $vendor_id=session()->get('logged.vendor_id');
            $vendor_account=vendor::where('vendor_id',$vendor_id)->value('account');
            $vendor_account=$vendor_account + $sale ;
            $update=vendor::where('vendor_id',$vendor_id)
                            ->update(['account'=>$vendor_account]);
            
            
            //add medicine
            foreach ($new as $item ) {
                $vendor_name=vendor::where('vendor_id',$item->vendor_id)->value('vendor_name');
                $new1=supply::where('med_name',$item->med_name)->first();
                $price=$new1->price_perUnit;
                $price=$price*1.4;

                
                $med= new medicine();
                $med->med_id = $new1->med_id;
                $med->med_name =$item->med_name;
                $med->price_perUnit =$price;
                $med->stock = $item->quantity;
                $med->contract_id = $contract_id;
                $med->expiryDate = $new1->expiryDate;
                $med->manufacturingDate = $new1->manufacturingDate;
                $med->vendor_id=$item->vendor_id;
                $med->vendor_name=$vendor_name;
                $med->save();
                //update supply table
                $quantity=$item->quantity;
                $stock=supply::where('med_id',$new1->med_id)->value('stock');
                $stock=$stock-$quantity;
                $modified2 = supply::where('med_id',$new1->med_id) 
                        ->update(['stock'=>$stock]);
 
                
            }
            
            
        }
        elseif($status=='Reject'){
             $contract_id=session()->get('contract.contract_id');
             $modified = contract::where('contract_id',$contract_id) 
                        ->update(['contract_status'=>$status]);

        }
        
        
        return redirect()->route('vendor.contracts');

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
            "name"=> "required|regex:/^[A-Za-z- .,]+$/i",
            "password"=>"required|min:8|regex:/^.*(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$ %^&*~><.,:;]).*$/i",
            "confirmPassword"=>"required|same:password",
            "email"=>"required|unique:users,u_email"
        ],
        [
            "password.regex"=>"Password must contain minimum 1 special character and minimum 1 upper case letter."

        ]);

        $modified = users::where('u_id',$u_id) 
                            ->update(
                                ['u_name'=>$req->name,
                                'u_email'=>$req->email,
                                'u_pass'=>$req->password]
                            );
        
        $vendor=vendor::where('u_id',$u_id)
                            ->update(
                                ['vendor_name' =>$req->name,
                                'vendor_email' =>$req->email]
                            );
        session()->flash("updated","Sucessfully Updated");
        $u_id=session()->get('logged.vendor');
        return redirect()->route('vendor.profile');
    }


    public function contracts(){
        
        $contract=contract::select('contract_id','manager_name','total_price','contract_status')->distinct()->get();
        
        return view ('vendor.contracts')->with('contract',$contract);
    }


    public function market(){
        $supp=supply::all();
        return view ('vendor.market')->with('supp',$supp);
    }


    public function supply(){
        $v_id=session()->get('logged.vendor_id');
        $supp=supply::where('vendor_id',$v_id)->paginate(5);
        return view('vendor.supply')->with('supp',$supp);

    }


    public function addsupply(){
        
        return view('vendor.addsupply');
    }
    
    public function updatesupply($supply_id){
        
        $supply=supply::where('supply_id',$supply_id)->first();
        return view('vendor.updatesupply')->with('supply',$supply);
    }

    public function updatedsupply(Request $req,$supply_id){
        $addedstock=$req->stock;
        $supply=supply::where('supply_id',$supply_id)->first();
        $stock=$supply->stock;
        $addedstock=$addedstock+$stock;
        
        $this->validate($req,
        [
            "price_perUnit"=>"required|numeric|min:0|not_in:0",
            "stock"=>"required|numeric|min:1|not_in:0",
            "expiryDate"=>"required|after:yesterday",
            "manufacturingDate"=>"required|before:today"
        ]);

        $modified = supply::where('supply_id',$supply_id) 
                            ->update(
                                ['price_perUnit'=>$req->price_perUnit,
                                'stock'=>$addedstock,
                                'expiryDate'=>$req->expiryDate,
                                'manufacturingDate'=>$req->manufacturingDate]
                            );
        
        return redirect()->route('vendor.supply');
    }
    //not done

    public function deletesupply(){
        
        return ;
    }

    public function addedsupply(Request $req){
        $this->validate($req,
        [
            "med_id"=> "required|unique:supply,med_id",
            "med_name"=>"required|unique:supply,med_name",
            "price_perUnit"=>"required|numeric|min:0|not_in:0",
            "stock"=>"required|numeric|min:1|not_in:1",
            "expiryDate"=>"required|after:yesterday",
            "manufacturingDate"=>"required|before:today"
        ],
        [
            "med_name.unique"=>"This med already Exists.If You want to add,Please Update stock"
        ]  
    
    );
        


        session()->flash("added","Sucessfully Added");
        $v_id=session()->get('logged.vendor_id');
        $supply= new supply();
        $supply->med_id = $req->med_id;
        $supply->med_name =$req->med_name;
        $supply->price_perUnit =$req->price_perUnit;
        $supply->stock = $req->stock;
        $supply->expiryDate = $req->expiryDate;
        $supply->manufacturingDate = $req->manufacturingDate;
        $supply->vendor_id=$v_id;
        $supply->save();
        return redirect()->route('vendor.supply');
        
    }
}
