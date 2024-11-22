<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\PasswordResets;




class FrontendController extends Controller
{
    public function userLogin(Request $req)
    {
        // Validate the input
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);
    
        $email = $req->input('email');
        $p = md5($req->input('password'));
    
        // Fetch user data
        $users = DB::select('
            SELECT u.id, u.name, u.email_id, p.mobile_no, r.id as role_id, r.name as role_name
            FROM users u
            JOIN profile p ON u.id = p.user_id
            JOIN roles r ON r.id = u.role_id
            WHERE u.email_id = ? AND u.password = ? AND u.is_email_verify = 1
        ', [$email, $p]);
    
        if (count($users) > 0) {
            $user = $users[0]; // Assuming there is only one matching user
    
            // Set session variables
            Session::put('username', $user->name);
            Session::put('role_name', $user->role_name);
            Session::put('user_id', $user->id);
            Session::put('role_id', $user->role_id);
            Session::put('email', $user->email_id);
    
            // Redirect based on role_id
            switch ($user->role_id) {
                case 5:
                    return redirect('broker/allLoansApplications');
                case 4:
                    return redirect('admin/dashboard');
                case 2:
                    return redirect('agent/agentDashboard');
                case 3:
                    return redirect('partner/partnerDashboard');
                case 1:
                    return redirect('/');
                default:
                    return redirect('/');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function activate($user_id, $auth_code)
    {
        $userAuth = DB::table('users')
            ->where('id', $user_id)
            ->where('email_otp', $auth_code)
            ->get();


        $is_active = $userAuth[0]->is_email_verify;

        if ($is_active == 0) {
            $update = DB::table('users')
                ->where('id', $user_id)
                ->update(['is_email_verify' => 1, 'updated_at' => Carbon::now()]);
            $result =  array('status' => 'success', 'message' => "Congratulation! Your account is activated successfully...!!!");
        } else {
            $result =  array('status' => 'failed', 'message' => "Your account is already activated...!");
        }

        return view('frontend/account_activation', compact('result'));
        // print json_encode($result);
    }


    function reset_password_link(Request $request){

        $validator = Validator::make($request->all(), ['email' => 'required',]);

        if (!$validator->passes()) {
            return redirect('forgot')->with('error', 'The Email Address field is empty.');
        } else {
            $user = DB::table('users')
            ->where('email_id', $request->email)
            ->first();
            if($user){

                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $auth_id = substr(str_shuffle($permitted_chars), 0, 10);
                $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
                $expDate = date("Y-m-d H:i:s", $expFormat);

                $email = $request->email;
                $name = $user->name;
                $msg = env('baseURL') . "/reset_password/".$auth_id;
                $temp_id = 2;

                $values = [
                    'email' => $email,
                    'token' => $auth_id,
                    'exp_date' => $expDate,
                    'user_id' => $user->id
                ];

                // die;
                $addExp = PasswordResets::create($values);

                //calling UsersController temail function from FrontendController
                app(UsersController::class)->temail($email, $name, $msg, $temp_id);

                return redirect('forgot')->with('status', 'We sent an email to your registered email-id to help you recover your account. Please login into your email account and click on the link we sent to reset your password');
            }else{
                return redirect('forgot')->with('error', 'Sorry, no user exists on our system with that email');
            }
        }
    }

    
    function reset_password($auth_id){
        $curDate = date("Y-m-d H:i:s");
        $user = DB::table('password_resets')->where('token', $auth_id)->first();
        if ($user) {
            if ($user->exp_date >= $curDate) {
                if ($user->is_verified == 1) {
                    return redirect('forgot')->with('error', 'The link is expired. You have already used this link to reset your password. Please enter Email ID again to generate to reset link.');
                } else {
                    session()->put('email_id', $user->email);
                    session()->put('user_id', $user->user_id);
                    session()->put('auth_id', $auth_id);
                    return view('frontend.reset_password');
                }
            } else {
                return redirect('forgot')->with('error', 'The link is expired. You are trying to use the expired link which as valid only 24 hours (1 days after request).');
            }
        } else {
            return redirect('forgot')->with('error', 'Authentication failed!');
        }

    }

    function update_password(Request $req){

        $validator = Validator::make($req->all(), [
            'password' => 'required',
        ]);

        if (!$validator->passes()) {
            return redirect('reset_password/'.$req->auth_id)->with('error', 'The Password field is empty.');
        } else {
            $first_password = $req->input('password');
            $second_password = $req->input('cpassword');
            $email = $req->input('email');
            $user_id = $req->input('user_id');

            $check = strcmp($first_password, $second_password);
            if ($check == 0) {
                // $pwd = Hash::make($second_password);
                $users = DB::table('users')->where('email_id', $email)->where('id', $user_id)->first();
                if ($users) {
                    DB::table('users')->where('email_id', $email)->where('id', $user_id)->update(['password' => md5($first_password)]);
                    $update = PasswordResets::where('token', $req->auth_id)->update(['is_verified' =>  1]);
                    return redirect('/')->with('status', 'Password updated.');

                }
            } else {
                return redirect('reset_password/'.$req->auth_id)->with('error', 'Password and Confirmed Password do not match');
            }
        }
    }

    

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }

    function forgot(){
        return view('frontend.forgot');
    }

    public function TestView(){
        return view('frontend.test');
    }

    public function ContactView()
    {
        return view('frontend.contact');
    }

    public function RegisterView()    {
        return view('registration');
    }

    public function ServicesView()    {
        return view('frontend.services');
    }
    public function AboutView(){
        return view('frontend.about');
    }
    public function PrivacyView(){
        return view('frontend.privacy');
    }
    public function TermCondView(){
        return view('frontend.termcond');
    }

    public function PropDetailsView($property_id){
        $data['propertie_details'] = DB::select('select * from properties as p, price_range as pr, property_category as pc where 
        p.price_range_id = pr.range_id and pc.pid = p.property_type_id and p.properties_id =' . $property_id);

        return view('frontend.property-details',compact('data'));
    }

    // Loan Application
    public function ProfessionalDetailView(){
        return view('frontend.professional-info');
    }

    public function CalculatorView(){
        return view('frontend.calculator');
    }

    public function properties()
    {
        $data['allProperties'] = DB::table('properties')
            ->join('price_range', 'properties.price_range_id', '=', 'price_range.range_id')
            ->join('property_category', 'properties.property_type_id', '=', 'property_category.pid')
            ->where('properties.is_active',1)
            ->select('properties.properties_id', 'properties.title', 'properties.image', 'properties.property_type_id', 'properties.builder_name','properties.select_bhk', 
            'properties.address','properties.facilities',  'properties.contact', 'price_range.from_price', 'price_range.to_price', 'property_category.category_name', 'properties.property_details', 'properties.localities', 'properties.city', 'properties.area')
        ->paginate(700);

        $data['category'] = DB::table('property_category')->get();
        $data['range'] = DB::table('price_range')->get();

        return view('frontend.properties',compact('data'));
    }
    

    public function search_properties(Request $request)
    {
        $range_id = $request->range_id;
        $category_type = $request->category_type;
        $location_name = $request->location_name;


        $data['allProperties'] = DB::table('properties')
            ->join('price_range', 'properties.price_range_id', '=', 'price_range.range_id')
            ->join('property_category', 'properties.property_type_id', '=', 'property_category.pid')
            ->where('properties.is_active',1)
            ->where('properties.property_type_id',$category_type)
            ->orWhere('properties.price_range_id',$range_id)
            ->select('properties.properties_id', 'properties.title', 'properties.image', 'properties.property_type_id', 'properties.builder_name','properties.select_bhk', 
            'properties.address','properties.facilities',  'properties.contact', 'price_range.from_price', 'price_range.to_price', 'property_category.category_name', 'properties.property_details')
        ->paginate(700);

        $data['category'] = DB::table('property_category')->get();
        $data['range'] = DB::table('price_range')->get();

        return  view('frontend.searchResult', compact('data'))->render();
    }

    public function ReferralsView(){
        return view('frontend.referrals');
    }

    public function HomeLoanView(){
        return view('frontend.allLoans.home');
    }

    public function LAPLoanView(){
        return view('frontend.allLoans.loan-against-property');
    }

    public function ProjectLoanView(){
        return view('frontend.allLoans.project');
    }

    public function OverdraftLoanView(){
        return view('frontend.allLoans.overdraft-facility');
    }

    public function LRDLoanView(){
        return view('frontend.allLoans.lrd');
    }
    
    public function MSMELoanView(){
        return view('frontend.allLoans.msme');
    }
}