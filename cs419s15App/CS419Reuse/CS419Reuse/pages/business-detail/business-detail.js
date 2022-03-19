(function () {
    "use strict";

    var app = WinJS.Application;

    WinJS.UI.Pages.define("/pages/business-detail/business-detail.html", {
        
        ready: function (element, options) {
           
            var business_id = app.sessionState.businessID;

            var c = new Windows.Web.Http.HttpClient();
            c.getStringAsync(new Windows.Foundation.Uri("http://web.engr.oregonstate.edu/~adamjosh/419/api.php?type=business&id=" + business_id))
                .done(function (result) {
                    var json = JSON.parse(result);
                    var business = json[0];
                    if (!$.isEmptyObject(business)) {
                        $('.pagetitle').text(business.name);
                        var phone_html = '<a href="tel:' + business.phone + '">' + business.phone + '</a>';
                        var website_html = '<a href="' + business.website + '">' + business.website + '</a>';
                        var address_query = encodeURI(business.address + ' ' + business.city + ' ' + business.state + ' ' + business.zip);
                        var address_html  = '<a href="bingmaps:?q=' + address_query + '">';
                        address_html += business.address + '<br>';
                        if (business.address2) {
                            address_html += business.address2 + '<br>';
                        }
                        address_html += business.city + ', ' + business.state + ' ' + business.zip + '<br>';

                        $('.phone-info').html(phone_html);
                        $('.website-info').html(website_html);
                        $('.address-info').html(address_html);
                        if (business.hours) {
                            $('.business-hours').html('Hours: ' + business.hours);
                        }
                        else {
                            $('.business-hours').remove();
                        }

                        if (business.notes) {
                            $('.business-notes').html(business.notes);
                        }
                        else {
                            $('.business-notes').remove();
                        }

                    }
                });

            var view = app.sessionState.pastView;
            if (view == 'map') {
                $('.left-nav').text("< Map");
                $('.left-nav').on('click', function () {
                    WinJS.Navigation.navigate('/pages/business-map/business-map.html');
                });
            }
            else {
                $('.left-nav').on('click', function () {
                    WinJS.Navigation.navigate('/pages/business-list/business-list.html');
                });
            }
        },
    });
})();
