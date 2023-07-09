<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\VideoSummaries;
use App\Models\TextSummaries;

class TaskController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function generateVideoSummary(Request $request){

        try{


            $validated = $request->validate([
            'videoURL' => 'required|string',
            'userID' => 'required|string'
           
        ]);

        $video_url = $validated["videoURL"];



        $curl = curl_init();

        
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://youtube-video-summarizer1.p.rapidapi.com/v1/youtube/summarizeVideoWithToken?videoURL=".$video_url ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: youtube-video-summarizer1.p.rapidapi.com",
                "X-RapidAPI-Key:".env("RAPID_API_KEY") , 
                "openai-api-key:".env("OPENAI_API_KEY")
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return response()->json(['error'=>$err]);

        } else {
            $json_response = json_decode($response, true);

            // summary to summary history

            $addVideoSummary = new VideoSummaries();

            $addVideoSummary->user_id = $validated["userID"];
            $addVideoSummary->link = $video_url;
            $addVideoSummary->link_type="YOUTUBE";
            $addVideoSummary->summary = response()->json(['videoSummary'=>$json_response]);
            $addVideoSummary->email_verified = true;

            $addVideoSummary->save();
             

          
            return response()->json(['videoSummary'=>$json_response]);

        }

        

      
    }

    catch(\Exception $e){
        return response()->json(['message'=>'An error occured, please try again'.$e, 'error'=>$e],405);


    }

    
    }



    public function generateTextSummary(Request $request){

        try{


            $validated = $request->validate([
            'text' => 'required|string',
            'userID' => 'required|string',
            'sentence_num'=>'required|string',
           
        ]);

      //  $video_url = $validated["videoURL"];



        $curl = curl_init();

        
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://gpt-summarization.p.rapidapi.com/summarize" ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([

                'text'=> $validated["text"],
                
                'num_sentences'=>3

            ]),
          

                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: gpt-summarization.p.rapidapi.com",
                    "X-RapidAPI-Key:".env("RAPID_API_KEY") , 
                    "content-type: application/json"
                ],
             
          
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return response()->json(['error'=>$err]);

        } else {
            $json_response = json_decode($response, true);

            // summary to summary history

         $addTextSummary = new TextSummaries();

            $addTextSummary->user_id = $validated["userID"];
            $addTextSummary->text= $validated["text"];
          //  $addTextSummary->link_type="";
            $addTextSummary->summary = response()->json(['textSummary'=>$json_response]);
            $addTextSummary->email_verified = true;

            $addTextSummary->save();
             

          
            return response()->json(['textSummary'=>$json_response]);

        }

        

      
    }

    catch(\Exception $e){
        return response()->json(['message'=>'An error occured, please try again'.$e, 'error'=>$e],405);


    }

    
    }

}
