/* eslint-env browser */

/**
 * initMap - initializes all maps on the page
 */
function initMap() {
  const maps = document.getElementsByClassName('mapblock-map');
  if (maps.length < 1) {
    throw (new Error('No map found to initialize. Add the class  \'mapblock-map \' to the node you want to init on.'));
  }
  const { google } = window;
  for (let i = 0; i < maps.length; i += 1) {
    const mapBlock = maps[i];
    const marker = JSON.parse(mapBlock.dataset.marker);
    const mapoptions = JSON.parse(mapBlock.dataset.mapoptions);

    const bounds = new google.maps.LatLngBounds();

    const initialPosition = marker.length > 0 ? marker[0] : { lat: 0, lng: 0 };
    const map = new google.maps.Map(
      mapBlock, { center: initialPosition, zoom: mapoptions.zoom, styles: mapoptions.styles },
    );

    for (let j = 0; j < marker.length; j += 1) {
      const newMarker = new google.maps.Marker({
        position: marker[j],
        map,
      });
      if (marker[j].info) {
        const infowindow = new google.maps.InfoWindow({
          content: decodeURIComponent(marker[j].info),
        });
        newMarker.addListener('click', () => {
          infowindow.open({
            anchor: newMarker,
            map,
            shouldFocus: false,
          });
        });
        if (marker[j].showInfo) {
          infowindow.open({
            anchor: newMarker,
            map,
            shouldFocus: false,
          });
        }
      }
      bounds.extend(newMarker.getPosition());
    }

    if (mapoptions.fit && marker.length > 1) {
      map.fitBounds(bounds);
    }
  }
}
window.initMap = initMap;
