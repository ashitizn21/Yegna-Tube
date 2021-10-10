function postComment(button, videoId, postedBy, replyTo, containerClass) {
    var textarea = $(button).siblings("textarea");
    var commentText = textarea.val();
    textarea.val("");

    if(commentText)
    {

        $.post("ajax/postComment.php", {videoId: videoId, postedBy: postedBy, 
                                        responseTo: replyTo, commentText: commentText})
        .done(function(data){
            $("." + containerClass).prepend(data);
        });

    } else
    {
        alert("You can't insert empty comment!.");
    }
}