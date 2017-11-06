// Store locator with customisations
// - custom marker
// - custom info window (using Info Bubble)
// - custom info window content (+ store hours)

var slPanel;

var ICON = new google.maps.MarkerImage('/skin/frontend/polar/default/images/store-locator-icon.png', null, null,
    new google.maps.Point(14, 13));

var SHADOW = new google.maps.MarkerImage('/skin/frontend/polar/default/images/medicare-shadow.png', null, null,
new google.maps.Point(14, 13));

google.maps.event.addDomListener(window, 'load', function() {
  var map = new google.maps.Map(document.getElementById('map-canvas'), {
    center: new google.maps.LatLng(-28, 135),
    scrollwheel: false,
    navigationControl: false,
    mapTypeControl: false,
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var panelDiv = document.getElementById('store-locator-search'),
      data = new MedicareDataSource,
      view = new storeLocator.View(map, data, {
        geolocation: false,
        features: data.getFeatures()
      });

  view.createMarker = function(store) {
    var markerOptions = {
      position: store.getLocation(),
      icon: ICON,
      shadow: SHADOW,
      title: store.getDetails().title
    };
    return new google.maps.Marker(markerOptions);
  }

  var infoBubble = new InfoBubble;
  view.getInfoWindow = function(store) {
    if (!store) {
      return infoBubble;
    }

    var details = store.getDetails(),
        html = ['<div class="store"><div class="title"><strong>', details.title, '</strong></div>',
                '<div class="address">', details.address, '</div>',
                 '<div class="hours misc">', details.hours, '</div>',
                '</div>'].join('');
    
    infoBubble.setContent(jQuery(html)[0]);
    return infoBubble;
  };

  slPanel = new storeLocator.Panel(panelDiv, {
    view: view
  });
  
  if (typeof someAddress !== 'undefined') {
    slPanel.searchPosition(someAddress);
    jQuery('.location-search input').val(someAddress);
  } 
  
});