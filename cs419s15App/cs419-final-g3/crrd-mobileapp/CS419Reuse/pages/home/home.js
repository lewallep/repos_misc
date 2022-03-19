(function () {
    "use strict";

    var app = WinJS.Application;

    WinJS.UI.Pages.define("/pages/home/home.html", {
        
        ready: function (element, options) {

            // populate main categories dropdown
            var c = new Windows.Web.Http.HttpClient();
            c.getStringAsync(new Windows.Foundation.Uri("http://web.engr.oregonstate.edu/~adamjosh/419/api.php?type=category&parentid=none"))
                .done(function (result) {
                    var jsonResult = JSON.parse(result);
                    if (jsonResult.length > 0) {
                        var $categories = $('#categories');
                        $.each(jsonResult, function () {
                            $categories.append('<option value="' + this.id + '" data-name="' + this.name + '">' + this.name + '</option>');
                        });
                    }
                });

            // populate subcategories dropdown when main category is changed
            $('#categories').on('change', function () {
                var $this = $(this);
                var cat_id = $this.val();
                var $subcategories = $('#subcategories');
                if (cat_id != '') {
                    $subcategories.empty().append('<option value="All">All Subcategories</option>');
                    c.getStringAsync(new Windows.Foundation.Uri("http://web.engr.oregonstate.edu/~adamjosh/419/api.php?type=category&parentid=" + cat_id))
                        .done(function (result) {
                            var jsonResult = JSON.parse(result);
                            if (jsonResult.length > 0) {
                                $.each(jsonResult, function () {
                                    $subcategories.append('<option value="' + this.id + '" data-name="' + this.name + '">' + this.name + '</option>');
                                });
                            }
                        });
                    $subcategories.removeAttr('disabled');
                    $('.category-select-submit').removeAttr('disabled');
                }
                else {
                    $subcategories.empty().append('<option>Select main category first</option>');
                    $subcategories.attr('disabled', 'disabled');
                    $('.category-select-submit').attr('disabled', 'disabled');
                }
            });

            $('.category-select-submit').on('click', function (e) {
                e.preventDefault();
                var $categories = $('#categories');
                var $subcategories = $('#subcategories');
                if ($subcategories.val() == 'All') {
                    app.sessionState.catID = $categories.val();
                    app.sessionState.catName = $('#categories option:selected').data('name');
                }
                else {
                    app.sessionState.catID = $subcategories.val();
                    app.sessionState.catName = $('#subcategories option:selected').data('name');
                }
                WinJS.Navigation.navigate('/pages/business-list/business-list.html');
            });

        },
    });
})();