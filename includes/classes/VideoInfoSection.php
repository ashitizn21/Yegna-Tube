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
                            <span class='viewCount'>$views views</span>
                            $control
                        </div>
                    </div>";
        }
        private function createSecondaryInfo()
        {
            $description = $this->video->getDescription();
            $postedBy = $this->video->getUploadedBy();
            $postDate = $this->video->getUploadDate();
            $profilePic = ButtonProvider::createUserProfileButton($this->con, $postedBy);
            
          
                // check if the user is who posted video  
            if($postedBy == $this->userLoggedInObj->getUsername())
            {     // can not subscribe to itself, but can edit video
                $button = ButtonProvider::createEditVideoButton($this->video->getId());
            }
            else
            {
                $userToObject = new User($this->con, $postedBy);
                $button = ButtonProvider::createSubscriberButton($this->con, $userToObject, $this->userLoggedInObj);
            }
            return "<div class='secondaryInfo'>

                        <div class='topInfo'>
                            $profilePic
                            
                            <div class='uploadInfo'>
                                <span class='owner'>
                                    <a href='profile.php?username=$postedBy'>
                                        $postedBy
                                    </a>
                                 </span>

                                 <span class='date'> Published on $postDate</span>
                            </div>
                                $button
                        </div>

                        <div class='descriptionContainer'>
                            $description
                        </div>
                    </div>";
        }
    }



?>