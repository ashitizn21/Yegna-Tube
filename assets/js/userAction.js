function subscribe(userTo, userLogedIn, button)   // userLoggedIn == userFrom
{
    if(userTo == userLogedIn)
    {
        alert("You cannot SUBSCRIBE to yourself!.");
        return;
    }
// console.log(123)
    $.post("ajax/subscribe.php", {userTo: userTo, userFrom: userLogedIn})
    .done(function(data){
        
        if( data != null ){

            $(button).toggleClass("subscribe unsubscribe");
            
            var buttonText = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "SUBSCRIBED";
            $(button).text(buttonText + " " + data);

        } else
        {
            alert("some thing went wrong");
        }
    });
}