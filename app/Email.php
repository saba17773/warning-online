<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Crypt;
use App\Warn;
use DB;

class Email extends Model
{
    public function send($req)
    {


        //Check Status
        $check = DB::table('sentmail')->where('warning_no', $req->warning_no)
            ->where('type', $req->type)->where('level_approved', $req->level)->value('status');



        if ($check == 0 | $check == 1) {
            $body = $this->getBody($req);


            $mail = $this->phpmail($req->email, 'คำร้องใบตักเตือนออนไลน์', $body);
            if ($mail["result"] == true) {
                DB::table('sentmail')->where('warning_no', $req->warning_no)->where('type', $req->type)
                    ->where('level_approved', $req->level)->update(['status' => 1]);
                return $mail;
            } else {
                return $mail;
            }
        } else {
            return 'This user is already approved or canceled, We shoud not be here . Something must be broken ._.';
        }
    }

    public function sendnext($warning_no, $email, $level, $type)
    {
        //Check Status

        $check = DB::table('sentmail')->where('warning_no', $warning_no)
            ->where('type', $type)->where('level_approved', $level)->value('status');
        if ($check == 0 | $check == 1) {
            $body = $this->getBodynext($warning_no, $email, $level, $type);

            $mail = $this->phpmail($email, 'คำร้องใบตักเตือนออนไลน์', $body);
            if ($mail["result"] == true) {
                DB::table('sentmail')->where('warning_no', $warning_no)->where('type', $type)
                    ->where('level_approved', $level)->update(['status' => 1]);
                return $mail;
            } else {
                return $mail;
            }
        } else {
            return 'This user is already approved or canceled, We shoud not be here . Something must be broken ._.';
        }
    }

    public function getBody($req)
    {

        $name = DB::table('users')->where('email', $req->email)->value('name');
        if ($req->type == 'hr') {
            $text =  'อนุมัติ';
            $text2 = 'ไม่อนุมัติ';
        } else {
            $text =  'เห็นชอบ';
            $text2 = 'ไม่เห็นชอบ';
        }

        $data = DB::select(
            "SELECT Convert(varchar(10),CONVERT(date,WT.warning_date,106),103)+' '+convert(nvarchar(5),convert(time,wt.warning_date,108))[warning_date]
            ,Convert(varchar(10),CONVERT(date,WT.follow_date,106),103)+' '+convert(nvarchar(5),convert(time,wt.follow_date,108))[follow_date]
            
            ,WT.emplid
            ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
            ,E.POSITIONNAME[position]
            ,E.DEPARTMENTNAME[department]
            ,E.DIVISIONCODE[divisioncode]
            ,E.DIVISIONNAME[division]
            ,WT.corgroup_id 
            ,CG.corgroup_description
            ,WT.Remark
            FROM warning_table WT
            JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON WT.emplid = E.CODEMPID
            JOIN corgroup CG ON WT.corgroup_id = CG.corgroup_id
            WHERE warning_no = ?",
            [$req->warning_no]
        );

        $data2 = json_decode(json_encode($data), true);
        $encrypt = Crypt::encrypt(['warning_no' => $req->warning_no, 'email' => $req->email, 'level' => $req->level, 'type' => $req->type, 'text' => $text, 'text2' => $text2]);
        $body = 'เรียน ' . $name .
            '<br>เรื่อง ใบเตือน <br> <p>เพื่อพิจารณาการกระทำความผิดของพนักงาน</p><br>มีรายละเอียดดังนี้
                <br><table>
                <tr bgcolor=lightblue><td>Warning No.</td><td>วันที่กระทำผิด</td><td>วันที่ตักเตือน</td><td>รหัสพนักงาน</td><td>ชื่อ - สกุล</td>
                <td>ตำแหน่ง</td><td>ฝ่าย</td><td>แผนก</td><td>สาเหตุการกระทำความผิด</td></tr>
                <tr><td>' . $req->warning_no . '</td><td>' . $data2[0]['warning_date'] . '</td><td>' . $data2[0]['follow_date'] . '</td>
                <td>' . $data2[0]['emplid'] . '</td><td>' . $data2[0]['emplname'] . '</td><td>' . $data2[0]['position'] . '</td>
                <td>' . $data2[0]['division'] . '</td><td>' . $data2[0]['department'] . '</td><td>' . $data2[0]['corgroup_description'] . '</td>
                </tr>
                </table><br>Remark : ' . $data2[0]['Remark'] . '<br><br>จึงเรียนมาเพื่อทราบและพิจารณา<br> ดูรายละเอียดคำร้อง >>  
                <a href="http://192.168.111.143:8899/email/detail/' . $encrypt . '"> Detail </a>';
        return $body;
    }

    public function getBodynext($warning_no, $email, $level, $type)
    {

        $name = DB::table('users')->where('email', $email)->value('name');
        if ($type == 'hr') {
            $text =  'อนุมัติ';
            $text2 = 'ไม่อนุมัติ';
        } else {
            $text =  'เห็นชอบ';
            $text2 = 'ไม่เห็นชอบ';
        }

        $data = DB::select(
            "SELECT Convert(varchar(10),CONVERT(date,WT.warning_date,106),103)+' '+convert(nvarchar(5),convert(time,wt.warning_date,108))[warning_date]
            ,Convert(varchar(10),CONVERT(date,WT.follow_date,106),103)+' '+convert(nvarchar(5),convert(time,wt.follow_date,108))[follow_date]
            
            ,WT.emplid
            ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
            ,E.POSITIONNAME[position]
            ,E.DEPARTMENTNAME[department]
            ,E.DIVISIONCODE[divisioncode]
            ,E.DIVISIONNAME[division]
            ,WT.corgroup_id 
            ,CG.corgroup_description
            ,WT.Remark
            FROM warning_table WT
            JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON WT.emplid = E.CODEMPID
            JOIN corgroup CG ON WT.corgroup_id = CG.corgroup_id
            WHERE warning_no = ?",
            [$warning_no]
        );

        $data2 = json_decode(json_encode($data), true);
        $encrypt = Crypt::encrypt(['warning_no' => $warning_no, 'email' => $email, 'level' => $level, 'type' => $type, 'text' => $text, 'text2' => $text2]);
        $body = 'เรียน ' . $name .
            '<br>เรื่อง ใบเตือน <br> <p>เพื่อพิจารณาการกระทำความผิดของพนักงาน</p><br>มีรายละเอียดดังนี้
                <br><table>
                <tr bgcolor=lightblue><td>Warning No.</td><td>วันที่กระทำผิด</td><td>วันที่ตักเตือน</td><td>รหัสพนักงาน</td><td>ชื่อ - สกุล</td>
                <td>ตำแหน่ง</td><td>ฝ่าย</td><td>แผนก</td><td>สาเหตุการกระทำความผิด</td></tr>
                <tr><td>' . $warning_no . '</td><td>' . $data2[0]['warning_date'] . '</td><td>' . $data2[0]['follow_date'] . '</td>
                <td>' . $data2[0]['emplid'] . '</td><td>' . $data2[0]['emplname'] . '</td><td>' . $data2[0]['position'] . '</td>
                <td>' . $data2[0]['division'] . '</td><td>' . $data2[0]['department'] . '</td><td>' . $data2[0]['corgroup_description'] . '</td>
                </tr>
                </table><br>Remark : ' . $data2[0]['Remark'] . '<br><br>จึงเรียนมาเพื่อทราบและพิจารณา<br> ดูรายละเอียดคำร้อง >>  
                <a href="http://192.168.111.143:8899/email/detail/' . $encrypt . '"> Detail </a>';
        return $body;
    }

    public function send_complete($warning_no)
    {
        $check = DB::table('sentmail')->where('warning_no', $warning_no)->get();

        $data = json_decode(json_encode($check), true);
        foreach ($data as $data2) {

            $body = $this->getBody_complete($warning_no, $data2["email"]);
            $mail = $this->phpmail($data2["email"], 'คำร้องใบตักเตือนออนไลน์:เสร็จสิ้น', $body);
        }

        $creator = DB::table('warning_table')->where('warning_no', $warning_no)->get(['created_by', 'updated_by']);
        $creator = json_decode(json_encode($creator[0]), true);
        if (!$creator["updated_by"]) {
            $finaltor = $creator["created_by"];
        } else {
            $finaltor = $creator["updated_by"];
        }
        $datafinaltor = DB::table('users')->where('userid', $finaltor)->get(['email', 'name']);
        $datafinaltor = json_decode(json_encode($datafinaltor[0]), true);

        $body = $this->getBody_complete($warning_no, $datafinaltor["email"]);
        $mail = $this->phpmail($datafinaltor["email"], 'คำร้องใบตักเตือนออนไลน์:เสร็จสิ้น', $body);

        // return $check;
    }

    public function getBody_complete($warning_no, $email)
    {
        // $name = DB::table('empl')->where('email',$email)->value('emplname');

        $data = DB::select(
            "SELECT Convert(varchar(10),CONVERT(date,WT.warning_date,106),103)+' '+convert(nvarchar(5),convert(time,wt.warning_date,108))[warning_date]
            ,Convert(varchar(10),CONVERT(date,WT.follow_date,106),103)+' '+convert(nvarchar(5),convert(time,wt.follow_date,108))[follow_date]
            
            ,WT.emplid
            ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
            ,E.POSITIONNAME[position]
            ,E.DEPARTMENTNAME[department]
            ,E.DIVISIONCODE[divisioncode]
            ,E.DIVISIONNAME[division]
            ,WT.corgroup_id 
            ,CG.corgroup_description
            FROM warning_table WT
            JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON WT.emplid = E.CODEMPID
            JOIN corgroup CG ON WT.corgroup_id = CG.corgroup_id
            WHERE warning_no = ?",
            [$warning_no]
        );
        $data2 = json_decode(json_encode($data), true);

        $body = 'เรียนทุกท่าน 
                <br>เรื่อง แจ้งผลอนุมัติการลงโทษทางวินัย <br> 
                <br><table>
                <tr bgcolor=lightblue><td>Warning No.</td><td>วันที่กระทำผิด</td><td>วันที่ตักเตือน</td><td>รหัสพนักงาน</td><td>ชื่อ - สกุล</td>
                <td>ตำแหน่ง</td><td>ฝ่าย</td><td>สาเหตุการกระทำความผิด</td></tr>
                <tr><td>' . $warning_no . '</td><td>' . $data2[0]['warning_date'] . '</td><td>' . $data2[0]['follow_date'] . '</td>
                <td>' . $data2[0]['emplid'] . '</td><td>' . $data2[0]['emplname'] . '</td><td>' . $data2[0]['position'] . '</td>
                <td>' . $data2[0]['department'] . '</td><td>' . $data2[0]['corgroup_description'] . '</td>
                </tr>
                </table><br>ได้อนุมัติเรียบร้อยแล้ว<br>จึงเรียนมาเพื่อทราบ<br> ดูรายละเอียดใบเตือน >>  
                <a href="http://192.168.111.143:8899/warns/report/' . $warning_no . '"> Detail </a>';
        return $body;
    }

    public function detail($encrypt)
    {
        $dec = Crypt::decrypt($encrypt);
        $data = DB::select(
            "SELECT w.warning_no
            ,w.warning_date
            ,w.follow_date
            ,w.emplid
            ,w.corgroup_id
            ,w.penalty_qty
            ,isnull(convert (nvarchar(50),w.penalty_start),null)[penalty_start]
            ,isnull(convert (nvarchar(50),w.penalty_end),null)[penalty_end]
            ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
            ,E.POSITIONNAME[position]
            ,E.DEPARTMENTNAME[department]
            ,E.COMPANYNAME[company]
            ,E.DIVISIONNAME[division]
            ,E.DIVISIONCODE[divisioncode]
            ,w.remark
            FROM warning_table w
            JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON W.emplid = E.CODEMPID
            WHERE w.warning_no = ?",
            [$dec["warning_no"]]
        );
        $array = json_decode(json_encode($data[0]), true) + $dec;
        return $array;
    }


    public function approved($request)
    {

        $check = DB::table('sentmail')->where('warning_no', $request->warning_no)
            ->where('email', $request->email)->where('type', $request->type)
            ->where('level_approved', $request->level)->get();
        $check = json_decode(json_encode($check), true);

        if (count($check) == 0) {
            return ['result' => false, 'message' => 'ไม่พบ Email ใน sentmail table.', 'errorcode' => '#' . $request->warning_no . '#' . $request->email . '#' . $request->type . $request->level];
        }

        $date = date("Y-m-d H:i:s");
        if ($check[0]['status'] == 2 or $check[0]['status'] == 5) {
            return ['result' => false, 'message' => 'ใบเตือนนี้ได้ถูกอนุมัติ/ปฏิเสธไปแล้ว.', 'errorcode' => '#' . $request->warning_no . '#' . $request->email . '#' . $request->type . $request->level];
        }

        if ($check[0]['status'] == 1) {

            //อัพเดตสถานะ sentmail เป็น Approved
            DB::table('sentmail')->where('warning_no', $request->warning_no)->where('email', $request->email)
                ->where('type', $request->type)->where('level_approved', $request->level)->update(['status' => 2, 'approved_date' => $date]);
            //อัพเดตสถานะ warning_table เป็น 1 (อยู่ระหว่างการตรวจสอบ)
            DB::table('warning_table')->where('warning_no', $request->warning_no)->update(['status' => 1]);

            if ($check[0]["type"] == 'user') {
                $checknext = DB::table('sentmail')->where('warning_no', $request->warning_no)->where('type', 'user')
                    ->where('level_approved', $request->level + 1)->get();
                $checknext = json_decode(json_encode($checknext), true);

                if (count($checknext) != 0) {
                    DB::table('sentmail')->where('warning_no', $checknext[0]["warning_no"])->where('email', $checknext[0]["email"])
                        ->where('type', $checknext[0]["type"])->where('level_approved', $checknext[0]["level_approved"])->update(['status' => 0]);

                    $mail = $this->sendnext($checknext[0]["warning_no"], $checknext[0]["email"], $checknext[0]["level_approved"], $checknext[0]["type"]);

                    return ['result' => true, 'message' => 'อนุมัติ ' . $request->warning_no . ' เรียบร้อยแล้ว'];
                } else {

                    $hr = DB::table('sentmail')->where('warning_no', $request->warning_no)->where('type', 'hr')
                        ->where('level_approved', 1)->get();
                    $hr = json_decode(json_encode($hr), true);

                    DB::table('sentmail')->where('warning_no', $hr[0]["warning_no"])->where('email', $hr[0]["email"])
                        ->where('type', $hr[0]["type"])->where('level_approved', $hr[0]["level_approved"])->update(['status' => 0]);
                    $mail = $this->sendnext($hr[0]["warning_no"], $hr[0]["email"], $hr[0]["level_approved"], $hr[0]["type"]);
                    //อัพเดตสถานะ warning_table เป็น 2 (ตรวจสอบเรียบร้อย)
                    DB::table('warning_table')->where('warning_no', $request->warning_no)->update(['status' => 2]);
                    return ['result' => true, 'message' => 'อนุมัติ ' . $request->warning_no . ' เรียบร้อยแล้ว'];
                }
            } else {
                $checknext = DB::table('sentmail')->where('warning_no', $request->warning_no)->where('type', 'hr')
                    ->where('level_approved', $request->level + 1)->get();
                $checknext = json_decode(json_encode($checknext), true);
                if (count($checknext) != 0) {
                    DB::table('sentmail')->where('warning_no', $checknext[0]["warning_no"])->where('email', $checknext[0]["email"])
                        ->where('type', $checknext[0]["type"])->where('level_approved', $checknext[0]["level_approved"])->update(['status' => 1]);
                    $mail = $this->sendnext($checknext[0]["warning_no"], $checknext[0]["email"], $checknext[0]["level_approved"], $checknext[0]["type"]);

                    //อัพเดตสถานะ warning_table เป็น 3 (ระหว่างการอนุมัติ)
                    DB::table('warning_table')->where('warning_no', $request->warning_no)->update(['status' => 3]);
                    return ['result' => true, 'message' => 'อนุมัติ ' . $request->warning_no . ' เรียบร้อยแล้ว'];
                } else {

                    $mail = $this->send_complete($request->warning_no);
                    DB::table('warning_table')->where('warning_no', $request->warning_no)->update(['status' => 4, 'updated_at' => $date]);

                    //อัพเดตสถานะ warning_table เป็น 4 (อนุมัติเรียบร้อย)

                    $warndata = DB::table('warning_table')->where('warning_no', $request->warning_no)->first();
                    $warndata = json_decode(json_encode($warndata), true);
                    // return $warndata['corgroup_id'];


                    $data = DB::select(
                        'SELECT 

                                isnull(count(wt.penalty_qty),0)
                                
                                [penalty_qty]
                                
                            FROM warning_table WT
                            WHERE emplid = ?
                            AND corgroup_id = ?
                            and wt.status = 4
                            and wt.expiry_date > GETDATE() ',
                        [$warndata['emplid'], $warndata['corgroup_id']]
                    );
                    // 'SELECT 

                    // CASE WHEN GETDATE() < WT.expiry_date THEN MAX(wt.penalty_qty) END
                    //  [penalty_qty]

                    // FROM warning_table WT
                    // WHERE emplid = ?
                    // AND corgroup_id = ?
                    // and wt.status = 4
                    // group by wt.expiry_date',[$warndata['emplid'],$warndata['corgroup_id']] );

                    $data = json_decode(json_encode($data), true);

                    if (!$data[0]['penalty_qty']) {

                        $penalty_qty = 1;
                        // return $penalty_qty;
                        DB::table('warning_table')->where('warning_no', $request->warning_no)->update(['status' => 4, 'penalty_qty' => $penalty_qty]);
                        return ['result' => 'report', 'message' => $request->warning_no . ' เสร็จสิ้นแล้ว', 'errorcode' => 'คุณสามารถปริ้นรายงานได้ในหน้าถัดไป'];
                    } else {

                        $penalty_qty = $data[0]['penalty_qty'] + 1;
                        // return $penalty_qty; 
                        DB::table('warning_table')->where('warning_no', $request->warning_no)->update(['status' => 4, 'penalty_qty' => $penalty_qty]);
                        return ['result' => 'report', 'message' => $request->warning_no . ' เสร็จสิ้นแล้ว', 'errorcode' => 'คุณสามารถปริ้นรายงานได้ในหน้าถัดไป'];
                    }
                }
            }
        } else {
            return ['result' => false, 'message' => 'สถานะไม่ถูกต้อง', 'errorcode' => '#' . $request->warning_no . '#' . $request->email . '#' . $request->type . $request->level];
        }
    }

    public function decline($request)
    {

        $check = DB::table('sentmail')->where('warning_no', $request->warning_no)
            ->where('email', $request->email)->where('type', $request->type)
            ->where('level_approved', $request->level)->value('status');
        if ($check == null) {
            return ['result' => false, 'message' => 'ไม่พบ Email ใน sentmail table.', 'errorcode' => '#' . $request->warning_no . '#' . $request->email . '#' . $request->type . $request->level];
        }

        if ($check == 1) {
            try {

                $creator = DB::table('warning_table')->where('warning_no', $request->warning_no)->get(['created_by', 'updated_by']);
                $creator = json_decode(json_encode($creator[0]), true);
                if (!$creator["updated_by"]) {
                    $finaltor = $creator["created_by"];
                } else {
                    $finaltor = $creator["updated_by"];
                }
                $datafinaltor = DB::table('users')->where('userid', $finaltor)->get(['email', 'name']);
                $datafinaltor = json_decode(json_encode($datafinaltor[0]), true);

                $subject = "คำร้องใบตักเตือนออนไลน์:ปฏิเสธ";


                $data = DB::select(
                    "SELECT Convert(varchar(10),CONVERT(date,WT.warning_date,106),103)[warning_date]
                    ,Convert(varchar(10),CONVERT(date,WT.follow_date,106),103)[follow_date]
                    ,WT.emplid
                    ,ISNULL(E.EMPNAME,'') +' '+ ISNULL(E.EMPLASTNAME,'')[emplname]
                    ,E.POSITIONNAME[position]
                    ,E.DEPARTMENTNAME[department]
                    ,E.DIVISIONCODE[divisioncode]
                    ,E.DIVISIONNAME[division]
                    ,WT.corgroup_id 
                    ,CG.corgroup_description
                    ,WT.Remark
                    FROM warning_table WT
                    JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON WT.emplid = E.CODEMPID
                    JOIN corgroup CG ON WT.corgroup_id = CG.corgroup_id
                    WHERE warning_no = ?",
                    [$request->warning_no]
                );


                $data2 = json_decode(json_encode($data), true);


                $body = 'เรียน ' . $datafinaltor["name"] .
                    '<br>เรื่อง ทบทวนการลงโทษกระทำความผิดของพนักงาน <br> <p>เพื่อพิจารณาทบทวนการลงโทษกระทำความผิดของพนักงาน</p><br>มีรายละเอียดดังนี้
                        <br><table>
                        <tr bgcolor=lightblue><td>Warning No.</td><td>วันที่กระทำผิด</td><td>วันที่ตักเตือน</td><td>รหัสพนักงาน</td><td>ชื่อ - สกุล</td>
                        <td>ตำแหน่ง</td><td>ฝ่าย</td><td>แผนก</td><td>สาเหตุการกระทำความผิด</td></tr>
                        <tr><td>' . $request->warning_no . '</td><td>' . $data2[0]['warning_date'] . '</td><td>' . $data2[0]['follow_date'] . '</td>
                        <td>' . $data2[0]['emplid'] . '</td><td>' . $data2[0]['emplname'] . '</td><td>' . $data2[0]['position'] . '</td>
                        <td>' . $data2[0]['division'] . '</td><td>' . $data2[0]['department'] . '</td><td>' . $data2[0]['corgroup_description'] . '</td></tr>
                        </table><br>Remark : ' . $data2[0]['Remark'] . '<br>เหตุผลที่ไม่เห็นด้วย : ' . $request->remark . '<br><br>จึงเรียนมาเพื่อทราบและพิจารณา<br>
                        หากต้องการดูรายละเอียดใบเตือน คลิก >>>>
                        <a href="http://192.168.111.143:8899/warns/email/' . $request->warning_no . '"> Detail </a>';

                //sentmail to creator
                $this->phpmail($datafinaltor["email"], $subject, $body);

                //update status to 5 (ไม่เห็นด้วย)
                DB::table('sentmail')->where('warning_no', $request->warning_no)->where('email', $request->email)->where('level_approved', $request->level)
                    ->where('type', $request->type)->update(['status' => 5, 'remark' => $request->remark]);

                DB::table('warning_table')->where('warning_no', $request->warning_no)->update(['status' => 5]);
                return ['result' => true, 'message' => 1];
            } catch (\Exception $th) {

                return ['result' => false, 'message' => $th];
            }
        } elseif ($check == 2 or $check == 5) {
            return ['result' => false, 'message' => 'ใบเตือนนี้ได้ถูกอนุมัติ/ปฏิเสธไปแล้ว.', 'errorcode' => '#' . $request->warning_no . '#' . $request->email . '#' . $request->type . $request->level];
        } else {
            return ['result' => false, 'message' => 'สถานะไม่ถูกต้อง', 'errorcode' => '#' . $request->warning_no . '#' . $request->email . '#' . $request->type . $request->level];
        }
    }

    public function phpmail($to, $subject, $body)
    {
        try {
            // $mail->SMTPDebug = 2;
            $mail = new PHPMailer(true);
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'idc.deestone.com';  // Specify main and backup SMTP servers
            // $mail->Host = '127.0.0.1';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'webadministrator@deestone.com';                 // SMTP username
            $mail->Password = 'W@dmIn$02587';                           // SMTP password
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPOptions = array('ssl' => array(
                'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true
            )); // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;
            $mail->CharSet = "utf-8";
            $mail->From = 'no_reply@deestone.com';
            $mail->FromName = 'ระบบใบเตือนออนไลน์';
            $mail->Sender = 'webadministrator@deestone.com';
            $mail->addAddress($to);
            // $mail->addCC('orapan_m@deestone.com');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            return ['result' => true];
        } catch (\Exception $e) {
            report($e);
            return ['result' => false, 'message' => $e->errorMessage()];
        }
    }
    public function sendlostpass($req)
    {
        $result = DB::table('users')->where('email', $req->email)->value('password');
        $subject = "รหัสผ่านระบบใบเตือนออนไลน์";
        $body = "รหัสผ่านระบบใบเตือนออนไลน์ของคุณคือ<br><br>"
            . $result . "<br><br>หากพบปัญหา กรุณาแจ้งทีม IT";
        try {
            $this->phpmail($req->email, $subject, $body);
        } catch (\Throwable $th) {
            return $th;
        }

        return $body;
    }
}
