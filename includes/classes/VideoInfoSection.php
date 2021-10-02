<?php
    require_once("includes/classes/VideoInfoControl.php");
    class VideoInfoSection
    {
        private $con, $video, $userLoggedInObj;

        public function __construct($con, $video, $userLoggedInObj)
        {
            $this->con = $con;
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create()
        {
            return $this->createPrimaryInfo() . $this->createSecondaryInfo();   

        }

        private function createPrimaryInfo()
        {
            $title = $this->video->getTitle();
            $views = $this->video->getViews();

            $videoInfoControl = new VideoInfoControl($this->video, $this->userLoggedInObj);

            $control = $videoInfoControl->create();
            return "<div class='videoInfo'>
                        <h3>$title</h3>

                        <div class='P_infoBelowSection'>
                            <span class='viewCount'>$views</span>
                            $control
                        </div>
                    </div>";
        }
        private function createSecondaryInfo()
        {

        }
    }



?>