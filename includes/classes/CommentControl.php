<?php
require_once("ButtonProvider.php");

class CommentControl
{
    private $con, $comment, $userLoggedInObj;

    public function __construct($con, $comment, $userLoggedInObj)
    {
        $this->con = $con;
        $this->comment = $comment;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create()
    {
        $replyButton = $this->createReplyButton();
        $countLikes = $this->createLikesCount();
        $replySection = $this->createReplySection();
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();

        return "<div class='control'>
                    $replyButton
                    $countLikes
                    $likeButton
                    $dislikeButton
                </div>
                $replySection";
    }

    private function createReplyButton()
    {
        $text = "REPLY";
        $action = "toggleReply(this)";
        return ButtonProvider::createButton($text, null, $action, null);
    }
    private function createLikesCount()
    {
        $text = $this->comment->getLikes();
        if($text == 0) $text = "";
        return "<span class='likesCount'>
                $text
                </span>";
    }
    private function createReplySection()
    {
        $postedBy = $this->userLoggedInObj->getUsername();
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();

        $profilePicture = ButtonProvider::createUserProfileButton($this->con, $postedBy);

        $cancelButtonAction = "toggleReply(this)";

        $cancelButton = ButtonProvider::createButton("Cancel", null, $cancelButtonAction, "cancelComment");

        $postButtonAction = "postComment(this, $videoId, \"$postedBy\", $commentId, \"repliesSection\")";
        $postButton = ButtonProvider::createButton("Reply", null, $postButtonAction , "postComment");

        // echo $profilePicture;
            // get here html
        
        return "<div class='commentForm hidden'>
                    $profilePicture
                    <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                    $cancelButton
                    $postButton
                </div>";
    }
    private function createLikeButton()
    {
        $videoId = $this->comment->getVideoId();   // look at this    //  
        $commentId = $this->comment->getId();
        $action = "likeVideo($commentId, this, $videoId)";
        $class = "likeButton";

        $imageSrc = "assets/images/icons/thumb-up.png";

        if ($this->comment->wasLikedBy()) { 
            $imageSrc = "assets/images/icons/thumb-up-active.png";
        }
        return ButtonProvider::createButton("", $imageSrc, $action, $class);
    }
    private function createDislikeButton()
    {
        $commentId = $this->comment->getId();
        $videoId = $this->comment->getVideoId();
        $action = "dislikeVideo($commentId, this, $videoId)";
        $class = "dislikeButton";

        $imageSrc = "assets/images/icons/thumb-down.png";

        if ($this->comment->wasDislikedBy()) {
            $imageSrc = "assets/images/icons/thumb-down-active.png";
        }
        return ButtonProvider::createButton("", $imageSrc, $action, $class);
    }
}
