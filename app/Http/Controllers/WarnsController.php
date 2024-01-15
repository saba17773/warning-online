<?php

namespace App\Http\Controllers;

use App\Warn;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use DateTime;

class WarnsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth', ['except' => ['report', 'test']]); // << บังคับให้ Login ก่อนจึงสามารถเข้าใช้ได้
        $this->warn = new warn; // << ใช้งาน Model
    }

    public function index()
    {
        return view('pages.home');
    }

    public function create()
    {
        return view('pages.create');
    }

    public function test()
    {
        return view('pages.test');
    }

    // public function store(Request $request)
    // {

    // }

    // public function show(Warn $warn)
    // {
    //     //
    // }

    public function edit(Request $request)
    {





        $result = $this->warn->edit($request);
        // return $result;
        if ($result["result"] == true) {
            $trans = $this->warn->trans($request, 1, null);
            return $result;
        } else {
            return $result;
        }
    }

    public function destroy(Warn $warn)
    {
        //
    }
    public function insert(Request $request)
    {
        // return $request;

        // $expire_time = substr($request->warning_date, 0, strpos($request->warning_date, '('));

        // $final = date('Y-m-d h:i:s', strtotime($expire_time));

        // return $request;
        $result = $this->warn->insert($request);
        // return $result;

        if ($result["result"] == true) {
            $this->warn->trans($request, 0, $result["warning_no"]);
            return $result;
        } else {
            return $result;
        }
    }

    public function report($warning_no)
    {
        $check = $this->warn->checkcomplete($warning_no);
        if ($check["result"] == 1) {
            $result = $this->warn->report($check["data"]);
            return view('pages.report')->with($result);
        } else {
            return $check["data"];
        }
    }

    public function report2($warning_no)
    {
        $check = $this->warn->checkcomplete($warning_no);
        if ($check["result"] == 1) {
            $result = $this->warn->report($check["data"]);
            return view('pages.report2')->with($result);
        } else {
            return $check["data"];
        }
    }

    public function email($warning_no)
    {
        $result = $this->warn->geteditdata($warning_no);
        return view('pages.sendmail')->with($result);
    }

    public function Cancelcase(Request $request)
    {




        // return $request;
        $result = $this->warn->Cancelcase($request);
        return $result;
        // if ($result["result"] == true) {
        //     $trans = $this->warn->trans($request, 1, null);
        //     return $result;
        // } else {
        //     return $result;
        // }
    }
}
