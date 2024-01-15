<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public function getcorgroup()
    {
        $data = DB::select("SELECT CG.corgroup_id,CG.corgroup_description,CG.cutoff,CO.cutoff_desc,CG.active
        FROM corgroup CG
        JOIN CUTOFF CO ON CG.cutoff = CO.CUTOFF
        ORDER BY CG.corgroup_id ASC");
        return $data;
    }

    public function getregu()
    {
        $data = DB::select("SELECT CG.corgroup_id,CG.corgroup_description,C.cor_id,C.cor_description,C.active 
        FROM corgroup CG
        JOIN cor C ON CG.corgroup_id = C.corgroup_id
        ORDER BY CG.corgroup_id ASC");
        return $data;
    }

    public function getemail()
    {
        $data = DB::select(
            "SELECT  convert (nvarchar(30),e.divisioncode)[code]
                        ,e.id[id]
                        ,e.divisionname[name]
                        ,e.email
                        ,e.level_approved
                        ,e.active
                        ,c.COMPANYNAME [company]
                FROM email_user e
                JOIN [HRTRAINING_DEV].[dbo].[DIVISION] d on d.DIVISIONCODE = e.divisioncode
                JOIN [HRTRAINING_DEV].[dbo].[COMPANY] c on c.COMPANYCODE = d.CODCOMPY
                union
                SELECT 'HR'
                        ,e.id[id]
                        ,e.company
                        ,e.email
                        ,e.level_approved
                        ,e.active
                        ,e.company
                FROM email_hr e

                ORDER BY Code ASC , name ASC , level_approved ASC"
        );
        return $data;
    }

    public function getuser()
    {
        $data = DB::select(
            "SELECT id,emplid,userid,name,email,company,level,active
                FROM USERS

                ORDER BY id ASC"
        );
        return $data;
    }

    public function getblacklist()
    {
        $data = DB::select(
            "SELECT bt.id,bt.emplid,bt.cardid,bt.blacklist_group,bg.blacklist_description,E.EMPNAME +' '+E.EMPLASTNAME[emplname]
                FROM blacklist_table bt
                
                JOIN blacklist_group bg on bt.blacklist_group = bg.blacklist_group
                JOIN [HRTRAINING_DEV].[dbo].[EMPLOYEE] E ON bt.emplid = E.CODEMPID
                ORDER BY bt.id ASC"
        );
        return $data;
    }

    public function getblacklistgroup()
    {
        $data = DB::select(
            "SELECT BG.blacklist_group
                ,BG.blacklist_description
                ,bg.active
                FROM blacklist_group BG"
        );
        return $data;
    }

    public function addcorgroup($req)
    {
        try {
            DB::table('corgroup')->insert([
                'corgroup_description' => $req->corgroup_desc,
                'cutoff' => $req->cutoff
            ]);
            return ['result' => true];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function addregu($req)
    {

        $maxcorid = DB::table('cor')->where('corgroup_id', $req->corgroup_id)->max('cor_id');
        if (!$maxcorid) {
            $maxcorid = 0;
        }
        try {
            DB::table('cor')->insert([
                'cor_description' => $req->regu_desc,
                'cor_id' => $maxcorid + 1,
                'corgroup_id' => $req->corgroup_id
            ]);
            return ['result' => true];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function addemail($req)
    {
        if ($req->type == 'USER') {
            try {
                $check = DB::table('email_user')->where('divisioncode', $req->code)->where('level_approved', $req->level)->get();

                // if(count($check) != 0){
                //     return ['result' => false ,'message' => 'ระดับการอนุมัติซ้ำ !'];
                // }else{
                DB::table('email_user')->insert([
                    'divisioncode' => $req->code,
                    'divisionname' => $req->name,
                    'email' => $req->email,
                    'level_approved' => $req->level
                ]);
                return ['result' => true];
                // }  
            } catch (\Exception $e) {
                return ['result' => false, 'message' => $e->errorInfo[2]];
            }
        } else {
            try {
                // $check = DB::table('email_hr')->where('company',$req->code)->where('level_approved',$req->level)->get();

                // if(count($check) != 0){
                //     return ['result' => false ,'message' => 'ระดับการอนุมัติซ้ำ !'];
                // }else{
                DB::table('email_hr')->insert([
                    'company' => $req->code,
                    'email' => $req->email,
                    'level_approved' => $req->level
                ]);
                return ['result' => true];
                // }
            } catch (\Exception $e) {
                return ['result' => false, 'message' => $e->errorInfo[2]];
            }
        }
    }
    public function adduser($req)
    {
        try {
            DB::table('users')->insert([
                'emplid' => $req->emplid,
                'userid' => $req->userid,
                'name' => $req->name,
                'email' => $req->email,
                'level' => $req->level,
            ]);
        } catch (\Throwable $th) {
            return ['result' => false, 'message' => $th];
        }
    }

    public function addblacklist($req)
    {
        try {
            DB::table('blacklist_table')->insert([
                'emplid' => $req->emplid,
                'cardid' => $req->cardid,
                'blacklist_group' => $req->blacklist_group
            ]);
            return ['result' => true, 'message' => 'เพิ่ม Blacklist สำเร็จ !'];
        } catch (\Throwable $th) {
            return ['result' => false, 'message' => $th];
        }
    }

    public function addblacklistgroup($req)
    {
        try {
            DB::table('blacklist_group')->insert([
                'blacklist_description' => $req->description,
            ]);
            return ['result' => true, 'message' => 'เพิ่ม Blacklist Group สำเร็จ !'];
        } catch (\Throwable $th) {
            return ['result' => false, 'message' => $th];
        }
    }

    public function editcorgroup($req)
    {
        try {
            DB::table('corgroup')->where('corgroup_id', $req->corgroup_id)->update([
                'corgroup_description' => $req->corgroup_desc,
                'cutoff' => $req->cutoff
            ]);
            return ['result' => true];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function editregu($req)
    {
        try {
            DB::table('cor')->where('corgroup_id', $req->corgroup_id)->where('cor_id', $req->cor_id)
                ->update([
                    'cor_description' => $req->regu_desc
                ]);
            return ['result' => true];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function editemail($req)
    {
        // return $req;
        if ($req->type == 'USER') {
            try {
                // $check = DB::table('email_user')->where('divisioncode',$req->code)->where('level_approved',$req->level)->value('level_approved');
                // if(count($check) != 0){
                //     return ['result' => false ,'message' => 'ระดับการอนุมัติซ้ำ !'];
                // }else{
                DB::table('email_user')->where('id', $req->id)
                    ->update([
                        'divisioncode' => $req->code,
                        'divisionname' => $req->name,
                        'email' => $req->email,
                        'level_approved' => $req->level
                    ]);
                return ['result' => true];
                // }  
            } catch (\Throwable $e) {

                return ['result' => false, 'message' => $e];
            }
        } else {
            try {
                // $check = DB::table('email_hr')->where('company',$req->code)->where('level_approved',$req->level)->get();

                // if(count($check) != 0){
                //     return ['result' => false ,'message' => 'ระดับการอนุมัติซ้ำ !'];
                // }else{
                DB::table('email_hr')->where('id', $req->id)
                    ->update([
                        'company' => $req->code,
                        'email' => $req->email,
                        'level_approved' => $req->level
                    ]);
                return ['result' => true];
                // }
            } catch (\Exception $e) {
                return ['result' => false, 'message' => $e->errorInfo[2]];
            }
        }
    }
    public function edituser($req)
    {

        try {
            $datetime = date("Y-m-d H:i:s");
            DB::table('users')->where('id', $req->id)->update([
                'emplid' => $req->emplid,
                'userid' => $req->userid,
                'name' => $req->name,
                'email' => $req->email,
                'level' => $req->level,
                'updated_at' => $datetime,
            ]);
            return ['result' => true];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }
    public function editblacklist($req)
    {
        try {
            DB::table('blacklist_table')->where('id', $req->id)->update([
                'emplid' => $req->emplid,
                'cardid' => $req->cardid,
                'blacklist_group' => $req->blacklist_group,
            ]);
            return ['result' => true, 'message' => 'แก้ไข Blacklist สำเร็จ !'];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function editblacklistgroup($req)
    {
        try {
            DB::table('blacklist_group')->where('blacklist_group', $req->id)->update([
                'blacklist_description' => $req->description,
            ]);
            return ['result' => true, 'message' => 'แก้ไข Blacklist Group สำเร็จ !'];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function activecorgroup($req)
    {

        try {
            DB::table('corgroup')->where('corgroup_id', $req->corgroup_id)->update([
                'active' => $req->active,
            ]);
            return ['result' => true, 'message' => 'Update Successfull'];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function activeregu($req)
    {

        // return $req->active;

        try {
            DB::table('cor')->where([
                ['corgroup_id', '=', $req->corgroup_id],
                ['cor_id', '=', $req->corid]
            ])
                ->update(['active' => $req->active]);
            return ['result' => true, 'message' => 'Update Successfull'];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function activeemail($req)
    {

        // return $req;

        if ($req->code == 'HR') {
            if ($req->active == 1) {
                $check = DB::table('email_hr')->where([
                    ['company', '=', $req->name],
                    ['level_approved', '=', $req->level],
                    ['active', '=', 1]
                ])->get();
                if (count($check) != 0) {
                    return ['result' => false, 'message' => 'ระดับการอนุมัติซ้ำ !'];
                } else {
                    try {

                        DB::table('email_hr')->where('id', $req->id)->update([
                            'active' => $req->active,
                        ]);
                        return ['result' => true, 'message' => 'Update Successfull'];
                    } catch (\Exception $e) {
                        return ['result' => false, 'message' => $e->errorInfo[2]];
                    }
                }
            } else {
                try {

                    DB::table('email_hr')->where('id', $req->id)->update([
                        'active' => $req->active,
                    ]);
                    return ['result' => true, 'message' => 'Update Successfull'];
                } catch (\Exception $e) {
                    return ['result' => false, 'message' => $e->errorInfo[2]];
                }
            }
        } else {
            try {
                DB::table('email_user')->where('id', $req->id)->update([
                    'active' => $req->active,
                ]);
                return ['result' => true, 'message' => 'Update Successfull'];
            } catch (\Exception $e) {
                return ['result' => false, 'message' => $e->errorInfo[2]];
            }
        }
    }

    public function activeusers($req)
    {

        // return $req;

        try {
            DB::table('users')->where('id', $req->id)->update([
                'active' => $req->active,
            ]);
            return ['result' => true, 'message' => 'Update Successfull'];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function activeblacklist_group($req)
    {

        // return $req;

        try {
            DB::table('blacklist_group')->where('blacklist_group', $req->id)->update([
                'active' => $req->active,
            ]);
            return ['result' => true, 'message' => 'Update Successfull'];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function getpenalty()
    {
        $data = DB::select("SELECT *
        from penalty
        
        order by Penalty_ID ASC");
        return $data;
    }


    public function addpenalty($req)
    {
        try {
            DB::table('penalty')->insert([
                'Penalty_Description' => $req->corgroup_desc
            ]);
            return ['result' => true];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function activepenalty($req)
    {

        // return $req;
        try {
            DB::table('penalty')->where([
                ['Penalty_ID', '=', $req->Penalty_ID]
            ])
                ->update(['active' => $req->active]);
            return ['result' => true, 'message' => 'Update Successfull'];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }

    public function editpenalty($req)
    {
        try {
            DB::table('penalty')->where('Penalty_ID', $req->corgroup_id)->update([
                'Penalty_Description' => $req->corgroup_desc
            ]);
            return ['result' => true];
        } catch (\Exception $e) {
            return ['result' => false, 'message' => $e->errorInfo[2]];
        }
    }
}
