const citymap = {
    chicago: {
      center: { lat: 41.878, lng: -87.629 },
      population: 50000,
    },
  };

function initMap() {
    // Create the map.
    const map = new google.maps.Map(document.getElementById("delivery_map"), 
    {
        zoom: 4,
        center: { lat: 37.09, lng: -95.712 },
        mapTypeId: "terrain",
    });
    
    for (const city in citymap) 
    {
        // Add the circle for this city to the map.
        const cityCircle = new google.maps.Circle({
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
            map,
            center: citymap[city].center,
            radius: Math.sqrt(citymap[city].population) * 100,
        });
    }
}