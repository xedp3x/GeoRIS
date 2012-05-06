if (!Array.prototype.forEach)
{
  Array.prototype.forEach = function(fun /*, thisp*/)
  {
    var len = this.length;
    if (typeof fun != "function")
      throw new TypeError();

    var thisp = arguments[1];
    for (var i = 0; i < len; i++)
    {
      if (i in this)
        fun.call(thisp, this[i], i, this);
    }
  };
}


var map, vectors;

function init_edit(URL, la, lb, lc, ld){
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
	map.addLayer(gmap);


	var ghyb = new OpenLayers.Layer.Google(
			"Google Satellit",
			{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20}
	);
	map.addLayer(ghyb);
	
	
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

	}
	vectors = new OpenLayers.Layer.Vector("Vector Layer");
	map.addLayer(vectors);

	map.addControl(new OpenLayers.Control.LayerSwitcher());
	map.addControl(new OpenLayers.Control.MousePosition());
	map.addControl(new OpenLayers.Control.EditingToolbar(vectors));
	

	map.zoomToExtent(new OpenLayers.Bounds(la, lb, lc, ld ).transform(map.displayProjection, map.projection));
}

function printBr(element, index, array) {
	var geojson = new OpenLayers.Format.GeoJSON({
		'internalProjection': map.baseLayer.projection,
		'externalProjection': new OpenLayers.Projection("EPSG:4326")
	});
	
	var str = geojson.write(element, false);
	document.getElementById('map_edit').innerHTML = document.getElementById('map_edit').innerHTML + "<textarea name='map_edit[]'>"+str+"</textarea>";
}

function get_map() {
	vectors.features.forEach(printBr);
	return true;
}