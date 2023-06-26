use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\TaskController;

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

Route::prefix('members')->group(function () {
    Route::post('signup', [MembersController::class, 'store']);
    Route::post('verify_email_otp', [MembersController::class, 'verify_email_otp']);
    Route::post('send_email', [MembersController::class, 'send_email']);
    Route::get('test', [MembersController::class, 'test']);
});

Route::get('/grabber', [DownloadController::class, 'index']);

Route::get('/convert', [DownloadController::class, 'index']);

Route::get('/test_api', [DownloadController::class, 'test_rapid_api']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('generate_summary', [TaskController::class, 'generateVideoSummary']);
    // other protected routes...
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
