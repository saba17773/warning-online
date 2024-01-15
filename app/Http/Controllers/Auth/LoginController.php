<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(REQUEST $request){
        
        $user = User::where('userid', $request->userid)
            ->where('password', $request->password)
            ->first();

           if($user) {
               if($user->active == 1){
                Auth::loginUsingId($user->id);
                
                $computername = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            
                // DB::connection('sqlsrv2')->insert(
                //     "SET NOCOUNT ON;
                //     INSERT INTO [EA_APP].[dbo].[TB_LOG_APP] (EMP_CODE,USER_NAME,HOST_NAME,LOGIN_DATE,PROJECT_NAME)
                //     VALUES (?,?,?,?,?);",[
                //         $user->emplid,
                //         $request->userid,
                //         $computername,
                //         date('Y-m-d H:i:s'),
                //         'Warning Online'
                //     ]
                // );

                return redirect('/');
               }else{
                return redirect()->back()->withInput()->withErrors([ 'userid' => 'Username นี้ไม่ได้ถูก Active']);
               }
                
            } else {
                return redirect()->back()->withInput()->withErrors([ 'userid' => 'Username หรือ Password ไม่ถูกต้อง']);
            }
    }
    public function username()
    {
        return 'userid';
    }

    public function level()
    {
        return 'level';
    }
}
