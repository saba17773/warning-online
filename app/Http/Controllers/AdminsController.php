<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use DB;

class AdminsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // << บังคับให้ Login ก่อนจึงสามารถเข้าใช้ได้
        $this->admin = new Admin; // << ใช้งาน Model
    }

    public function test(){
        return 'test';
    }

    public function corgroup(){
        return view('admins.corgroup');
    }

    public function regu(){
        return view('admins.regu');
    }

    public function email(){
        return view('admins.email');
    }

    public function user(){
        return view('admins.user');
    }
    public function blacklist(){
        return view('admins.blacklist');
    }
    public function blacklistgroup(){
        return view('admins.blacklistgroup');
    }


    public function getcorgroup(){

        $result = $this->admin->getcorgroup();
        return $result;
    }

    public function getregu(){

        $result = $this->admin->getregu();
        return $result;
    }

    public function getemail(){

        $result = $this->admin->getemail();
        return $result;
    }

    public function getuser(){

        $result = $this->admin->getuser();
        return $result;
    }

    public function getblacklist(){

        $result = $this->admin->getblacklist();
        return $result;
    }

    public function getblacklistgroup(){

        $result = $this->admin->getblacklistgroup();
        return $result;
    }

    public function addcorgroup(REQUEST $request){

        $result = $this->admin->addcorgroup($request);
        return $result;
    }

    public function addregu(REQUEST $request){

        $result = $this->admin->addregu($request);
        return $result;
    }

    public function addemail(REQUEST $request){
        
        $result = $this->admin->addemail($request);
        return $result;
    }

    public function adduser(REQUEST $request){

        $result = $this->admin->adduser($request);
        return $result;
    }
    public function addblacklist(REQUEST $request){
        $result = $this->admin->addblacklist($request);
        return $result;
    }

    public function addblacklistgroup(REQUEST $request){
        $result = $this->admin->addblacklistgroup($request);
        return $result;
    }

    public function editcorgroup(REQUEST $request){
        
        $result = $this->admin->editcorgroup($request);
        return $result;
    }

    public function editregu(REQUEST $request){
        
        $result = $this->admin->editregu($request);
        return $result;
    }

    public function editemail(REQUEST $request){

        $result = $this->admin->editemail($request);
        return $result;
    }

    public function edituser(REQUEST $request){
        
        $result = $this->admin->edituser($request);
        return $result;
    }

    public function editblacklist(REQUEST $request){
    
        $result = $this->admin->editblacklist($request);
        return $result;
    }

    public function editblacklistgroup(REQUEST $request){

        $result = $this->admin->editblacklistgroup($request);
        return $result;
    }

    public function deletecorgroup(REQUEST $request){
        try {
            DB::table('corgroup')->where('corgroup_id' , $request->corgroup_id)->delete();
            return ['result' => true];
        } catch (\Throwable $e) {
            return ['result' => false ,'message' => $e->errorInfo[2]];
        }
        
    }

    public function deleteregu(REQUEST $request){
        try {
            DB::table('cor')->where('corgroup_id' , $request->corgroup_id)->where('cor_id' , $request->cor_id)->delete();
            return ['result' => true];
        } catch (\Throwable $e) {
            return ['result' => false ,'message' => $e->errorInfo[2]];
        }
        
    }

    public function deleteemail(REQUEST $request){
        // return $request;
        if ($request->code == 'HR'){
            try {
                DB::table('email_hr')->where('id' , $request->id)->delete();
                return ['result' => true];
            } catch (\Throwable $e) {
                return ['result' => false ,'message' => $e->errorInfo[2]];
            }
        }else{
            try {
                DB::table('email_user')->where('id' , $request->id)->delete();
                return ['result' => true];
            } catch (\Throwable $e) {
                return ['result' => false ,'message' => $e->errorInfo[2]];
            }
        }
    }
    public function deleteuser(REQUEST $request){
        try {
            DB::table('users')->where('id', $request->id)->delete();
            return ['result' => true];
        }catch (\Throwable $e) {
            return ['result' => false ,'message' => $e->errorInfo[2]];
        }
    }

    public function deleteblacklist(REQUEST $request){
        try {
            DB::table('blacklist_table')->where('id', $request->id)->delete();
            return ['result' => true,'message' => 'ลบ Blacklist สำเร็จ'];
        }catch (\Throwable $e) {
            return ['result' => false ,'message' => $e->errorInfo[2]];
        }
    }

    public function deleteblacklistgroup(REQUEST $request){
        try {
            DB::table('blacklist_group')->where('blacklist_group', $request->id)->delete();
            return ['result' => true,'message' => 'ลบ Blacklist Group สำเร็จ'];
        }catch (\Throwable $e) {
            return ['result' => false ,'message' => $e->errorInfo[2]];
        }
    }

    public function activecorgroup(REQUEST $request){
        $result = $this->admin->activecorgroup($request);
        return $result;
    }

    public function activeregu(REQUEST $request){
        $result = $this->admin->activeregu($request);
        return $result;
    }

    public function activeemail(REQUEST $request){
        $result = $this->admin->activeemail($request);
        return $result;
    }

    public function activeusers(REQUEST $request){
        $result = $this->admin->activeusers($request);
        return $result;
    }

    public function activeblacklist_group(REQUEST $request){
        $result = $this->admin->activeblacklist_group($request);
        return $result;
    }

    
    public function penalty(){
        return view('admins.penalty');
    }

    public function getpenalty(){

        $result = $this->admin->getpenalty();
        return $result;
    }

    public function addpenalty(REQUEST $request){
        $result = $this->admin->addpenalty($request);
        return $result;
    }

    public function editpenalty(REQUEST $request){
        
        $result = $this->admin->editpenalty($request);
        return $result;
    }

    public function activepenalty(REQUEST $request){
        $result = $this->admin->activepenalty($request);
        return $result;
    }
}
