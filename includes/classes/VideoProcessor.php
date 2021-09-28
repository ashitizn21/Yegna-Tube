<?php

class VideoProcessor {

    private $con;
    private $sizeLimit = 500000000;
    private $allowedType = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");
            // here add ffmpeg and ffprobe based on ur operating system.... am using linux os
    private $ffmpegPath = "ffmpeg/linux/ffmpeg"; 
    private $ffprobePath = "ffmpeg/linux/ffprobe";


    public function __construct($con)
    {
        $this->con = $con;
    }

    public function upload($videoUploadData)
    {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->videoDataArray;

        $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);
        //              uploads/videosdfgh34jhj5jk3Mezmur Ashenafi.mp4    => uploads/videosdfgh34jhj5jk3Mezmur_Ashenafi.mp4
        //     uploads/videos61503ff343d78Awesome_Linux_Tools__Ulauncher.mp4
        $tempFilePath = str_replace(" ", "_", $tempFilePath);
        // echo $tempFilePath;

        $isValidData = $this->processData($videoData, $tempFilePath);
        if(!$isValidData)
        {
            return false;
        }

        if(move_uploaded_file($videoData['tmp_name'], $tempFilePath))
        {
            // echo "File moved successfully";

            $finalFilePath = $targetDir . uniqid() . ".mp4";
            
            if(!$this->insertVideoData($videoUploadData, $finalFilePath))
            {
                echo "insert query is failed";
                return false;
            }

            if(!$this->convertVideoToMp4($tempFilePath, $finalFilePath))
            {
                echo "\nupload is failed\n";
                return false;
            }

            if(!$this->deleteFile($tempFilePath))
            {
                return false;
            }

            if(!$this->generateThumbnails($finalFilePath))
            {
                return false;
            }
        }
        
        return true;
    }

    private function processData($videoData, $filePath)
    {
        $videoType = pathinfo($filePath, PATHINFO_EXTENSION);

        if(!$this->isValid($videoData)){
            echo "File too large. can't be more than ".$this->sizeLimit."byte";
            return false;
        }
        elseif(!$this->isValidType($videoType))
        {
            echo "Invalid type";
            return false;
        }
        elseif($this->hasError($videoData))
        {
            echo "Error code".$videoData['error'];
            return false;
        }

        return true;
    }

    private function isValid($data)
    {
        return $data['size'] <= $this->sizeLimit;
    }
    private function isValidType($type)
    {
        $lowerCased = strtolower($type);
        return in_array($lowerCased, $this->allowedType);
    }
    private function hasError($data)
    {
        return $data['error'] != 0;
    }
    private function insertVideoData($UploadData, $filePath)
    {
        $query = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath)
                                        VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)");
        $query->bindParam(":title", $UploadData->title);
        $query->bindParam(":uploadedBy", $UploadData->uploadedBy);
        $query->bindParam(":description", $UploadData->description);
        $query->bindParam(":privacy", $UploadData->privacy);
        $query->bindParam(":category", $UploadData->category);
        $query->bindParam(":filePath", $filePath);

        return $query->execute();

    }
    public function convertVideoToMp4($tempFilePath, $finalFilePath)
    {
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";  // 2>&1
        $outputLog = array();

        exec($cmd, $outputLog, $returnCode);  // returCode == 0  work or 1 fial

        if($returnCode != 0)
        {
            // command failed
            foreach($outputLog as $lines)
            {
                echo $lines."<br>";
            }
            return false;
        }
        return true;
    }
    private function deleteFile($filePath)
    {
        if(!unlink($filePath))
        {
            echo "\nFile couldn't be deleted\n";
            return false;
        }

        return true;
    }
    public function generateThumbnails($filePath)
    {
        $thumbnailSize = "210x118";
        $numThumbnails = 3;
        $pathThumbnails = "uploads/videos/thumbnails";

        $videoDuration = $this->getVideoDuration($filePath);

        $videoId = $this->con->lastInsertId();
        
        $this->updateDuration($videoDuration, $videoId);
        // echo "video duration is ".$videoDuration;

        for($num =0; $num < $numThumbnails; $num++){
            $imageName = uniqid() . ".jpg";
            $interval = ($videoDuration * 0.8) / $numThumbnails * $num;
            $fullThumbnailPath = "$pathThumbnails/$imageName";

            // create thumbnails
            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";            $outputLog = array();

            exec($cmd, $outputLog, $returnCode);  // returCode == 0  work or 1 fial

            if($returnCode != 0)
            {
                // command failed
                foreach($outputLog as $lines)
                {
                    echo $lines."<br>";
                }
            
            }
            $selected = $num == 1 ? 1:0;

            $query = $this->con->prepare("INSERT INTO thumbnails(videoId, filePath, selected)
                                            VALUES(:videoId, :filePath, :selected)");
            $query->bindParam(":videoId", $videoId);
            $query->bindParam(":filePath", $fullThumbnailPath);
            $query->bindParam(":selected", $selected);

            
            $success = $query->execute();
            if(!$success)
            {
                echo "Error inserting thumbnail\n";
                return false;
            }
        }
        return true;
    }
    private function getVideoDuration($filePath)
    {
        return (int)shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }
    private function updateDuration($duration, $videoId)
    {
        $duration = (int)$duration;
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - ($hours * 3600)) / 60);
        $seconds = floor($duration % 60);

        // formating
        $hours = ($hours < 1) ? "":$hours. ":";
        $minutes = ($minutes < 10 ) ? "0".$minutes . ":":$minutes.":";
        $seconds = ($seconds < 10 ) ? "0".$seconds : $seconds;

        $duration = $hours.$minutes.$seconds;

        // insert to video table
        $query = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId");
        $query->bindParam(":duration", $duration);
        $query->bindParam(":videoId",$videoId);

        $query->execute();
    }
}


?>