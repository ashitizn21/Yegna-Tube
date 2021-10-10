<?php
    // require_once("includes/classes/User.php");

    class ButtonProvider
    {
        public static function createButton($text, $imageSrc, $action, $class)
        {
            $image = ($imageSrc == null ) ? "" : "<img src='$imageSrc'>";
            return "<button class='$class' onclick='$action'>
                        $image
                        <span class='text'>$text</span>
                    </button>";
        }

        public static function createUserProfileButton($con, $username)
        {
            $userOBJ = new User($con, $username);
            $profilePic = $userOBJ->getProfilPic();
            $link = "profile.php?username=$username";

            return "<a href='$link'>
                        <img src='$profilePic' class='profilePicture'>
                    </a>";
        }

        public static function createEditVideoButton($videoId)
        {
            $href = "editVideo.php?videoId=$videoId";
            $button = ButtonProvider::createHyperLinkButton("EDIT VIDEO", null, $href, "edit button");

            return "<div class='editVideoButtonContainer'>
                        $button
                    </div>";
        }

        public static function createHyperLinkButton($text, $imageSrc, $href, $class)
        {
            $image = ($imageSrc == null ) ? "" : "<img src='$imageSrc'>";
            return "<a href='$href'>
                        <button class='$class' >
                            $image
                            <span class='text'>$text</span>
                        </button>
                    </a>";
        }

        public static function createSubscriberButton($con, $userToObj, $userLoggedInObj)
        {
            $userTo = $userToObj->getUsername();
            $userLoggedIn = $userLoggedInObj->getUsername();
            
               $isSubscribedTo = $userLoggedInObj->isSubscribedTo($userTo);
               $buttonText = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
               $buttonText .= " " . $userLoggedInObj->getSubscribersCount();

               $buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
                $action = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

               $button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

               return "<div class='subscribeButtonContainer'>
                            $button
                        </div>";
        }
    }

?>