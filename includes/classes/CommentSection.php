<?php

    class CommentSection
    {
        private $con, $video, $userLoggedInOBJ;

        public function __construct($con, $video, $userLoggedInOBJ)
        {
            $this->con = $con;
            $this->video = $video;
            $this->userLoggedInOBJ = $userLoggedInOBJ;
        }

        public function create()
        {
           return $this->createCommentSection();
        }
        private function createCommentSection()
        {
            $commentNO = $this->video->getNumberOfComment();
            $postedBy = $this->userLoggedInOBJ->getUsername();
            $videoId = $this->video->getId();

            $profilePicture = ButtonProvider::createUserProfileButton($this->con, $postedBy);
            $commentAction = "postComment(this, $videoId, \"$postedBy\", null,\"comments\")";

            $commentButton = ButtonProvider::createButton("COMMENT", null, $commentAction, "postComment");

            $comments = $this->video->getComments();
            $commentHtml = "";
            foreach($comments as $comment_ttt )
            {
                $commentHtml .= $comment_ttt->create();
            }
            
            return "<div class='commentSection'>
                        <div class='headerComment'>
                            <span class='countComment'>$commentNO comments</span>

                            <div class='commentForm'>
                                $profilePicture
                                <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                                $commentButton
                            </div>
                        </div>

                        <div class='comments'>
                            $commentHtml
                        </div>
                    </div>";
        }
        
    }



?>