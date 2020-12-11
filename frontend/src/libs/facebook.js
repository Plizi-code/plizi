import client_ids from './social_networks_client_ids';

// Init Facebook SDK.
window.fbAsyncInit = function() {
    FB.init({
        appId      : client_ids.facebook,
        cookie     : true,
        xfbml      : true,
        version    : 'v6.0'
    });
    FB.AppEvents.logPageView();
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
