$(document).ready(function(){
    $(".navShowHide").on("click",function(){

        var main = $("#mainSectionContainer");
        var nav = $("#sideNavContainer")
        if(main.hasClass("leftPadding")){
            nav.hide();
        }else{
            nav.show();
        }

        main.toggleClass("leftPadding");
    })

    // var videoContent = document.getElementById("videoContent");
    // var actualVideoWidth = videoContent.videoWidth();
    // var actualVideoHeight = videoContent.videoHeight();

    // now check their sizes
        // TODO:
    // if() ...

})