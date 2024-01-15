<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Email;
use datetime;

class Warn extends Model
{
    public function __construct()
    {

        $this->Email = new Email; // << ใช้งาน Model
    }

    public function insert($request)
    {


        //check type;

        $checktime = DateTime::createFromFormat('m-d-Y H:i:s', $request->warning_date);
        $dob_new = $checktime->format('Y-m-d');
        $checkType = DB::select(
            "SELECT * FROM warning_table WHERE 
             corgroup_id = ? AND emplid = ? AND convert(date,warning_date) = ? AND [status] <> 5",
            [$request["corgroup_id"], $request["emplid"], $dob_new]
        );

        if ($checkType) {
            return ['result' => false, 'message' => 'มีการบันทึกแล้วที่รายการ : ' . $checkType[0]->warning_no];
        }
        //return $request;
        $emailboss = DB::select(
            "SELECT email,level_approved from email_user t 
            WHERE divisioncode = ? AND active = 1",
            [$request["divisioncode"]]
        );

        if (!$emailboss) {
            return ['result' => false, 'message' => 'ไม่พบ Email หัวหน้างานสำหรับ Division ' . $request["divisioncode"] . ' กรุณาเพิ่ม Email หัวหน้างาน'];
        }

        $emailhr = DB::select(
            "SELECT email,level_approved from email_hr t
            WHERE company = ? AND active = 1",
            [$request["company"]]
        );

        if (!$emailhr) {
            return ['result' => false, 'message' => 'ไม่พบ Email HR สำหรับ Company ' . $request["company"] . ' กรุณาเพิ่ม Email HR '];
        }



        //convert thai-time to sql time

        // $warningvalue = substr($request->warning_date, 0, strpos($request->warning_date, '('));
        // $finalwarning_date = date('Y-m-d h:i:s', $request->warning_date);

        // $followvalue = substr($request->follow_date, 0, strpos($request->follow_date, '('));
        // $finalfollow_date = date('Y-m-d h:i:s', strtotime($followvalue));

        if ($request->penalty_start == "Invalid date") {
            $finalpenalty_start = null;
        } else {
            $finalpenalty_start = $request->penalty_start;
        }

        if ($request->penalty_end == "Invalid date") {
            $finalpenalty_end = null;
        } else {
            $finalpenalty_end = $request->penalty_end;
        }

        //
        $datetime = date("Y-m-d H:i:s");
        $cutoff = DB::table('corgroup')->where('corgroup_id', $request->corgroup_id)->value('cutoff');

        if ($cutoff == 0) {
            $addexp = 30;
        } elseif ($cutoff == 1) {
            $addexp = 365;
        }
        //$expire = date('Y-m-d', strtotime("+".$addexp." days"));
        //$expire = date('Y-m-d', strtotime($request->warning_date."+".$addexp." days"));

        $date = DateTime::createFromFormat('m-d-Y H:i:s', $request->warning_date);
        $dob_new = $date->format('Y-m-d');
        $expire = date('Y-m-d', strtotime($dob_new . "+" . $addexp . " days"));

        $data = DB::select(
            "SELECT ISNULL (MAX(warning_no),0)+1[next_no]
            FROM warning_seq
            WHERE company = ?
            AND MONTH = MONTH(GETDATE())
            AND YEAR = YEAR(GETDATE())",
            [$request["logincompany"]]
        );

        //Converting to COMPANY.MONTH.WARNING_NO.YEAR
        //Fix to Company.Year.Month.Warning_No
        $str = $request["logincompany"] . "." . date("y") . "." . date("m") . "." . sprintf("%'04d", $data[0]->next_no);



        try {
            DB::table('warning_table')->insert(
                [
                    'Warning_no' => $str, 'warning_date' => $request->warning_date, 'follow_date' => $request->follow_date, 'created_at' => $datetime, 'created_by' => $request->loginuser, 'updated_at' => $datetime, 'emplid' => $request->emplid, 'corgroup_id' => $request->corgroup_id
                    // ,'penalty_qty' => $request->penalty_qty
                    , 'remark' => $request->remark, 'penalty_start' => $finalpenalty_start, 'penalty_end' => $finalpenalty_end, 'status' => '0', 'expiry_date' =>  $expire, 'warning_company' => $request["logincompany"]
                ]
            );

            //INSERT sentemail_user
            $emailboss = DB::select(
                "SELECT email,level_approved from email_user t 
                WHERE divisioncode = ? AND active = 1",
                [$request["divisioncode"]]
            );

            foreach ($emailboss as $boss) {
                if ($boss->level_approved == 1) {
                    DB::table('sentmail')->insert(
                        [
                            'level_approved' => $boss->level_approved, 'warning_no' => $str, 'email' => $boss->email, 'type' => 'user', 'status' => 0
                        ]
                    );
                } else {
                    DB::table('sentmail')->insert(
                        [
                            'level_approved' => $boss->level_approved, 'warning_no' => $str, 'email' => $boss->email, 'type' => 'user', 'status' => 99
                        ]
                    );
                }
            }

            //INSERT sentemail_hr
            $emailhr = DB::select(
                "SELECT email,level_approved from email_hr t
                WHERE company = ? AND active = 1",
                [$request["company"]]
            );

            foreach ($emailhr as $boss) {
                DB::table('sentmail')->insert(
                    [
                        'level_approved' => $boss->level_approved, 'warning_no' => $str, 'email' => $boss->email, 'type' => 'hr', 'status' => 99
                    ]
                );
            }

            //INSERT warning_seq
            DB::insert(
                "INSERT INTO warning_seq 
                (company,year,month,warning_no,create_by,create_date)
                VALUES (?,YEAR(GETDATE()),MONTH(GETDATE()),?,?,GETDATE())",
                [$request["logincompany"], $data[0]->next_no, $request["loginuser"]]
            );

            //INSERT cor_data
            foreach ($request->cor_id as $cor_id) {
                DB::table('cor_data')->insert(
                    [
                        'warning_no' => $str, 'corgroup_id' => $request->corgroup_id, 'cor_id' => $cor_id
                    ]
                );
            }

            // INSERT penalty_data
            foreach ($request->penalty_id as $penalty_id) {
                DB::insert(
                    "INSERT INTO penalty_data
                    (warning_no,penalty_id)
                    VALUES (?,?)",
                    [$str, $penalty_id]
                );
            }


            // อันใหม่ auto send First Email
            $check = DB::table('email_user')->where('divisioncode', $request->divisioncode)
                ->where('level_approved', 1)->where('active', 1)->value('email');
            return ['result' => true, 'email' => $check, 'warning_no' => $str];
            // End auto send 

        } catch (\Exception $e) {
            report($e);
            return ['result' => false, 'data' => $e->getMessage()];
        }
    }

    public function edit($request)
    {


        $cutoff = DB::table('corgroup')->where('corgroup_id', $request->corgroup_id)->value('cutoff');
        //$createdate = DB::table('warning_table')->where('warning_no', $request->warning_no)->value('created_at');
        //$createdate = DB::table('warning_table')->where('warning_no', $request->warning_no)->value('warning_date');

        if ($cutoff == 0) {
            $addexp = 30;
        } elseif ($cutoff == 1) {
            $addexp = 365;
        }


        $date = DateTime::createFromFormat('m-d-Y H:i:s', $request->warning_date);
        $dob_new = $date->format('Y-m-d');
        $expire = date('Y-m-d', strtotime($dob_new . "+" . $addexp . " days"));

        if ($request->penalty_start == "Invalid date") {
            $finalpenalty_start = null;
        } else {
            $finalpenalty_start = $request->penalty_start;
        }

        if ($request->penalty_end == "Invalid date") {
            $finalpenalty_end = null;
        } else {
            $finalpenalty_end = $request->penalty_end;
        }

        try {
            //Update warning_table
            $data = DB::update(
                "UPDATE warning_table
                    SET emplid = ?
                        ,warning_date = ?
                        ,follow_date = ?
                        ,updated_at = GETDATE()
                        ,updated_by = ?
                        ,corgroup_id = ?
                        ,remark = ?
                        ,penalty_start = ?
                        ,penalty_end = ?
                        -- ,penalty_qty = ?
                        ,expiry_date = ?
                        -- ,status = 0 ** Edit ของเดิมต้อง Status = 0  ของใหม่ ไม่ต้องอัพเดต Status
                    
                    WHERE warning_no = ? ",
                [
                    $request["emplid"], $request["warning_date"], $request["follow_date"], $request["loginuser"], $request["corgroup_id"], $request["remark"], $finalpenalty_start, $finalpenalty_end
                    // ,$request["penalty_qty"]
                    , $expire, $request["warning_no"]
                ]
            );

            if ($data == 0) {
                //ERROR no row
                return ['result' => false, 'data' => '0 row(s) affected'];
            } else {

                //OK Do the rest of process.
                //Update cor_data
                DB::delete(
                    "DELETE from cor_data
                    WHERE warning_no = ?",
                    [$request["warning_no"]]
                );
                foreach ($request->cor_id as $cor_id) {
                    DB::insert(
                        "INSERT INTO cor_data
                        (warning_no,corgroup_id,cor_id)
                        VALUES (?,?,?)",
                        [$request["warning_no"], $request["corgroup_id"], $cor_id]
                    );
                }

                //Update penalty_data
                DB::delete(
                    "DELETE from penalty_data
                    WHERE warning_no = ?",
                    [$request["warning_no"]]
                );
                foreach ($request->penalty_id as $penalty_id) {
                    DB::insert(
                        "INSERT INTO penalty_data
                        (warning_no,penalty_id)
                        VALUES (?,?)",
                        [$request["warning_no"], $penalty_id]
                    );
                }
                //Update sentmail
                // *** ของใหม่ ไม่ต้องนอุมัติใหม่
                //  
                // *** End ของใหม่


                // ***ของเดิม อนุมัติใหม่
                // DB::table('sentmail')->where('warning_no',$request->warning_no)->delete();
                // $emailboss = DB::select(
                //     "SELECT email,level_approved from email_user t
                //     WHERE divisioncode = ?
                //     AND active = 1
                //     ",[$request["divisioncode"]]); 

                //     foreach ($emailboss as $boss) {
                //         if ($boss->level_approved == 1) {
                //             DB::table('sentmail')->insert(
                //                 ['level_approved' => $boss->level_approved
                //                 ,'warning_no' => $request["warning_no"]
                //                 ,'email' => $boss->email
                //                 ,'type' => 'user'
                //                 ,'status' => 0
                //                 ]
                //             );
                //         } else {
                //             DB::table('sentmail')->insert(
                //                 ['level_approved' => $boss->level_approved
                //                 ,'warning_no' => $request["warning_no"]
                //                 ,'email' => $boss->email
                //                 ,'type' => 'user'
                //                 ,'status' => 99
                //                 ]
                //             );
                //         }     
                //     }
                // //UPDATE sentemail_hr
                // $emailhr = DB::select(
                //     "SELECT email,level_approved from email_hr t
                //     WHERE company = ? AND active = 1 ",[$request["company"]]); 

                //     foreach ($emailhr as $boss) {
                //             DB::table('sentmail')->insert(
                //                 ['level_approved' => $boss->level_approved
                //                 ,'warning_no' => $request["warning_no"]
                //                 ,'email' => $boss->email
                //                 ,'type' => 'hr'
                //                 ,'status' => 99
                //                 ]
                //             );
                //         }
                // ***End ของดั้งเดิม
                return ['result' => true];
            }
        } catch (\Exception $e) {
            report($e);
            return ['result' => false, 'data' => $e->getMessage()];
        }
    }

    public function geteditdata($warning_no)
    {
        $data = DB::select(
            "SELECT w.warning_no
            ,w.warning_date
            ,w.follow_date
            ,w.emplid
            ,w.corgroup_id
            ,C.corgroup_description
            ,w.penalty_qty
            ,w.penalty_start[penalty_start]
            ,w.penalty_end[penalty_end]
            ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
            ,E.POSITIONNAME[position]
            ,E.DEPARTMENTNAME[department]
            ,E.COMPANYNAME[company]
            ,E.DIVISIONNAME[division]
            ,E.DIVISIONCODE[divisioncode]
            ,w.remark
            
            FROM warning_table w
            JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON W.emplid = E.CODEMPID 
            JOIN CORGROUP C ON W.CORGROUP_ID = C.CORGROUP_ID
            WHERE w.warning_no = ?",
            [$warning_no]
        );
        $array = json_decode(json_encode($data[0]), true);
        return $array;
    }

    public function trans($request, $status, $str)
    {
        //convert thai-time to sql time
        // return $request;
        // $warningvalue = substr($request->warning_date, 0, strpos($request->warning_date, '('));
        // $finalwarning_date = date('Y-m-d h:i:s', strtotime($warningvalue));

        // $followvalue = substr($request->follow_date, 0, strpos($request->follow_date, '('));
        // $finalfollow_date = date('Y-m-d h:i:s', strtotime($followvalue));


        if ($request->penalty_start == "Invalid date") {
            $finalpenalty_start = null;
        } else {
            $finalpenalty_start = $request->penalty_start;
        }

        if ($request->penalty_end == "Invalid date") {
            $finalpenalty_end = null;
        } else {
            $finalpenalty_end = $request->penalty_end;
        }



        $date = date("Y-m-d");
        $time = date("H:i:s");
        $trueno = 0;

        if ($request->warning_no == null) {
            $trueno = $str;
        } else {
            $trueno = $request->warning_no;
        }


        //Cor_id
        $c = $request->cor_id;
        sort($c);
        $cor_id2 = null;
        foreach ($c as $cor_id) {
            $cor_id2 = $cor_id2 . "," . $cor_id;
        }
        $cor_id = ltrim($cor_id2, ',');

        //Penalty_id
        $p = $request->penalty_id;
        sort($p);
        $penalty_id2 = null;
        foreach ($p as $penalty_id) {
            $penalty_id2 = $penalty_id2 . "," . $penalty_id;
        }
        $penalty_id = ltrim($penalty_id2, ',');



        $test = DB::table('warning_trans')->insert(
            [
                'ref_warning_no' => $trueno, 'warning_date' => $request->warning_date, 'follow_date' => $request->follow_date, 'emplid' => $request->emplid, 'corgroup_id' => $request->corgroup_id, 'cor_id' => $cor_id, 'penalty_id' => $penalty_id, 'penalty_start' => $finalpenalty_start, 'penalty_end' => $finalpenalty_end, 'remark' => $request->remark, 'status' => $status, 'create_date' => $date, 'create_time' => $time, 'create_by' => $request->loginuser
                // ,'approved_by' => ''
                // ,'approved_emplid' => ''
            ]
        );
    }

    public function trans2($request, $status, $str)
    {

        // return $request;
        //convert thai-time to sql time
        // return $request;
        // $warningvalue = substr($request->warning_date, 0, strpos($request->warning_date, '('));
        // $finalwarning_date = date('Y-m-d h:i:s', strtotime($warningvalue));

        // $followvalue = substr($request->follow_date, 0, strpos($request->follow_date, '('));
        // $finalfollow_date = date('Y-m-d h:i:s', strtotime($followvalue));


        if ($request->penalty_start == "Invalid date") {
            $finalpenalty_start = null;
        } else {
            $finalpenalty_start = $request->penalty_start;
        }

        if ($request->penalty_end == "Invalid date") {
            $finalpenalty_end = null;
        } else {
            $finalpenalty_end = $request->penalty_end;
        }



        $date = date("Y-m-d");
        $time = date("H:i:s");
        $trueno = 0;

        if ($request->warning_no == null) {
            $trueno = $str;
        } else {
            $trueno = $request->warning_no;
        }


        //Cor_id
        $c = $request->cor_id;
        sort($c);
        $cor_id2 = null;
        foreach ($c as $cor_id) {
            $cor_id2 = $cor_id2 . "," . $cor_id;
        }
        $cor_id = ltrim($cor_id2, ',');

        //Penalty_id
        $p = $request->penalty_id;
        sort($p);
        $penalty_id2 = null;
        foreach ($p as $penalty_id) {
            $penalty_id2 = $penalty_id2 . "," . $penalty_id;
        }
        $penalty_id = ltrim($penalty_id2, ',');


        try {

            $test = DB::table('warning_trans')->insert(
                [
                    'ref_warning_no' => $trueno, 'warning_date' => $request->warning_date, 'follow_date' => $request->follow_date, 'emplid' => $request->emplid, 'corgroup_id' => $request->corgroup_id, 'cor_id' => $cor_id, 'penalty_id' => $penalty_id, 'penalty_start' => $finalpenalty_start, 'penalty_end' => $finalpenalty_end, 'remark' => $request->remark, 'status' => $status, 'create_date' => $date, 'create_time' => $time, 'create_by' => $request->loginuser
                    // ,'approved_by' => ''
                    // ,'approved_emplid' => ''
                ]
            );
        } catch (\Exception $e) {
            report($e);
            return ['result' => false, 'data' => $e->getMessage()];
        }
    }

    public function checkcomplete($warning_no)
    {

        $check = DB::table('warning_table')->where('warning_no', $warning_no)->value('status');
        if ($check == 4 or $check == 5) {
            return $message = ['result' => 1, 'data' => $warning_no];
        } else {
            return $message = ['result' => 0, 'data' => ' ใบเตือน ' . $warning_no . ' ยังไม่เสร็จสิ้นขั้นตอนการอนุมัติ<br>หากมีข้อสงสัยกรุณาติดต่อ IT'];
        }
    }

    public function report($warning_no)
    {
        $data = DB::select(
            "SELECT w.warning_no
            ,w.warning_date
            ,w.follow_date
            ,w.emplid
            ,w.corgroup_id
            ,CG.corgroup_description
            ,w.penalty_qty
            ,isnull(convert (nvarchar(50),w.penalty_start),null)[penalty_start]
            ,isnull(convert (nvarchar(50),w.penalty_end),null)[penalty_end]
            ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
            ,E.POSITIONNAME[position]
            ,E.DEPARTMENTNAME[department]
            ,E.COMPANYNAME[company]
            ,w.remark
            ,IMG.NAMIMAGE[IMGSRC]
            ,w.expiry_date

            FROM warning_table w
            JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E on w.emplid = E.CODEMPID
            JOIN CORGROUP CG ON W.corgroup_id = CG.corgroup_id
            LEFT JOIN [HRTRAINING_DEV].[dbo].[TEMPIMGE] IMG ON CONVERT(NVARCHAR(20),W.emplid) = IMG.CODEMPID
            WHERE w.warning_no = ?",
            [$warning_no]
        );
        $array = json_decode(json_encode($data[0]), true);
        return $array;
    }

    public function Cancelcase($request)
    {



        //$date = DateTime::createFromFormat('m-d-Y H:i:s', $request->warning_date);
        //$dob_new = $date->format('Y-m-d');

        try {
            //Update warning_table
            $data = DB::update(
                "UPDATE warning_table
                    SET 
                        updated_at = GETDATE()
                        ,updated_by = ?
                        ,[status] = 5
                        ,penalty_qty = NULL
                      
                    WHERE warning_no = ? ",
                [
                    $request->Namecheck, $request->warning_no
                ]
            );

            if ($data == 0) {
                //ERROR no row
                return ['result' => false, 'data' => '0 row(s) affected'];
            } else {

                DB::insert(
                    "INSERT INTO warning_trans  ( [ref_warning_no]
                    ,[warning_date]
                    ,[follow_date]
                    ,[emplid]
                    ,[cardid]
                    ,[corgroup_id]
                    ,[cor_id]
                    ,[penalty_id]
                    ,[penalty_start]
                    ,[penalty_end]
                    ,[remark]
                    ,[company]
                    ,[status]
                    ,[create_date]
                    ,[create_time]
                    ,[create_by]
                    ,[approved_by]
                    ,[approved_emplid])
              SELECT 
              TOP 1
              [ref_warning_no]
                    ,[warning_date]
                    ,[follow_date]
                    ,[emplid]
                    ,[cardid]
                    ,[corgroup_id]
                    ,[cor_id]
                    ,[penalty_id]
                    ,[penalty_start]
                    ,[penalty_end]
                    ,[remark]
                    ,[company]
                    ,5
                    ,[create_date]
                    ,[create_time]
                    ,[create_by]
                    ,[approved_by]
                    ,[approved_emplid]
              
              
               FROM warning_trans WHERE ref_warning_no = ?  ORDER BY id",
                    [$request->warning_no]
                );

                return ['result' => true];
            }
        } catch (\Exception $e) {
            report($e);
            return ['result' => false, 'data' => $e->getMessage()];
        }
    }
}
