<?php

class VideoPlayer
{
    private $video;

    public function __construct($video)
    {
        $this->video = $video;
    }

    public function create($autoplay)
    {
        // $autoplay = $autoplay == true ? "autoplay" : "";
        if($autoplay){
            $autoplay = "autoplay";
        }else{
            $autoplay = "";
        }

        $filePath = $this->video->getFilePath();

        return "<video class='videoPlayer' controls $autoplay>
                    <source src='$filePath' type='video/mp4'>
                    Your browser doesnot support vieo tag
                </video>";
    }
}

?>