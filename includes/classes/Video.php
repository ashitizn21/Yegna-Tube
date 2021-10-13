<?php
    require_once ("Comment.php");
class Video
{
    private $con, $sqlData, $userLoggedInObj;

    public function __construct($con, $input, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

        if(is_array($input)){
            $this->sqlData = $input;
        }else
        {
            $query = $this->con->prepare("SELECT * FROM videos WHERE id=:id");
            $query->bindParam(":id",$input);
            
            $query->execute();
            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }
    public function getId()
    {
        return $this->sqlData['id'];
    }
    public function getUploadedBy()
    {
        return $this->sqlData['uploadedBy'];
    }
    public function getTitle()
    {
        return $this->sqlData['title'];
    }
    public function getDescription()
    {
        return $this->sqlData['description'];
    }
    public function getPrivacy()
    {
        return $this->sqlData['privacy'];
    }
    public function getFilePath()
    {
        return $this->sqlData['filePath'];
    }
    public function getUploadDate()
    {
        $date = $this->sqlData['uploadDate'];
        return date("M j, Y", strtotime($date));
    }
    public function getViews()
    {
        return $this->sqlData['views'];
    }
    public function getDuration()
    {
        return $this->sqlData['duration'];
    }
    public function increamentViews()
    {
        $videoId = $this->getId();

        $query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
        $query->bindParam(":id", $videoId);

        $query->execute();
        //
        $this->sqlData['views'] = $this->sqlData['views'] + 1;
    }
    public function getLike()
    {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId=:videoId");
        $query->bindParam(":videoId", $videoId);

        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data['count'];
    }
    public function getDislike()
    {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId=:videoId");
        $query->bindParam(":videoId", $videoId);

        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data['count'];
    }

    public function like()
    {
           // return "am from ajax, video clas";
        $videoId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
       
        if($this->wasLikedBy())
        {   // already liked, so remove from like table
            $q_query = $this->con->prepare("DELETE FROM likes WHERE username=:us AND videoId=:videoId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);

            $q_query->execute();

                $result =array(
                    "likes" => -1,
                    "dislikes" => 0
                );

                return json_encode($result);

        }else
        {   // not liked before,so insert into like table
                    // remove from dislike table
            $q_query = $this->con->prepare("DELETE FROM dislikes WHERE username=:us AND videoId=:videoId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);

            $q_query->execute();
            $count = $q_query->rowCount();

            $q_query = $this->con->prepare("INSERT INTO likes (username, videoId) VALUES(:us, :videoId)");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);

            $q_query->execute();

            $result = array(
                            "likes" => 1,
                            "dislikes" => 0 - $count
                            );

            return json_encode($result);
            
        }

    }
    public function dislike()
    {
        $username = $this->userLoggedInObj->getUsername();
        $videoId = $this->getId();
        
        if($this->wasDislikedBy())
        {   // already liked, so remove from like table
            $q_query = $this->con->prepare("DELETE FROM dislikes WHERE username=:us AND videoId=:videoId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);

            $q_query->execute();

            $result =array(
                "likes" => 0,
                "dislikes" => -1
            );

            return json_encode($result);
        }else
        {   // not liked before,so insert into like table
            $q_query = $this->con->prepare("DELETE FROM likes WHERE username=:us AND videoId=:videoId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);

            $q_query->execute();
            $count = $q_query->rowCount();

            $q_query = $this->con->prepare("INSERT INTO dislikes (username, videoId) VALUES(:us, :videoId)");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);

            $q_query->execute();


            $result = array(
                "likes" => 0 - $count,
                "dislikes" => 1
                );

            return json_encode($result);

        }   
    }

    public function wasLikedBy()
    {
        $username = $this->userLoggedInObj->getUsername();
        $videoId = $this->getId();

        $query = $this->con->prepare("SELECT * FROM likes WHERE username=:us AND videoId=:videoId");
        $query->bindParam(":us", $username);
        $query->bindParam(":videoId", $videoId);

        $query->execute();

        return $query->rowCount() > 0;
        
    }
    public function wasDislikedBy()
    {
        $username = $this->userLoggedInObj->getUsername();
        $videoId = $this->getId();

        $query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:us AND videoId=:videoId");
        $query->bindParam(":us", $username);
        $query->bindParam(":videoId", $videoId);

        $query->execute();

        return $query->rowCount() > 0 ;
    }

    public function getNumberOfComment()
    {
            $videoId = $this->getId();
            $query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId");
            $query->bindParam(":videoId", $videoId);

            $query->execute();

            return $query->rowCount();
    }

    public function getComments()
    {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId AND responseTo=0 ORDER BY datePosted DESC ");
        $query->bindParam(":videoId", $videoId);

        $query->execute();

        $commentARR = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $comment = new Comment($this->con, $this->userLoggedInObj, $videoId, $row);
            array_push($commentARR, $comment);
        }

        return $commentARR;
    }
    public function getThumbnail()
    {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT filePath FROM thumbnails WHERE videoId=:videoId AND selected=1");
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->fetchColumn();
    }
}

?>