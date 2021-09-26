<?php

class VideoProcessor {

    private $con;
    private $sizeLimit = 500000000;
    private $allowedType = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function upload($videoUpload)
    {
        $targetDir = "uploads/videos/";
        $videoData = $videoUpload->videoDataArray;

        $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);
        //              uploads/videosdfgh34jhj5jk3Mezmur Ashenafi.mp4    => uploads/videosdfgh34jhj5jk3Mezmur_Ashenafi.mp4
        //     uploads/videos61503ff343d78Awesome_Linux_Tools__Ulauncher.mp4
        $tempFilePath = str_replace(" ", "_", $tempFilePath);
        echo $tempFilePath;

        $isValidData = $this->processData($videoData, $tempFilePath);
        if(!$isValidData)
        {
            return false;
        }

        if(move_uploaded_file($videoData['tmp_name'], $tempFilePath))
        {
            echo "File moved successfully";
            
        }
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
}


?>