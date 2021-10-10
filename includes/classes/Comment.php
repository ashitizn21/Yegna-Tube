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

        echo "dfghjnk" ;
        echo $con->lastInsertId();
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
        $query = $this->con->prepare("SELECT count(*) as 'count'FROM likes WHERE commentId=:commentId");
        $query->bindParam(":commentId", $commentId);

        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numberOfLike = $data['count'];


        $query = $this->con->prepare("SELECT count(*) as 'count'FROM dislikes WHERE commentId=:commentId");
        $query->bindParam(":commentId", $commentId);

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

    }

}


?>