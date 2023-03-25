<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;

class DownloadController extends Controller
{
    //

    public function index(){

        $videoInfo = $this->getVideoInfo("NiKtZgImdlY");

        $videoInfo = json_decode($videoInfo);

        $formats =  $videoInfo->streamingData->formats;

        $format =  $formats[0];

        echo json_encode($formats);

        $firstVideoMimeType = explode(";",explode("/",$format->mimeType)[1])[0];

        $data =
        
        ["title" => $videoInfo->videoDetails->title,

         "formats"=> json_encode($formats),

         "firstVideoMimeType"=> $firstVideoMimeType,

         "url"=>$format->url,
    
    ];


   $this->downloader($videoInfo->videoDetails->title, $firstVideoMimeType , $format->url );

      //  $this-> convertToAudio();



        return view('test_screen', $data); //view('youtube_download_form');
    }




        
function getVideoInfo($video_id){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://www.youtube.com/youtubei/v1/player?key=AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{  "context": {    "client": {      "hl": "en",      "clientName": "WEB",      "clientVersion": "2.20210721.00.00",      "clientFormFactor": "UNKNOWN_FORM_FACTOR",   "clientScreen": "WATCH",      "mainAppWebInfo": {        "graftUrl": "/watch?v='.$video_id.'",           }    },    "user": {      "lockedSafetyMode": false    },    "request": {      "useSsl": true,      "internalExperimentFlags": [],      "consistencyTokenJars": []    }  },  "videoId": "'.$video_id.'",  "playbackContext": {    "contentPlaybackContext": {        "vis": 0,      "splay": false,      "autoCaptionsDefaultOn": false,      "autonavState": "STATE_NONE",      "html5Preference": "HTML5_PREF_WANTS",      "lactMilliseconds": "-1"    }  },  "racyCheckOk": false,  "contentCheckOk": false}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

  

    $file_path = public_path('/assets/temp_videos/jksbjksdbvj.mp4');
    file_put_contents($file_path, $result);
    return $result;

}

public function downloader($fileTitle, $fileType, $url){

    $downloadURL = $url;
    $type = $fileType;
    $title = $fileTitle;
    $fileName = $title.'.'.$type;


    if (!empty($downloadURL) && substr($downloadURL, 0, 8) === 'https://') {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header("Content-Transfer-Encoding: binary");

        readfile($downloadURL);

    }

}

public function convertToAudio(){

    $videoPath =  public_path('/assets/temp_videos/jksbjksdbvj.mp4');
    FFMpeg::fromDisk('public')
    ->open($videoPath)
    ->export()
    ->toDisk('public')
    ->inFormat(new \FFMpeg\Format\Audio\Mp3)
    ->save(public_path('/assets/temp_audios/converted_audio.mp3'));
}


    
}
