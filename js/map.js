var lon = 5;
var lat = 40;
var zoom = 5;
var map, layer;


OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
		defaultHandlerOptions: {
		'single': true,
		'double': false,
		'pixelTolerance': 0,
		'stopSingle': false,
		'stopDouble': false
	},
	
	initialize: function(options) {
		this.handlerOptions = OpenLayers.Util.extend(
				{}, this.defaultHandlerOptions
		);
		OpenLayers.Control.prototype.initialize.apply(
				this, arguments
		); 
		this.handler = new OpenLayers.Handler.Click(
				this, {
					'click': this.trigger
				}, this.handlerOptions
		);
	}, 
	
	trigger: function(e) {
		var lonlat = map.getLonLatFromViewPortPx(e.xy);
		try {
			document.getElementById('click_lat').value = lonlat.lat;
			document.getElementById('click_lon').value = lonlat.lon;
		} catch (e) { }
	}
	
});


function init(URL, la, lb, lc, ld){
	var options = {
			projection: new OpenLayers.Projection("EPSG:900913"),
			displayProjection: new OpenLayers.Projection("EPSG:4326"),
			units: "m",
			numZoomLevels: 19,
			maxResolution: 156543.0339,
			maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34,
					20037508.34, 20037508.34)
	}; 

	map = new OpenLayers.Map('map', options);

	var mapnik = new OpenLayers.Layer.TMS(
			"OpenStreetMap",
			"http://tile.openstreetmap.org/",
			{
				type: 'png', getURL: osm_getTileURL, opacity: 0.7,
				displayOutsideMaxExtent: true,
				attribution: '<a href="http://www.openstreetmap.org/">(CC-BY-SA) OpenStreetMap</a>'
			}

	);
	map.addLayer(mapnik);

	var gmap = new OpenLayers.Layer.Google(
			"Google Maps", // the default
			{numZoomLevels: 20}
	);
	//map.addLayer(gmap);


	var ghyb = new OpenLayers.Layer.Google(
			"Google Satellit",
			{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20}
	);
	//map.addLayer(ghyb);

	if (URL != ""){
		map.addLayer(new OpenLayers.Layer.GML("GeoRIS", URL, 
				{
			format: OpenLayers.Format.KML, 
			projection: map.displayProjection,
			formatOptions: {
			extractStyles: true, 
			extractAttributes: true
		}
				}));

		selectControl = new OpenLayers.Control.SelectFeature(map.layers[1],
				{onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});
		map.addControl(selectControl);
		selectControl.activate();   
	}
	map.zoomToExtent(new OpenLayers.Bounds(la, lb, lc, ld ).transform(map.displayProjection, map.projection));
	
	
	var click = new OpenLayers.Control.Click();
    map.addControl(click);
    click.activate();
    
    
    //map.addControl(new OpenLayers.Control.MousePosition());
}
function onPopupClose(evt) {
	selectControl.unselect(selectedFeature);
}
function onFeatureSelect(feature) {
	selectedFeature = feature;
	popup = new OpenLayers.Popup.FramedCloud("chicken", 
			feature.geometry.getBounds().getCenterLonLat(),
			new OpenLayers.Size(100,100),
			"<h2>"+feature.attributes.name + "</h2>" + feature.attributes.description,
			null, true, onPopupClose);
	feature.popup = popup;
	map.addPopup(popup);
}
function onFeatureUnselect(feature) {
	map.removePopup(feature.popup);
	feature.popup.destroy();
	feature.popup = null;
}
function osm_getTileURL(bounds) {
	var res = this.map.getResolution();
	var x = Math.round((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
	var y = Math.round((this.maxExtent.top - bounds.top) / (res * this.tileSize.h));
	var z = this.map.getZoom();
	var limit = Math.pow(2, z);

	if (y < 0 || y >= limit) {
		return OpenLayers.Util.getImagesLocation() + "404.png";
	} else {
		x = ((x % limit) + limit) % limit;
		return this.url + z + "/" + x + "/" + y + "." + this.type;
	}
}