
function likeVideo(button, videoId)
{
    
    $.post("ajax/likeVideo.php",{videoId :videoId})   // videoId the first is variable for ajax and the second one is which variable is passed
    .done(function(data){
        

        var likeButton = $(button);
        var dislikeButton = $(button).siblings(".dislikeButton");

        likeButton.addClass("active");
        dislikeButton.remove("active");

        var result = JSON.parse(data);

        console.log(result);
    });
}

function dislikeVideo(button, videoId)
{
    alert("dislike button is pushed");
}