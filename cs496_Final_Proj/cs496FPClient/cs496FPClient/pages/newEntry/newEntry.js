// For an introduction to the Page Control template, see the following documentation:
// http://go.microsoft.com/fwlink/?LinkId=232511
(function () {
    "use strict";

    var httpClientPost;

    WinJS.UI.Pages.define("/pages/newEntry/newEntry.html", {
        // This function is called whenever a user navigates to this page. It
        // populates the page elements with the app's data.
        ready: function (element, options) {
            // TODO: Initialize the page here.
            httpClientPost = new Windows.Web.Http.HttpClient();

            document.getElementById("postSubmit").addEventListener("click", newRest, false);
        }
    });

    function newRest() {
        var currentUser = Windows.Storage.ApplicationData.current.localSettings.values["acceptedUserName"];

        //define my post payload here.
        var endLine = '",';

        var postPayload = '{"id": "' + document.getElementById("rid").value + endLine;
        postPayload += '"rName": "' + document.getElementById("rname").value + endLine;
        postPayload += '"userId": "' + currentUser + endLine;
        postPayload += '"fName": "' + document.getElementById("fname").value + endLine;
        postPayload += '"lName": "' + document.getElementById("lname").value + endLine;
        postPayload += '"menuItems": [{"id": "1' + endLine;
        postPayload += '"itemName": "' + document.getElementById("itemName").value + endLine;
        postPayload += '"calories": "' + document.getElementById("calories").value + '}],';
        postPayload += '}';

        httpClientPost.postAsync(new Windows.Foundation.Uri("http://finalproj2983.azurewebsites.net/api/Restaurant"),
            Windows.Web.Http.HttpStringContent(postPayload, Windows.Storage.Streams.UnicodeEncoding.utf8,
            'application/json')).done;
    }

    function navToMap() {
        WinJS.Navigation.navigate('/pages/newEntry/newEntry.html');
    }
})();
