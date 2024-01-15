<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Email;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

require_once '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';

class EmailsController extends Controller
{
	use SendsPasswordResetEmails;
	/**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
	public function __construct()
    {
        
        $this->Email = new Email; // << ใช้งาน Model
    }
    Public function validateEmail(Request $request)
    {
        
        $request->validate(['email' => 'required|email']);
		}
		    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function lostpass(Request $request){
			
			// Check Email exists in users' table
		if (User::where('email', '=', $request["email"])->exists()) {
			//อีเมลล์มีในระบบ 
			$result = $this->Email->sendlostpass($request);
			return redirect()->back() ->with('alert', 'รหัสผ่านถูกส่งไปยัง Email ของคุณแล้ว !');
		}
		else{
			//อีเมลล์ไม่มีในระบบ
			return redirect()->back()->withInput()->withErrors([ 'email' => 'Email ไม่มีในระบบ']);
		}
	}

	public function sendmail(REQUEST $request){
		
		$result = $this->Email->send($request);
		return $result;
	}

	public function detail($encrypt){
		
		$result = $this->Email->detail($encrypt);
		return view('pages.report_mini')->with($result);	
	}

	public function approved(REQUEST $request){

		
		
		$result = $this->Email->approved($request);
		return $result;
	}

	public function decline(REQUEST $request){
		
		$result = $this->Email->decline($request);

		return $result;
	}



}
