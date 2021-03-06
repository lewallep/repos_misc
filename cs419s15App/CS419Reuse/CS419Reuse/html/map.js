$(document).ready(function () {

    var map = null;

    window.addEventListener("message", receiveMessage, false);
    function receiveMessage(event) {
        if (event.origin === "ms-appx://" + document.location.host) {

            var businesses = JSON.parse(event.data);
            if (businesses.length > 1) {

                var locations = [];
                $.each(businesses, function () {
                    locations.push(new Microsoft.Maps.Location(this.latitude, this.longitude));
                });
                var rectBound = Microsoft.Maps.LocationRect.fromLocations(locations);
                var mapOptions = {
                    credentials: "AhTTNOioICXvPRPUdr0_NAYWj64MuGK2msfRendz_fL9B1U6LGDymy2OhbGj7vhA",
                    bounds: rectBound,
                    mapTypeId: Microsoft.Maps.MapTypeId.road
                };
            }
            else {
                var mapOptions = {
                    credentials: "AhTTNOioICXvPRPUdr0_NAYWj64MuGK2msfRendz_fL9B1U6LGDymy2OhbGj7vhA",
                    center: new Microsoft.Maps.Location(businesses[0].latitude, businesses[0].longitude),
                    zoom: 15,
                    mapTypeId: Microsoft.Maps.MapTypeId.road
                };
            }
            var map = new Microsoft.Maps.Map(document.getElementById("mapDiv"), mapOptions);

            $.each(businesses, function () {

                var loc = new Microsoft.Maps.Location(this.latitude, this.longitude);
                var pin = new Microsoft.Maps.Pushpin(loc);
                var infoboxOptions = {
                    visible: false,
                    htmlContent: '<div class="tooltip Infobox"><div class="name">'+this.name+'</div><button type="button" class="details-link" data-id="'+this.id+'">Get Details</button></div>'
                };
                var infobox = new Microsoft.Maps.Infobox(pin.getLocation(), infoboxOptions);

                Microsoft.Maps.Events.addHandler(map, 'click', function () {
                    infobox.setOptions({ visible: false });
                });
                Microsoft.Maps.Events.addHandler(pin, 'click', function () {
                    if (infobox.getVisible()) {
                        infobox.setOptions({ visible: false });
                    }
                    else {
                        infobox.setOptions({ visible: true });
                    }
                });
                Microsoft.Maps.Events.addHandler(map, 'viewchange', function () {
                    infobox.setOptions({ visible: false });
                });

                map.entities.push(pin);
                map.entities.push(infobox);
            });

        }
    }
    $(document).arrive('.details-link', function () {
        bind_details_click();
    });

});

function bind_details_click() {
    $('.details-link').on('click', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        console.log('ID: ' + id);
        parent.postMessage(id, "ms-appx://" + document.location.host);
    });
}

/*
 * arrive.js
 * v2.2.0
 * https://github.com/uzairfarooq/arrive
 * MIT licensed
 *
 * Copyright (c) 2014-2015 Uzair Farooq
 */

(function(n,q,v){function r(a,b,c){if(e.matchesSelector(a,b.selector)&&(a._id===v&&(a._id=w++),-1==b.firedElems.indexOf(a._id))){if(b.options.onceOnly)if(0===b.firedElems.length)b.me.unbindEventWithSelectorAndCallback.call(b.target,b.selector,b.callback);else return;b.firedElems.push(a._id);c.push({callback:b.callback,elem:a})}}function p(a,b,c){for(var d=0,f;f=a[d];d++)r(f,b,c),0<f.childNodes.length&&p(f.childNodes,b,c)}function t(a){for(var b=0,c;c=a[b];b++)c.callback.call(c.elem)}function x(a,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   b){a.forEach(function(a){var d=a.addedNodes,f=a.target,e=[];null!==d&&0<d.length?p(d,b,e):"attributes"===a.type&&r(f,b,e);t(e)})}function y(a,b){a.forEach(function(a){a=a.removedNodes;var d=[];null!==a&&0<a.length&&p(a,b,d);t(d)})}function z(a){var b={attributes:!1,childList:!0,subtree:!0};a.fireOnAttributesModification&&(b.attributes=!0);return b}function A(a){return{childList:!0,subtree:!0}}function k(a){a.arrive=l.bindEvent;e.addMethod(a,"unbindArrive",l.unbindEvent);e.addMethod(a,"unbindArrive",
	l.unbindEventWithSelectorOrCallback);e.addMethod(a,"unbindArrive",l.unbindEventWithSelectorAndCallback);a.leave=m.bindEvent;e.addMethod(a,"unbindLeave",m.unbindEvent);e.addMethod(a,"unbindLeave",m.unbindEventWithSelectorOrCallback);e.addMethod(a,"unbindLeave",m.unbindEventWithSelectorAndCallback)}if(n.MutationObserver&&"undefined"!==typeof HTMLElement){var w=0,e=function(){var a=HTMLElement.prototype.matches||HTMLElement.prototype.webkitMatchesSelector||HTMLElement.prototype.mozMatchesSelector||HTMLElement.prototype.msMatchesSelector;
		return{matchesSelector:function(b,c){return b instanceof HTMLElement&&a.call(b,c)},addMethod:function(a,c,d){var f=a[c];a[c]=function(){if(d.length==arguments.length)return d.apply(this,arguments);if("function"==typeof f)return f.apply(this,arguments)}}}}(),B=function(){var a=function(){this._eventsBucket=[];this._beforeRemoving=this._beforeAdding=null};a.prototype.addEvent=function(a,c,d,f){a={target:a,selector:c,options:d,callback:f,firedElems:[]};this._beforeAdding&&this._beforeAdding(a);this._eventsBucket.push(a);
		return a};a.prototype.removeEvent=function(a){for(var c=this._eventsBucket.length-1,d;d=this._eventsBucket[c];c--)a(d)&&(this._beforeRemoving&&this._beforeRemoving(d),this._eventsBucket.splice(c,1))};a.prototype.beforeAdding=function(a){this._beforeAdding=a};a.prototype.beforeRemoving=function(a){this._beforeRemoving=a};return a}(),u=function(a,b,c){function d(a){"number"!==typeof a.length&&(a=[a]);return a}var f=new B,e=this;f.beforeAdding(function(b){var d=b.target,h;if(d===n.document||d===n)d=
		document.getElementsByTagName("html")[0];h=new MutationObserver(function(a){c.call(this,a,b)});var g=a(b.options);h.observe(d,g);b.observer=h;b.me=e});f.beforeRemoving(function(a){a.observer.disconnect()});this.bindEvent=function(a,c,h){if("undefined"===typeof h)h=c,c=b;else{var g={},e;for(e in b)g[e]=b[e];for(e in c)g[e]=c[e];c=g}e=d(this);for(g=0;g<e.length;g++)f.addEvent(e[g],a,c,h)};this.unbindEvent=function(){var a=d(this);f.removeEvent(function(b){for(var c=0;c<a.length;c++)if(b.target===a[c])return!0;
		return!1})};this.unbindEventWithSelectorOrCallback=function(a){var b=d(this);f.removeEvent("function"===typeof a?function(c){for(var d=0;d<b.length;d++)if(c.target===b[d]&&c.callback===a)return!0;return!1}:function(c){for(var d=0;d<b.length;d++)if(c.target===b[d]&&c.selector===a)return!0;return!1})};this.unbindEventWithSelectorAndCallback=function(a,b){var c=d(this);f.removeEvent(function(d){for(var e=0;e<c.length;e++)if(d.target===c[e]&&d.selector===a&&d.callback===b)return!0;return!1})};return this},
	l=new u(z,{fireOnAttributesModification:!1,onceOnly:!1},x),m=new u(A,{},y);q&&k(q.fn);k(HTMLElement.prototype);k(NodeList.prototype);k(HTMLCollection.prototype);k(HTMLDocument.prototype);k(Window.prototype)}})(this,"undefined"===typeof jQuery?null:jQuery,void 0);