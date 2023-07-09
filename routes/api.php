<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\TaskController;
use App\Models\VideoSummaries;
use App\Models\TextSummaries;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(MembersController::class)->group(function(){
    Route::post('signup', 'store');
    Route::post('login', 'login');
    Route::post('verify_email_otp', 'verify_email_otp');
    Route::post('send_email', 'send_email');
    Route::get('test','test');
   // Route::middleware('auth:sanctum')-> post('set_transaction_pin','set_transaction_pin');
   
   // Route::get('test_api','test_api');
});

Route::get('/grabber', [DownloadController::class,'index']);

Route::get('/convert', [DownloadController::class,'index']);

Route::get('/test_api', [DownloadController::class,'test_rapid_api']);






    Route::middleware('auth:sanctum')->group(function () {

        //generate video summary
        Route::post('generate_summary', [TaskController::class, 'generateVideoSummary']);

        //generate text summary
        Route::post('generate_text_summary', [TaskController::class, 'generateTextSummary']);

      
        Route::get('view/user/video_summaries/{id}', function ($id) {
            $video_summaries = VideoSummaries::where('user_id', $id)
            ->orderByDesc('created_at')
            ->get();
        
            return response()->json( $video_summaries );
        });

        Route::get('view/user/text_summaries/{id}', function ($id) {
            $text_summaries = TextSummaries::all()
            ->orderByDesc('created_at')
            ->get();
        
            return response()->json( $text_summaries );
        });
        
        

    });
    
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

   




