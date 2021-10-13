<?php
    require_once ("includes/classes/VideoGridItem.php");
    class VideoSgsGrid
    {
        private $con, $userLoggedInOBJ;
        private $largeMode = false;
        private $gridClass = "videoGrid";

        public function __construct($con, $userLoggedInOBJ)
        {
            $this->con = $con;
            $this->userLoggedInOBJ = $userLoggedInOBJ;
        }

        public function create($videos, $title, $showFilter)
        {

            if($videos != null)
            {
                $gridItems = $this->generateItemsFromVideo($videos);
            } else 
            {                
                $gridItems = $this->generateItems();
            }

            $header = "";
            if($title != null)
            {
                $header = $this->createGridHeader($title, $showFilter);
            }


            return "$header
                    <div class='$this->gridClass'>
                        $gridItems
                    </div>";
        }

        public function generateItemsFromVideo($video)
        {

        }

        public function generateItems()
        {
            $query = $this->con->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
            $query->execute();

            $elementHtml = "";

            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $video = new Video($this->con, $row, $this->userLoggedInOBJ);
                $item = new VideoGridItem($video, $this->largeMode);

                $elementHtml .= $item->create();
            }

            return $elementHtml;
        }

        public function createGridHeader($title, $showFilter)
        {

        }

    }



?>