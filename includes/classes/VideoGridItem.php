<?php

class VideoGridItem
{
    private $video, $largeMode;

    public function __construct($video, $largeMode)
    {
        $this->video = $video;
        $this->largeMode = $largeMode;    
    }

    public function create()
    {
        $thumbnail = $this->createThumbnail();
        $details = $this->createDetails();
        $url = "watch.php?id=".$this->video->getId();

        return "<a href='$url'>
                    <div class='videoGridItem'>
                        $thumbnail
                        $details
                    </div>
                </a>";

    }
    private function createThumbnail()
    {
        $thumbnail = $this->video->getThumbnail();
        $duration = $this->video->getDuration();

        return "<div class='thumbnail'>
                    <img src='$thumbnail'>
                    <div class='duration'>
                        $duration
                    </div>
                </div>";
    }
    public function createDetails()
    {
        $title = $this->video->getTitle();
        $description = $this->createDescription();
        $uploadDate = $this->video->getUploadDate();
        $views = $this->video->getViews();
        $username = $this->video->getUploadedBy();


        return "<div class='details'>
                    <h3 class='title'> $title</h3>
                    <span class='username'>$username</span>
                    <div class='stats'>
                        <span class='viewCount'>$views Views - </span>
                        <span class='timeStamp'>$uploadDate</spam>
                    </div>
                    $description
                </div>";
    }
    private function createDescription()
    {
        if(!$this->largeMode){
            return "";
        } else
        {
            $description = $this->video->getDescription();
            $description = strlen($description > 350 ) ? substr($description, 0, 346) . "..." : $description;
            return "<span class='description'>
                        $description
                    </span>";

        }

    }

}

?>