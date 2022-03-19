(function () {
    "use strict";

    var app = WinJS.Application;

    WinJS.UI.Pages.define("/pages/business-list/business-list.html", {
        
        ready: function (element, options) {

            var cat_name = app.sessionState.catName;
            var cat_id = app.sessionState.catID;
            var user_lat = app.sessionState.userLat;
            var user_long = app.sessionState.userLong;

            var c = new Windows.Web.Http.HttpClient();
            c.getStringAsync(new Windows.Foundation.Uri("http://web.engr.oregonstate.edu/~adamjosh/419/api.php?type=business&catid=" + cat_id))
                .done(function (result) {
                    var businesses = JSON.parse(result);
                    if (businesses.length > 0) {

                        $.each(businesses, function() {
                            var distance = calc_distance(this.latitude, this.longitude, user_lat, user_long);
                            this.distance = distance.toFixed(2);
                        });
                        businesses.sort(function (a, b) {
                            if (a.distance > b.distance) {
                                return 1;
                            }
                            if (a.distance < b.distance) {
                                return -1;
                            }
                            return 0;
                        });
                        
                        if (!user_lat || !user_long) {
                            $('.business-distance').hide();
                        }

                        var template_element = document.querySelector('#business-row-template');
                        var render_element = document.querySelector('#business-rows');
                        render_element.innerHTML = '';
                        var template_control = template_element.winControl;
                        $.each(businesses, function () {
                            template_control.render(this).done(function (result) {
                                render_element.appendChild(result);
                            });
                        });

                        $('#business-rows .win-template').on('click', function () {
                            app.sessionState.pastView = 'list';
                            app.sessionState.businessID = $(this).children('.business-id').val();
                            WinJS.Navigation.navigate('/pages/business-detail/business-detail.html');
                        });
                    }
                });

            $('.pagetitle').text(cat_name);

            $('.left-nav').on('click', function () {
                WinJS.Navigation.navigate('/pages/home/home.html');
            });
            
            $('.right-nav').on('click', function () {
                WinJS.Navigation.navigate('/pages/business-map/business-map.html');
            });

        },
    });
})();