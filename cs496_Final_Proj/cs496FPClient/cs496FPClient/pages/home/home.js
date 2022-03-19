(function () {
    "use strict";
    
    var filter;
    var httpClient;
    var httpPromise;

    var httpClientPost;
    var filterPost;

    var locStor = Windows.Storage.ApplicationData.current;

    WinJS.UI.Pages.define("/pages/home/home.html", {
        ready: function (element, options) {
            // code from me.
            document.getElementById("loginCredentials").addEventListener("click", userLogin, false);
            document.getElementById("navPage2").addEventListener("click", navToRList, false);
            document.getElementById("newUserReg").addEventListener("click", userReg, false);
            document.getElementById("logout").addEventListener("click", logOutUser, false);

            filter = new Windows.Web.Http.Filters.HttpBaseProtocolFilter();
            httpClient = new Windows.Web.Http.HttpClient(filter);

            filterPost = new Windows.Web.Http.Filters.HttpBaseProtocolFilter();
            httpClientPost = new Windows.Web.Http.HttpClient(filterPost);
        }
    });

    function userLogin() {
        var userClient;
        var returningPass = document.getElementById("passwordIn").value;
        var returningName = document.getElementById("usernameIn").value;

        userClient = new Windows.Web.Http.HttpClient();
        userClient.getStringAsync(new Windows.Foundation.Uri("http://finalproj2983.azurewebsites.net/api/User?userName="
            + returningName + "&password=" + returningPass))
            .done(function (result) {
                var userInfo = JSON.parse(result);
                if (userInfo[0].userName === returningName && userInfo[0].password === returningPass) {
                    var appData = Windows.Storage.ApplicationData.current;
                    appData.localSettings.values["passToken"] = true;
                    appData.localSettings.values["acceptedUserName"] = userInfo[0].userName;
                }
        });
    }

    function userReg()
    {
        var newUserClient;
        var newName = document.getElementById("newNameIn").value;
        var newPass = document.getElementById("newPassIn").value;

  
        //define my post payload here.
        var endLine = '",';

        var postPayload = '{"userName": "' + newName + endLine;
        postPayload += '"password": "' + newPass + '"';
        postPayload += '}';

        httpClientPost.postAsync(new Windows.Foundation.Uri("http://finalproj2983.azurewebsites.net/api/User"),
            Windows.Web.Http.HttpStringContent(postPayload, Windows.Storage.Streams.UnicodeEncoding.utf8,
            'application/json')).done;
               
       
    }

    function navToRList()
    {
        if (Windows.Storage.ApplicationData.current.localSettings.values["passToken"] === true)
        {
            WinJS.Navigation.navigate('/pages/restList/restList.html');
        } 
    }

    function logOutUser()
    {
        locStor.localSettings.values["passToken"] = false;
    }

})();
