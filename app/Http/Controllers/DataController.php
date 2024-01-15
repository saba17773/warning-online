<?php

namespace App\Http\Controllers;

use Wattanar\Sqlsrv;
use Illuminate\Http\Request;
use DB;
use App\Warn;
use Auth;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['regis', 'empl2', 'loginuser', 'empl', 'emplid', 'corid', 'penaltyid', 'penaltyqty']]);
        $this->warn = new warn; // << ใช้งาน Model
    }

    public function regis(REQUEST $request)
    {


        $checklen = strlen($request->password);
        if ($checklen < 3) {
            return ['result' => false, 'message' => 'รหัสผ่านต้องยาว 3 ตัวอักษรขึ้นไป !'];
        }
        $datetime = date("Y-m-d H:i:s");
        $check = DB::table('users')->where('userid', $request->userid)->value('userid');
        if ($check) {
            return ['result' => false, 'message' => 'มีผู้ใช้งานนี้อยู่ในระบบอยู่แล้ว !'];
        }
        // return ['result' => false ,'message' => $checklen ];
        try {
            DB::table('users')->insert([
                'emplid' => $request->emplid,
                'userid' => $request->userid,
                'name' => $request->name,
                'email' => $request->email,
                'company' => $request->company,
                'department' => $request->division,
                'password' => $request->password,
                'created_at' => $datetime,
                'level' => 1,
                'active' => 1,
            ]);

            return ['result' => true];
        } catch (\Throwable $th) {
            return ['result' => false, 'message' => $th->errorInfo["2"]];
        }
    }

    public function loginuser()
    {
        // $data = Auth::user();     
        $data = auth()->user()->company;
        return response()->json($data);
    }

    public function empl()
    {
        $datauserdp = auth()->user()->department;
        $level = auth()->user()->level;
        $company = auth()->user()->company;
        if ($level == 1) {
            $datauserdp = auth()->user()->department;
            $check = "DIVISIONNAME = '$datauserdp' AND COMPANYNAME = '$company'";
        } else {
            if ($company == "DSL") {
                $check = "COMPANYNAME IN ('DSL','DSC','DRB')";
            } else {
                $check = "COMPANYNAME = '$company'";
            }
        }
        $data = DB::connection('sqlsrv2')->select(
            "SELECT E.CODEMPID,E.CARDID,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname],T1.EMAIL,E.COMPANYNAME,E.DIVISIONNAME,E.DIVISIONCODE,E.DEPARTMENTNAME,E.POSITIONNAME
            FROM EMPLOYEE E
            JOIN TEMPLOY1 T1 ON E.CODEMPID = T1.CODEMPID
            WHERE E.EMP_STATUS in (1,3) AND  $check ORDER BY CODEMPID ASC
            
            
            "
        );

        return $data;
    }

    public function empl2()
    {
        $data = DB::connection('sqlsrv2')->select(
            "SELECT E.CODEMPID,E.CARDID,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname],T1.EMAIL,E.COMPANYNAME,E.DIVISIONNAME,E.DIVISIONCODE,E.DEPARTMENTNAME,E.POSITIONNAME
            FROM EMPLOYEE E
            JOIN TEMPLOY1 T1 ON E.CODEMPID = T1.CODEMPID
            WHERE E.EMP_STATUS in (1,3) 
            AND T1.EMAIL != 'dummy@tjs.co.th'
            AND T1.EMAIL != '0'
            
            ORDER BY CODEMPID ASC
            
            "
        );

        return $data;
    }

    public static function connect()
    {
        $server = "BANANA\DEVELOP";
        $user = "EAConnection";
        $password = "l;ylfu;yo0yomiN";
        $database = "HRTRAINING_DEV";

        return Sqlsrv::connect(
            $server,
            $user,
            $password,
            $database
        );
    }




    public function blacklist_group()
    {
        $data = DB::table('blacklist_group')->where('active', '=', 1)->get();
        return $data;
    }

    public function emplid($emplid)
    {

        $data =  DB::connection('sqlsrv2')->select(
            "SELECT E.CODEMPID,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname],T1.EMAIL,E.COMPANYNAME,E.DIVISIONNAME,E.DIVISIONCODE,E.DEPARTMENTNAME,E.POSITIONNAME
                FROM EMPLOYEE E
                JOIN TEMPLOY1 T1 ON E.CODEMPID = T1.CODEMPID
                WHERE E.CODEMPID = ?
                AND E.EMP_STATUS = 1 ORDER BY CODEMPID ASC",
            [$emplid]
        );
        if (!$data) {
            return ['result' => false];
        } else {
            return ['result' => true, 'data' => $data];
            // return response()->json($data);   
        }
    }

    public function warning()
    {
        $usercompany = Auth::user()->company;
        $data = DB::select(
            "SELECT W.id
        ,W.warning_no
        ,W.warning_date
        ,W.follow_date
        ,W.emplid
        ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
        ,E.POSITIONNAME[position]
        ,E.DEPARTMENTNAME[department]
        ,E.DIVISIONCODE[divisioncode]
        ,E.DIVISIONNAME[division]
        ,w.corgroup_id
        ,CG.corgroup_description
        ,W.penalty_qty
        ,E.COMPANYNAME[company]
        ,W.remark
        ,W.penalty_start
        ,W.penalty_end
        ,W.status
        ,Ws.description
        ,W.expiry_date
        ,W.created_by
        ,SM.remark [REJECTREMARK]
        
        FROM warning_table W
        JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON W.emplid = E.CODEMPID
        JOIN corgroup CG ON W.CORGROUP_ID = CG.corgroup_id 
        JOIN warning_status ws ON W.status = WS.id
        LEFT JOIN sentmail SM ON W.warning_no = SM.warning_no AND W.status = SM.status
        and sm.remark != ''
        WHERE W.warning_company = ?
        
        ORDER BY id DESC ",
            [$usercompany]
        );
        return response()->json($data);
    }

    public function corgroup()
    {
        $data = DB::select('SELECT * FROM corgroup where active = 1 ORDER BY corgroup_id ASC');
        return response()->json($data);
    }

    public function allcorgroup()
    {
        $data = DB::select('SELECT * FROM corgroup  ORDER BY corgroup_id ASC');
        return response()->json($data);
    }

    public function cor($request)
    {

        $data = DB::select('SELECT * FROM cor WHERE corgroup_id = ? and active = 1 ORDER BY cor_id ASC', [$request]);
        return response()->json($data);
    }

    public function penalty()
    {
        $data = DB::select('SELECT * from Penalty where active = 1 ORDER BY Penalty_ID ASC');
        return response()->json($data);
    }

    public function corid(REQUEST $request)
    {
        // return $request->data;
        $data = DB::select('SELECT  c.corgroup_id,cd.cor_id,c.cor_description FROM cor_data cd
        JOIN cor c on cd.cor_id = c.cor_id and cd.corgroup_id = c.corgroup_id
        WHERE warning_no = ?', [$request->data]);
        return response()->json($data);
    }

    public function penaltyid(REQUEST $request)
    {
        // return $request->data;
        $data = DB::select('SELECT pp.penalty_id,pp.penalty_description FROM penalty_data pd
        JOIN penalty pp on pd.penalty_id = pp.penalty_id
        WHERE warning_no = ?', [$request->data]);
        return response()->json($data);
    }

    public function penaltyqty($emplid, $corgroup_id)
    {
        $data = DB::select(

            'SELECT 

            isnull(count(wt.penalty_qty),0)
            
             [penalty_qty]
            
            FROM warning_table WT
            WHERE emplid = ?
            AND corgroup_id = ?
            and wt.expiry_date > GETDATE() ',
            [$emplid, $corgroup_id]
        );

        // 'SELECT 

        // CASE WHEN GETDATE() < WT.expiry_date THEN isnull(MAX(wt.penalty_qty),0)
        // ELSE 0 END
        //  [penalty_qty]

        // FROM warning_table WT
        // WHERE emplid = ?
        // AND corgroup_id = ?

        // group by wt.expiry_date',[$emplid,$corgroup_id] );


        // 'SELECT CASE WHEN CG.cutoff = 0  THEN
        // CASE WHEN DATEDIFF (DAY,WT.created_at,GETDATE()) > 30 THEN 0
        // ELSE isnull(WT.penalty_qty,0) END
        // WHEN CG.cutoff = 1 THEN
        //     CASE WHEN DATEDIFF (DAY,WT.created_at,GETDATE()) > 365 THEN 0
        //     ELSE isnull(WT.penalty_qty,0) END
        // END [penalty_qty]

        // FROM warning_table WT

        // JOIN (
        // SELECT MAX(WT.created_at)[created_at]
        // FROM warning_table WT
        // WHERE emplid = ?
        // AND corgroup_id = ?
        // )MAXX ON MAXX.created_at = WT.created_at
        // JOIN corgroup CG ON CG.corgroup_id = WT.corgroup_id',[$emplid,$corgroup_id] );

        // return $data;
        if (!$data) {

            $data = array(["penalty_qty" => "0"]);
            return response()->json($data);
        } else {

            return response()->json($data);
        }
    }

    public function maxwarningno($company)
    {
        $data = DB::select(
            'SELECT ISNULL (MAX(warning_no),0)[warning_no]
            ,ISNULL (MAX(warning_no),0)+1[next_no]
            FROM warning_seq
            WHERE company = ?
            AND MONTH = MONTH(GETDATE())
            AND YEAR = YEAR(GETDATE())',
            [$company]
        );

        return response()->json($data);
    }

    public function getedit($warning_no)
    {
        $result = $this->warn->geteditdata($warning_no);
        return view('pages.edit')->with($result);
    }

    public function email(Request $request)
    {
        $result = DB::select(
            'SELECT warning_no,level_approved,email,sh.type,sh.status,ss.status_description
            FROM sentmail sh
            LEFT JOIN sentmail_status ss on sh.status = ss.status
            WHERE warning_no = ?
            ORDER BY SH.type DESC ,SH.level_approved ASC ',
            [$request["warning_no"]]
        );
        return $result;
    }

    public function reason(Request $request)
    {
        $result = DB::table('sentmail')->where('warning_no', $request->warning_no)->where('status', 5)->value('remark');
        if ($result == null) {
            return response()->json(0);
        } else {
            return response()->json($result);
        }
        return response()->json($result);
    }

    public function division()
    {
        $result = DB::connection('sqlsrv2')->select(
            "SELECT D.DIVISIONCODE[divisioncode]
            ,D.DIVISIONNAME[divisionname]
            ,CONVERT (NVARCHAR(100),D.DIVISIONCODE)+' - '+D.DIVISIONNAME+' - '+C.COMPANYNAME[full]
            FROM DIVISION D
            JOIN COMPANY C ON C.COMPANYCODE = D.CODCOMPY
            ORDER BY D.DIVISIONCODE ASC"
        );
        return $result;
    }

    public function test(Request $request)
    {
        return $request;
    }
}
