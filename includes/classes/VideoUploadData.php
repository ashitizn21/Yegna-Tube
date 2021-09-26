<?php

class VideoUploadData {

    public $videoDataArray, $title, $description, $category, $privacy, $uploadedBy;
    public function __construct($videoDataArray, $title, $description, $category, $privacy, $uploadedBy)
    {
        $this->videoDataArray = $videoDataArray;
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->privacy = $privacy;
        $this->uploadedBy = $uploadedBy;
    }


}

?>