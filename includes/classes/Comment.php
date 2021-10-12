<?php
    require_once("ButtonProvider.php");
    require_once ("CommentControl.php");

    class Comment
{  
    private $sqlData, $con, $userLoggedInOBJ, $videoId;

    public function __construct($con, $userLoggedInOBJ, $videoId, $input)
    {
        $this->con = $con;
        if(!is_array($input))
        {
            $query = $this->con->prepare("SELECT * FROM comments WHERE id=:id");
            $query->bindParam(":id", $input);

            $query->execute();

            $input = $query->fetch(PDO::FETCH_ASSOC);
        }

        $this->sqlData = $input;
        $this->userLoggedInOBJ = $userLoggedInOBJ;
        $this->videoId = $videoId;     

    }

    public function create()
    {
        $id = $this->getId();
        $videoId = $this->getVideoId();
        $postedBy = $this->sqlData["postedBy"];
        $bodyText = $this->sqlData["body"];
        $datePosted = $this->sqlData['datePosted'];
        $profile = ButtonProvider::createUserProfileButton($this->con, $postedBy);
        $timeStamp = $this->time_elapsed_string($this->sqlData['datePosted']);

        $commentControlObj = new CommentControl($this->con, $this, $this->userLoggedInOBJ);
        $commentOBJ = $commentControlObj->create();

        $numResponses = $this->getNumberOfReplies();

        if($numResponses > 0 ) {
            $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies($id, this, $videoId)'>
                                    view all $numResponses replies
                                </span>";
        } else {
            $viewRepliesText = "<div class='repliesSection' >
                                </div>";
        }
        return "<div class='itemContainer'>
                    <div class='comment'>
                        $profile

                        <div class='mainContainer'>

                            <div class='commentHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timeStamp'>
                                    $timeStamp
                                </span>
                                </>
                            </div>

                            <div class='body'>
                                $bodyText
                            </div>
                        </div>
                    </div>
                    $commentOBJ
                </div>";
    }

    public function getVideoId()
    {
        return $this->videoId;
    }
    public function getId()
    {
        return $this->sqlData['id'];
    }

    public function getLikes()
    {
        $commentId = $this->getId();
        $videoId = $this->getVideoId();
        $query = $this->con->prepare("SELECT count(*) as 'count'FROM likes WHERE commentId=:commentId AND videoId=:videoId");
        $query->bindParam(":commentId", $commentId);
        $query->bindParam(":videoId", $videoId);

        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numberOfLike = $data['count'];


        $query = $this->con->prepare("SELECT count(*) as 'count'FROM dislikes WHERE commentId=:commentId AND videoId=:videoId");
        $query->bindParam(":commentId", $commentId);
        $query->bindParam(":videoId", $videoId);

        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numberOfDislike = $data['count'];

        return $numberOfLike - $numberOfDislike;
    }


    public function wasLikedBy()
    {
        $username = $this->userLoggedInOBJ->getUsername();
        $commentId = $this->getId();
        $query = $this->con->prepare("SELECT * FROM likes WHERE username=:us AND commentId=:commentId");
        $query->bindParam(":us", $username);
        $query->bindParam(":commentId", $commentId);

        $query->execute();

        return $query->rowCount() > 0;
        
    }
    public function wasDislikedBy()
    {
        $username = $this->userLoggedInOBJ->getUsername();
        $commentId = $this->getId();

        $query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:us AND commentId=:commentId");
        $query->bindParam(":us", $username);
        $query->bindParam(":commentId", $commentId);

        $query->execute();

        return $query->rowCount() > 0 ;
    }


    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function getNumberOfReplies()
    {
        $id = $this->getId();
        $videoId = $this->getVideoId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM comments WHERE responseTo=:responseTo AND videoId=:videoId");  // here adding of videoId doesn't change
        $query->bindParam(":responseTo", $id);
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->fetchColumn();  // the first column returned is number  
    }



    public function like()
    {
           // return "am from ajax, video clas";
        $commentId = $this->getId();
        $username = $this->userLoggedInOBJ->getUsername();
        $videoId = $this->getVideoId();

        if($this->wasLikedBy())
        {   // already liked, so remove from like table
            $q_query = $this->con->prepare("DELETE FROM likes WHERE username=:us AND commentId=:commentId AND videoId=:videoId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);
            $q_query->bindParam(":commentId", $commentId);

            $q_query->execute();

                return -1;

        }else
        {   // not liked before,so insert into like table
                    // remove from dislike table
            $q_query = $this->con->prepare("DELETE FROM dislikes WHERE username=:us AND videoId=:videoId AND commentId=:commentId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);
            $q_query->bindParam(":commentId", $commentId);

            $q_query->execute();
            $count = $q_query->rowCount();

            $q_query = $this->con->prepare("INSERT INTO likes (username, videoId, commentId) VALUES(:us, :videoId, :commentId)");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);
            $q_query->bindParam(":commentId", $commentId);

            $q_query->execute();

            return 1 + $count;
            
        }

    }
    public function dislike()
    {
        $username = $this->userLoggedInOBJ->getUsername();
        $videoId = $this->getVideoId();
        $commentId = $this->getId();

        if($this->wasDislikedBy())
        {   // already disliked, so remove from like table
            $q_query = $this->con->prepare("DELETE FROM dislikes WHERE username=:us AND commentId=:commentId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":commentId", $commentId);

            $q_query->execute();

           return 1;
        }else
        {   // not liked before,so insert into like table
            $q_query = $this->con->prepare("DELETE FROM likes WHERE username=:us AND commentId=:commentId");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":commentId", $commentId);

            $q_query->execute();
            $count = $q_query->rowCount();

            $q_query = $this->con->prepare("INSERT INTO dislikes (username, videoId, commentId) VALUES(:us, :videoId, :commentId)");
            $q_query->bindParam(":us", $username);
            $q_query->bindParam(":videoId", $videoId);
            $q_query->bindParam(":commentId", $commentId);

            $q_query->execute();

            return -1 - $count;

        }   
    }

    public function getCommentReplies()
    {
        $videoId = $this->getVideoId();
        $id  = $this->getId();
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId AND responseTo=commentId ORDER BY datePosted ASC ");
        $query->bindParam(":videoId", $videoId);
        $query->bindParam(":commentId", $id);

        $query->execute();

        $commentARR = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $comment = new Comment($this->con, $this->userLoggedInObj, $videoId, $row);
            array_push($commentARR, $comment);
        }

        return $commentARR;
    }

}


?>