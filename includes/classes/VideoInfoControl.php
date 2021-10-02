<?php
        require_once("includes/classes/ButtonProvider.php");

    class VideoInfoControl
    {
        private $video, $userLoggedInObj;

        public function __construct($video, $userLoggedInObj)
        {
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create()
        {
           $likeButton = $this->createLikeButton();
           $dislikeButton = $this->createDislikeButton();

           return "<div class='control'>
                        $likeButton
                        $dislikeButton
                    </div>";
        }
        private function createLikeButton()
        {
            $text = $this->video->getLike();
            $videoId = $this->video->getId();
            $action = "likeVideo(this, $videoId)";
            $class = "likeButton";

            $imageSrc = "assets/images/icons/thumb-up.png";
            return ButtonProvider::createButton($text, $imageSrc, $action, $class);
        }
        private function createDislikeButton()
        {
            $text = $this->video->getDislike();
            $videoId = $this->video->getId();
            $action = "dislikeVideo(this, $videoId)";
            $class = "dislikeButton";
            
            $imageSrc = "assets/images/icons/thumb-down.png";
            return ButtonProvider::createButton($text, $imageSrc, $action, $class);
        }
    }

?>