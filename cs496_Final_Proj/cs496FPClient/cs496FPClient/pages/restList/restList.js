// For an introduction to the Page Control template, see the following documentation:
// http://go.microsoft.com/fwlink/?LinkId=232511
(function () {
    "use strict";

    var httpClient;
    var httpClientPost;

    var locStor = Windows.Storage.ApplicationData.current.localSettings.values;

    WinJS.UI.Pages.define("/pages/restList/restList.html", {
        // This function is called whenever a user navigates to this page. It
        // populates the page elements with the app's data.
        ready: function (element, options) {
            // TODO: Initialize the page here.
            httpClientPost = new Windows.Web.Http.HttpClient();

            document.getElementById("getRest").addEventListener("click", getRests, false);
            document.getElementById("navNewEntry").addEventListener("click", navToNewEntry, false);
            document.getElementById("editEntry").addEventListener("click", funcEditEntry, false)
        }
    });

    function getRests() {
        var restClient;
        var currentUser = locStor["acceptedUserName"];

        restClient = new Windows.Web.Http.HttpClient();
        restClient.getStringAsync(new Windows.Foundation.Uri
            ("http://finalproj2983.azurewebsites.net/api/Restaurant?user=" + currentUser))
            .done(function (result) {
                if (typeof result !== 'undefined')
                {
                    var restInfo = JSON.parse(result);
                    document.getElementById("restName").innerHTML = restInfo[0].rName;
                    document.getElementById("restAddress").innerHTML = restInfo[0].userId;

                }
        });
    }

    function funcEditEntry()
    {
        var currentUser = Windows.Storage.ApplicationData.current.localSettings.values["acceptedUserName"];
        var endLine = '",';

        var pp = '{"id": "1'   + endLine;
        pp += '"rName": "' + document.getElementById("editRestName").value + endLine;
        pp += '"userId": "' + currentUser + endLine;
        pp = + '"address" : { "city" : "Corvallis" }';
        pp += '}';

        httpClientPost.putAsync(new Windows.Foundation.Uri("http://finalproj2983.azurewebsites.net/api/Restaurant/1"),
            Windows.Web.Http.HttpStringContent(pp, Windows.Storage.Streams.UnicodeEncoding.utf8,
            'application/json')).done;
    }

    function navToNewEntry() {
        if (Windows.Storage.ApplicationData.current.localSettings.values["passToken"] === true) {
            WinJS.Navigation.navigate('/pages/newEntry/newEntry.html');
        }
    }
})();
