<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\manager;
use App\Models\customer;
use App\Models\vendor;
use App\Models\courier;
use App\Models\users;
use App\Models\medicine;
use App\Models\order;
use App\Models\contract;

class ManagerController extends Controller
{

    //Homepage Methods

    public function managerHome() //vuew manager homepage
    {
        $id=session()->get('logged.manager');
        $val=manager::where('u_id',$id)->first();
        session()->put('name',$val->manager_name);
        return view('ManagerView.Homepage');
    }

    public function HomeAction(Request $req) // selecting buttono actions
    {
        if($req->action=='View Users')
        {
            return redirect()->route("manager.tableSelect");
        }
        elseif($req->action=='View Medicine')
        {
            return redirect()->route("manager.tableMedicine");
        }
        elseif($req->action=='View Orders')
        {
            return redirect()->route("manager.tableOrder");
        }
        elseif($req->action=='View Contracts')
        {
            return redirect()->route("manager.tableContracts");
        }

    } 

    //TableSelect methods

    public function tableSelect() //view table selecting form
    {
        return view("ManagerView.TableSelect");
    }

    public function viewTable(Request $req) // table selecting for users
    {
        if($req->user=='Manager')
        {
            return redirect()->route("manager.tableManager");
        }
        else if($req->user=='Vendor')
        {
            return redirect()->route("manager.tableVendor");
        }
        else if($req->user=='Courier')
        {
            return redirect()->route("manager.tableCourier");
        }
        else if($req->user=='Customer')
        {
            return redirect()->route("manager.tableCustomer");
        }
    }

    //ViewTable methods

    public function viewCustomer() //view customer table
    {
        $val=users::where('u_type',"CUSTOMER")->get();
        return view("ManagerView.ViewTable")->with('Val',$val);
    }

    public function viewCourier() //view courier table
    {
        $val=users::where('u_type',"COURIER")->get();
        return view("ManagerView.ViewTable")->with('Val',$val);
    }

    public function viewVendor() //view vendor table
    {
        $val=users::where('u_type',"VENDOR")->get();
        return view("ManagerView.ViewTable")->with('Val',$val);
    }

    public function viewManager() //view manager table
    {
        $val=users::where('u_type',"MANAGER")->get();
        return view("ManagerView.ViewTable")->with('Val',$val);
    }

    //ViewDetails methods

    public function userInfo($id)
    {
        $val=users::where("u_id",$id)->first();
        return view('ManagerView.ViewDetails')->with('val',$val);
    }

    //Delete users methods

    public function userDelete($id)
    {
        $val=users::where("u_id",$id)->first();
        if($val->u_type=="CUSTOMER")
        {
            customer::where("u_id",$id)->delete();
        }
        if($val->u_type=="COURIER")
        {
            courier::where("u_id",$id)->delete();
        }
        if($val->u_type=="VENDOR")
        {
            vendor::where("u_id",$id)->delete();
        }
        if($val->u_type=="MANAGER")
        {
            manager::where("u_id",$id)->delete();
        }
        users::where("u_id",$id)->delete();
        return back();
    }

    //View Medicine

    public function viewMed() //view medicine table
    {
        $val=medicine::all();
        return view("ManagerView.ViewMed")->with('data',$val);
    }

    //Delete Medicine

    public function medDelete($id)
    {
        medicine::where("med_id",$id)->delete();
    }

    //view med info

    public function medInfo($id)
    {
        $val=medicine::where("med_id",$id)->first();
        return view('ManagerView.MedDetails')->with('val',$val);
    }

    //View Order

    public function viewOrder() //view order table
    {
        $val=order::all();
        return view("ManagerView.ViewOrders")->with('data',$val);
    }

    //view order info

    public function orderInfo($id)
    {
        $val=order::where("order_id",$id)->first();
        return view('ManagerView.OrderDetails')->with('val',$val);
    }

    //View Contracts

    public function viewContract() //view contract table
    {
        $val=contract::all();
        return view("ManagerView.ViewContracts")->with('data',$val);
    }

    //Delete contracts

    public function contractDelete($id)
    {
        contract::where("contract_id",$id)->delete();
    }

    //view med info

    public function contractInfo($id)
    {
        $val=medicine::where("contract_id",$id)->first();
        return view('ManagerView.ContractDetails')->with('val',$val);
    }

}
