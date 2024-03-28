<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Map with Clustered Comments</title>
                  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
                  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
                  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
                  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                  <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
                  <script src="https://cdn.tailwindcss.com"></script>
                <body>
                  <div id="map" style="height: 400px;"></div>
                  {{-- <textarea id="commentBox" placeholder="Type your comment here..."></textarea>
                  <button onclick="addComment()">Add Comment</button> --}}
                  @livewire('LeaveComment')
                <script>
                    var map = L.map('map', {
                      center: [40.1431, 47.5769],
                      zoom: 7,
                      maxBounds: L.latLngBounds([38, 44], [42, 52]),
                      maxBoundsViscosity: 1.0,
                      minZoom: 7,
                    });

                    // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(map);
                    var markers = L.markerClusterGroup({
                      disableClusteringAtZoom: 13,
                    });

                    var radiusCircleLayer = L.layerGroup().addTo(map); // Layer for the radius circle
                    var locationObtained = false;
                    var userLocation = null;
                    var commentsDictionary = {}; // Define commentsDictionary here

                    function getUserLocation() {
                      return new Promise((resolve, reject) => {
                        if (navigator.geolocation) {
                          navigator.geolocation.getCurrentPosition(
                            position => resolve([position.coords.latitude, position.coords.longitude]),
                            error => reject(error)
                          );
                        } else {
                          reject(new Error("Geolocation is not supported by this browser."));
                        }
                      });
                    }

                    getUserLocation()
                      .then(userLatLng => {
                        map.setView(userLatLng, 15);
                        var userMarker = L.circleMarker(userLatLng, { color: 'blue', radius: 1 }).addTo(map);
                        locationObtained = true;
                        userLocation = userLatLng;
                        showRadiusCircle(userLatLng, 1);
                      })
                      .catch(error => {
                        console.error(error);
                        alert("Unable to obtain location.");
                      });

                    function isLocationInAzerbaijan(lat, lng) {
                      return lat >= 38 && lat <= 42 && lng >= 44 && lng <= 52;
                    }

                    function addComment() {
                      if (!locationObtained) {
                        alert("Please wait for the location to be obtained.");
                        return;
                      }

                      var commentText = document.getElementById('commentBox').value;

                      if (commentText.trim() !== '') {
                        if (!isLocationInAzerbaijan(userLocation[0], userLocation[1])) {
                          alert("You can only leave comments within Azerbaijan.");
                          return;
                        }

                        checkNearbyComments(userLocation, 1);
                        console.log(commentsDictionary);
                        if (markers.getLayers().length === 0) {
                          // Create a new marker if there are no markers yet
                          var customIcon = L.divIcon({
                            className: 'custom-marker-icon',
                            html: '<div class="pointer"></div><div class="comment-text">' + commentText + '</div>',
                            iconSize: [150, 30],
                            iconAnchor: [75, 30],
                          });

                          var marker = L.marker(userLocation, { icon: customIcon });
                          marker.commentsCount = 1; // Initialize commentsCount for the first comment
                          markers.addLayer(marker);
                          map.addLayer(markers);
                        } else {
                          // Update the existing marker if it already exists
                          var existingMarker = markers.getLayers()[0];
                          var commentsCount = existingMarker.commentsCount || 0;
                          existingMarker.commentsCount = commentsCount + 1;
                          updateMarkerContent(existingMarker);
                        }

                        document.getElementById('commentBox').value = '';
                      }
                      Livewire.dispatch('commentAdded', { latitude: userLocation[0], longitude: userLocation[1] });
                    }

                    function checkNearbyComments(referenceLocation, radius) {
                      showRadiusCircle(referenceLocation, radius);
                      for (const locationStr in commentsDictionary) {
                        const location = locationStr.split(',').map(parseFloat);
                        const distance = calculateDistance(referenceLocation, location);

                        if (distance <= radius && locationStr !== userLocation.join(',')) {
                          notifyUserAboutNewComment(location);
                        }
                      }
                    }

                    function showRadiusCircle(center, radius) {
                      radiusCircleLayer.clearLayers(); // Clear previous circles
                      radiusCircleLayer.addLayer(L.circle(center, {
                        color: 'blue',
                        fillColor: 'blue',
                        fillOpacity: 0.3,
                        weight: 2,
                        radius: radius * 1000,
                      }));
                    }

                    function calculateDistance(coord1, coord2) {
                      const [lat1, lon1] = coord1;
                      const [lat2, lon2] = coord2;

                      const R = 6371;
                      const dLat = deg2rad(lat2 - lat1);
                      const dLon = deg2rad(lon2 - lon1);

                      const a =
                        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);

                      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                      const distance = R * c;

                      return distance;
                    }

                    function deg2rad(deg) {
                      return deg * (Math.PI / 180);
                    }

                    function notifyUserAboutNewComment(newCommentLocation) {
                      alert('New comment near your location!');
                    }

                    function updateMarkerContent(marker) {
                      var commentsCount = marker.commentsCount || 0;
                      var customIcon = L.divIcon({
                        className: 'custom-marker-icon',
                        html: '<div class="pointer"></div><div class="comment-text">' + commentsCount + '</div>',
                        iconSize: [150, 30],
                        iconAnchor: [75, 30],
                      });

                      marker.setIcon(customIcon);
                    }

                    function onMarkerClick(event) {
                      console.log('Click');
                      var layer = event.layer || (event.target && event.target._childClusters && event.target._childClusters[0]);

                      if (layer) {
                        var location = layer.getLatLng ? layer.getLatLng() : null;

                        if (location) {
                          var comments = commentsDictionary[location.toString()];
                          var commentsList = comments ? comments.map(comment => '<li>' + comment + '</li>').join('') : '';
                          var message = commentsList ? '<ul>' + commentsList + '</ul>' : 'No comments';
                          message += '<p>Total comments in this location: ' + (layer.commentsCount || 0) + '</p>';
                          alert(message);

                          // Move the map view to the clicked comment's location
                          map.setView(location, 15);
                        }else{
                          alert('Can not retrieve location service');
                        }
                      }
                    }

                    function notifyUserAboutNewComment(newCommentLocation) {
                      alert();
                      var distance = calculateDistance(userLocation, newCommentLocation);
                      // Check if the new comment is within the same radius
                      if (distance <= 5) {
                        alert('New comment near your location!');
                      }
                    }
                    // Function to create a button and attach it to the map
                    function createLocationButton() {
                      var locationButton = L.Control.extend({
                        options: {
                          position: 'topright' // Adjust the position as needed
                        },

                        onAdd: function (map) {
                          var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

                          container.innerHTML = '<button onclick="goToUserLocation()">Go to My Location</button>';

                          return container;
                        }
                      });

                      map.addControl(new locationButton());
                    }

                    // Function to move the map view to the user's location
                    function goToUserLocation() {
                      if (locationObtained) {
                        map.setView(userLocation, 15);
                      } else {
                        alert("User location not obtained yet.");
                      }
                    }

                    createLocationButton();
                    map.on('click', onMarkerClick);

                  </script>
                  <style>
                    .custom-marker-icon {
                      display: flex;
                      flex-direction: column;
                      align-items: center;
                    }
                    .pointer {
                      width: 0;
                      height: 0;
                      border-left: 10px solid transparent;
                      border-right: 10px solid transparent;
                      border-bottom: 15px solid #333;
                    }
                    .comment-text {
                      background-color: #333;
                      color: #111;
                      padding: 5px;
                      border-radius: 5px;
                      margin-top: -10px;
                    }
                    .dark-mode #map {
                      background-color: #333;
                    }

                    .dark-mode textarea,
                    .dark-mode .comment-text {
                      background-color: #333;
                      color: #fff;
                    }
                  </style>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
