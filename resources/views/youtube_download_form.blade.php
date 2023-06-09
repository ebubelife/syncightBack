
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Download YouTube video</title>
    <!-- Font-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .formSmall {
            width: 700px;
            margin: 20px auto 20px auto;
        }
    </style>

</head>
<body>
    <div class="container">
        <form method="get" action="" class="formSmall">
            <div class="row">
                <div class="col-lg-12">
                    <h7 class="text-align"> Download YouTube Video</h7>
                </div>
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" class="form-control" name="video_link" placeholder="Paste link.. e.g. https://www.youtube.com/watch?v=5cpIZ8zHHXw" <?php if(isset($_POST['video_link'])) echo "value='".$_POST['video_link']."'"; ?>>
                        <span class="input-group-btn">
                        <button type="submit" name="submit" id="submit" class="btn btn-primary">Go!</button>
                      </span>
                    </div><!-- /input-group -->
                </div>
            </div><!-- .row -->
        </form>

       <!-- Place Your Video Downloader Code Here -->
    </div>
</body>
</html>

<?php if(isset($_GET['submit'])): ?>


<?php 
$video_link = $_GET['video_link'];
preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_link, $match);
$video_id =  $match[1];
$video = json_decode(getVideoInfo($video_id));
$formats = $video->streamingData->formats;
$adaptiveFormats = $video->streamingData->adaptiveFormats;
$thumbnails = $video->videoDetails->thumbnail->thumbnails;
$title = $video->videoDetails->title;
$short_description = $video->videoDetails->shortDescription;
$thumbnail = end($thumbnails)->url;
?>


<div class="row formSmall">
    <div class="col-lg-3">
        <img src="<?php echo $thumbnail; ?>" style="max-width:100%">
    </div>
    <div class="col-lg-9">
        <h2><?php echo $title; ?> </h2>
        <p><?php echo str_split($short_description, 100)[0]; ?></p>
    </div>
</div>

<?php if(!empty($formats)): ?>


    <?php if(@$formats[0]->url == ""): ?>
    <div class="card formSmall">
        <div class="card-header">
            <strong>This video is currently not supported by our downloader!</strong>
            <small><?php 
            $signature = "https://example.com?".$formats[0]->signatureCipher;
                        parse_str( parse_url( $signature, PHP_URL_QUERY ), $parse_signature );
                        $url = $parse_signature['url']."&sig=".$parse_signature['s'];
                   ?>
            </small>
        </div>
    </div>
    <?php 
    die();
    endif;
    ?>
    
    <div class="card formSmall">
        <div class="card-header">
            <strong>With Video & Sound</strong>
        </div>
        
        <div class="card-body">
            <table class="table ">
                <tr>
                    <td>URL</td>
                    <td>Type</td>
                    <td>Quality</td>
                    <td>Download</td>
                </tr>
                <?php foreach($formats as $format): ?>
                    <?php
                    
                    if(@$format->url == ""){
                        $signature = "https://example.com?".$format->signatureCipher;
                        parse_str( parse_url( $signature, PHP_URL_QUERY ), $parse_signature );
                        $url = $parse_signature['url']."&sig=".$parse_signature['s'];
                    }else{
                        $url = $format->url;
                    }
                    
                        
                    
                    
                    ?>
                    <tr>
                        <td><a href="<?php echo $url; ?>">Test</a></td>
                        <td>
                            <?php if($format->mimeType) echo explode(";",explode("/",$format->mimeType)[1])[0]; else echo "Unknown";?>
                        </td>
                        <td>
                            <?php if($format->qualityLabel) echo $format->qualityLabel; else echo "Unknown"; ?>
                        </td>
                        <td>
                            <a 
                                href="downloader.php?link=<?php echo urlencode($url)?>&title=<?php echo urlencode($title)?>&type=<?php if($format->mimeType) echo explode(";",explode("/",$format->mimeType)[1])[0]; else echo "mp4";?>"
                            >
                                Download
                            </a> 
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <!-- Your code here for additional formats -->
    
<?php endif; ?>


<?php endif; ?>