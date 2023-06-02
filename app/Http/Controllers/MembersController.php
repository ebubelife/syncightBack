<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test(){



        return response()->json(['message'=>'its working!']);
    }

    public function store(Request $request)
    {
      

        try{

            $validated = $request->validate([
                'name' => 'required|string:max:255',
                
               
               
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
              
            ],
            [
                "email.required"=>"Please enter a valid email address",
                "email.unique"=>"That email address is in use already",
               
            ]
             
        );

        $user = new Members();
        $user->name = $validated['name'];
       
      
       
        $user->email = $validated['email'];

        $checkEmailValid = $this->checkEmailValid($user->email);

        //generate 4 digit email otp
        $otp = $random_number = rand(1000, 9999);


        $user->email_code =  $otp;
        $user->email_verified = false;
      
      
        $user->password = Hash::make($validated['password']);

        //generate affiliate id
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle($characters), 0, 6);
       
        
        
        $checkEmailValid = $this->checkEmailValid($user->email);
        $checkEmailExists = $this->checkEmailExists($user->email);
       // $checkPhoneExists = $this->checkPhoneExists($user->phone);

        if($checkEmailValid && !$checkEmailExists ){

        $user->save();

        // Generate a new API token for the user...
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message'=>'success'],200);

        }else if(!$checkEmailValid){

            return response()->json(['message'=>'Please use a valid email'],405);


        }

        else if($checkEmailExists){

            return response()->json(['message'=>'That email is in use already, please try another'],405);


        }

          
        }
        catch(\Exception $e){
            return response()->json(['message'=>'failed', 'error'=>$e],400);


        }

    }

    //validate email address
public function checkEmailValid($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // email is valid
       return true;
      } else {
        // email is not valid
      return false;
      }
}

    //Check if email is already in use by another user

    public function checkEmailExists($email)
{
  
    $user = Members::where('email', $email)->first();

    if ($user) {
        return true;
       // return response()->json(['exists' => true]);
    } else {
        return false;
       // return response()->json(['exists' => false]);
    }
}

 //Check if phone is already in use by another user

 public function checkPhoneExists($email)
 {
   
     $user = Members::where('phone', $email)->first();
 
     if ($user) {
         return true;
        // return response()->json(['exists' => true]);
     } else {
         return false;
        // return response()->json(['exists' => false]);
     }
 }

public function login(Request $request){

    try{
    $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = Members::where('email', $request->email)->first();

    if (!$user ) {
         return response()->json(['message'=>'That email doesn\'t exist in our records.'],403);
    }
    else if(!Hash::check($request->password, $user->password)){
        return response()->json(['message'=>'That password is wrong and doesn\'t match the email.'],405);

    }

    if($user["email_verification_status"]=="0"){
        return response()->json(['message'=>'Account not verified. Please click the button below to get a verification code.'],401);

    }
   

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Successfully logged in.',
        'user_details' => $user,
        'access_token' => $token
    ]);
}catch(Exception $e){

    return response()->json(['message' => $e->getMessage()],500);
}

}



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Members  $members
     * @return \Illuminate\Http\Response
     */
    public function show(Members $members)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Members  $members
     * @return \Illuminate\Http\Response
     */
    public function edit(Members $members)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Members  $members
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Members $members)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Members  $members
     * @return \Illuminate\Http\Response
     */
    public function destroy(Members $members)
    {
        //
    }
}
