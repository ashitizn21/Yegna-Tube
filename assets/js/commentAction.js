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

function toggleReply(button) {
    var parent = $(button).closest(".itemContainer");
    var commentForm = parent.find(".commentForm").first();

    commentForm.toggleClass("hidden");
}

function likeComment(commentId, button, videoId)
{
  // videoId the first is variable for ajax and the second one is which variable is passed
  
    $.post("ajax/likeComment.php", { commentId: commentId, videoId: videoId })
    .done(function(numToChange) {
      
      var likeButton = $(button);
      var dislikeButton = $(button).siblings(".dislikeButton");

      likeButton.addClass("active");
      dislikeButton.removeClass("active");
      
      var likesCount = $(button).siblings(".likesCount");
      // alert(" ^^ " + likesCount);
      // console.log(likesCount);
      updateLikesValue(likesCount, numToChange);
  
      if (numToChange < 0) {
        likeButton.removeClass("active");
        likeButton
        .find("img:first")
        .attr("src", "assets/images/icons/thumb-up.png");
      } else {
        
        likeButton
          .find("img:first")
          .attr("src", "assets/images/icons/thumb-up-active.png");
      }
      dislikeButton
        .find("img:first")
        .attr("src", "assets/images/icons/thumb-down.png");
    });
}

function dislikeComment(commentId, button, videoId)
{
  // alert("**from dislike**js");

    $.post("ajax/dislikeComment.php", { commentId: commentId, videoId: videoId })
    .done(function (returnedNum) {
        var dislikeButton = $(button);
        var likeButton = $(button).siblings(".likeButton");
    
        dislikeButton.addClass("active");
        likeButton.removeClass("active");

        var likesCount = $(button).siblings(".likesCount");

        updateLikesValue(likesCount, returnedNum);
    
        if ( returnedNum > 0) {
          dislikeButton.removeClass("active");
          dislikeButton
            .find("img:first")
            .attr("src", "assets/images/icons/thumb-down.png");
        } else {
          dislikeButton
            .find("img:first")
            .attr("src", "assets/images/icons/thumb-down-active.png");
        }
    
        likeButton
          .find("img:first")
          .attr("src", "assets/images/icons/thumb-up.png");
      });
}

function updateLikesValue(element, num) {
  // var likesCountValue = element.text() != NaN ? element.text() : 0;
  var likesCountValue = element.html()  != NaN ? element.text() : 0;
  // alert(likesCountValue + " && " + num);
  var r = parseInt(likesCountValue) + parseInt(num);
  element.html(r);
}

function getReplies(commentId, button, videoId)
{
  $.post("ajax/getCommentReplies.php", {commentId: commentId, videoId:videoId})
    .done(function()){

    }
}