(function () {
    "use strict";

    var app = WinJS.Application;

    WinJS.UI.Pages.define("/pages/business-map/business-map.html", {
        
        ready: function (element, options) {

            var cat_name = app.sessionState.catName;
            var cat_id = app.sessionState.catID;

            var c = new Windows.Web.Http.HttpClient();
            c.getStringAsync(new Windows.Foundation.Uri("http://web.engr.oregonstate.edu/~adamjosh/419/api.php?type=business&catid=" + cat_id))
                .done(function (businesses) {
                    document.frames['map'].postMessage(businesses, "ms-appx-web://" + document.location.host);

                    window.addEventListener("message", receiveMessage, false);
                    function receiveMessage(event) {
                        if (event.origin === "ms-appx-web://" + document.location.host) {
                            app.sessionState.pastView = 'map';
                            app.sessionState.businessID = event.data;
                            WinJS.Navigation.navigate('/pages/business-detail/business-detail.html');
                        }
                    }

                });

            $('.pagetitle').text(cat_name);
            
            $('.left-nav').on('click', function () {
                WinJS.Navigation.navigate('/pages/home/home.html');
            });

            $('.right-nav').on('click', function () {
                WinJS.Navigation.navigate('/pages/business-list/business-list.html');
            });

        },
    });
})();