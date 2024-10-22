<?php
// require_once __DIR__."/config.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEV SEC IT Maps</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
    #map {
        height: 100vh;
        width: 100%;
    }
    </style>
</head>

<body>

    <div class="top-header">
        <div id="mySidepanel" class="sidepanel">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <ul class="menu">
                <li><i class="fas fa-user"></i> My Contributions</li>
                <li><i class="fas fa-share-alt"></i> Location Sharing</li>
                <li><i class="fas fa-briefcase"></i> My Business</li>
                <li><i class="fas fa-map-marker-alt"></i> Embed Map</li>
                <li><i class="fas fa-cogs"></i> Developer Settings</li>
                <li><i class="fas fa-plus-circle"></i> Add Missing Place</li>
                <li><i class="fas fa-lightbulb"></i> Tips and Tricks</li>
                <li><i class="fas fa-question-circle"></i> Get Help</li>
                <li><i class="fas fa-exclamation-circle"></i> Report Problem</li>
            </ul>
        </div>
        <button class="openbtn" onclick="openNav()">&#9776; </button>
        <input type="text" id="search-input" placeholder="Search for a place" oninput="suggestLocation()"
            autocomplete="off" />
        <ul class="search-suggestions" id="suggestions-list"></ul>
        <button id="search-btn">Search</button>
    </div>

    <!-- Info Card Container -->
    <div class="info-card" id="info-card">
        <img id="location-image" class="info-image" src="" alt="Location Image" style="display: none;" />
        <!-- <h3>Location Info</h3> -->
        <div id="location-info">

            <!-- Location Symbol and Text -->
            <div class="info-content">
                <span class="info-symbol"><i class="fas fa-map-marker-alt"></i></span>
                <p>Search for a location to see details here.</p>
            </div>
            <!-- Stylish Compass Direction Symbol -->
            <div class="info-content">
                <span class="direction-symbol"><i class="fas fa-compass"></i></span>
                <p>Direction information will appear here.</p>
            </div>

            <!-- Stylish Direction Button with Symbol -->
            <button class="button direction-button" onclick="window.location.href='direction.php'">
                <i class="fas fa-arrow-right"></i> Get Directions
            </button>
            <!-- Stylish Save Button with Symbol -->
            <button class="button save-button" onclick="saveLocation()">
                <i class="fas fa-save"></i> Save Location
            </button>
            <!-- Stylish Nearby Button with Symbol -->
            <button class="button nearby-button" onclick="findNearby()">
                <i class="fas fa-map-signs"></i> Find Nearby
            </button>
            <!-- Stylish Save to Phone Button with Symbol -->
            <button class="button save-phone-button" onclick="saveToPhone()">
                <i class="fas fa-mobile-alt"></i> Save to Phone
            </button>
            <!-- Stylish Share Button with Symbol -->
            <button class="button share-button" onclick="shareLocation()">
                <i class="fas fa-share-alt"></i> Share Location
            </button>
        </div>
    </div>

    <div id="map"></div>
    <script>
    function openNav() {
        document.getElementById("mySidepanel").style.width = "200px";
    }

    /* Set the width of the sidebar to 0 (hide it) */
    function closeNav() {
        document.getElementById("mySidepanel").style.width = "0";

    }
    </script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
    var map = L.map('map').setView([25.6498563180431, 88.14701515680098], 5);
    var marker, circle;

    if (!navigator.geolocation) {
        console.log("Your browser doesn't support geolocation feature!")
    } else {

        navigator.geolocation.getCurrentPosition(getPosition)

    }


    function getPosition(position) {

        var lat = position.coords.latitude
        var long = position.coords.longitude
        var accuracy = position.coords.accuracy

        if (marker) {
            map.removeLayer(marker)
        }

        if (circle) {
            map.removeLayer(circle)
        }

        marker = L.marker([lat, long])
        circle = L.circle([lat, long], {
            radius: accuracy
        })

        var featureGroup = L.featureGroup([marker, circle]).addTo(map)

        map.fitBounds(featureGroup.getBounds())

        setCookie("lat", lat, 1);
        setCookie("lon", long, 1);

        var schoolIcon = L.icon({
            iconUrl: './school.svg',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var hospitalIcon = L.icon({
            iconUrl: './hospital.svg',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        var policeIcon = L.icon({
            iconUrl: './school.svg',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var officeIcon = L.icon({
            iconUrl: './location.svg',
            iconSize: [35, 51],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        var defaultIcon = L.icon({
            iconUrl: './location.svg',
            iconSize: [35, 51],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var locations;
        let postObj = {
            startLat: lat,
            startLon: long,
            GET_LAT_LON_ALL_BY_LAT_LON: "true"
        }

        let post = JSON.stringify(postObj)
        const url = "https://maps.devsecit.com/ajax/"
        let xhr = new XMLHttpRequest()
        xhr.open('POST', url, true)
        xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
        xhr.send(post);
        xhr.onload = function() {
            // console.warn(xhr.responseText);
            locations = JSON.parse(xhr.responseText);
            console.log(locations)
            // document.getElementById('office').innerHTML = xhr.responseText;
        }
        console.log("-----------------------------------")
        // Add markers to the map
        setTimeout(function() {
            locations.forEach(function(location) {

                var icon;

                // Assign icon based on location type
                if (location.type === 'school') {
                    icon = schoolIcon;
                } else if (location.type === 'hospital' || location.type === 'police') {
                    icon = hospitalIcon; // Both hospital and police use red icon
                } else if (location.type === 'office') {
                    icon = officeIcon;
                } else {
                    icon = defaultIcon;
                }

                var marker = L.marker([location.lat, location.lng], {
                    icon: icon
                }).addTo(map);
                // .bindPopup(location.name);

                marker.bindTooltip(location.name, {
                    permanent: false,
                    direction: "bottom"
                }).openTooltip();

                marker.on('click', function() {
                    // if (confirm("Do you want directions to " + location.name + "?")) {
                    //     // Redirect to directions.html with the selected location's coordinates
                    //     // window.location.href = 'directions.html?lat=' + location.lat + '&lng=' + location.lng;
                    // }
                });
            });
        }, 1000);


        // console.log("Your coordinate is: Lat: " + lat + " Long: " + long + " Accuracy: " + accuracy)
    }
    // Initialize the map


    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        // attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Function to perform search using OpenStreetMap's Nominatim API
    function performSearch(query) {
        var url = `https://nominatim.openstreetmap.org/search?format=json&q=${query}`;

        // Fetch results from the Nominatim API
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    // Get the first result
                    var result = data[0];
                    var lat = result.lat;
                    var lon = result.lon;

                    /// Smoothly fly to the new location using Leaflet's flyTo
                    map.flyTo([lat, lon], 13, {
                        animate: true,
                        duration: 2 // Animation duration in seconds
                    });

                    // Add a marker at the searched location
                    var marker = L.marker([lat, lon]).addTo(map);

                    // Add a popup to the marker
                    marker.bindPopup(`<b>${result.display_name}</b>`).openPopup();

                    // Update the info card with the location details and direction info

                    document.getElementById('location-info').innerHTML = `
                            <div class="info-content">
                                <span class="info-symbol"><i class="fas fa-map-marker-alt"></i></span>
                                <p><strong>Address:</strong>${result.display_name}</p>
                            </div>
                            <div class="info-content">
                                <span class="direction-symbol"><i class="fas fa-compass"></i></span>
                                <p><strong>Coordinates:</strong> ${lat}, ${lon}</p>
                            </div>
                            <button class="button direction-button" onclick="window.location.href='direction.php'">
                                <i class="fas fa-arrow-right"></i> Get Directions
                            </button>
                            <button class="button save-button" onclick="saveLocation()">
                                <i class="fas fa-save"></i> Save Location
                            </button>
                            <button class="button nearby-button" onclick="findNearby()">
                                <i class="fas fa-map-signs"></i> Find Nearby
                            </button>
                            <button class="button save-phone-button" onclick="saveToPhone()">
                                <i class="fas fa-mobile-alt"></i> Save to Phone
                            </button>
                            <button class="button share-button" onclick="shareLocation()">
                                <i class="fas fa-share-alt"></i> Share Location
                            </button>
                        `;

                    // Display a static map image in the info card
                    var staticMapUrl =
                        `https://static-maps.yandex.ru/1.x/?ll=${lon},${lat}&size=300,200&z=13&l=map&lang=en_US`;
                    document.getElementById('location-image').src = staticMapUrl;
                    document.getElementById('location-image').style.display = "block";

                    // Show the info card
                    document.getElementById('info-card').style.display = "block";
                } else {
                    alert("Location not found.");
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Placeholder functions for buttons
    function getDirections() {
        alert("Getting directions...");
    }

    function saveLocation() {
        alert("Location saved.");
    }

    function findNearby() {
        alert("Finding nearby places...");
    }

    function saveToPhone() {
        alert("Location saved to phone.");
    }

    function shareLocation() {
        alert("Location shared.");
    }
    // Function to suggest locations as user types
    function suggestLocation() {
        var query = document.getElementById('search-input').value;
        if (query.length < 3) {
            document.getElementById('suggestions-list').style.display = 'none';
            return;
        }

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
            .then(response => response.json())
            .then(data => {
                var suggestionsList = document.getElementById('suggestions-list');
                suggestionsList.innerHTML = ''; // Clear previous suggestions

                if (data.length > 0) {
                    data.forEach(item => {
                        var suggestionItem = document.createElement('li');
                        suggestionItem.textContent = item.display_name;
                        suggestionItem.onclick = function() {
                            document.getElementById('search-input').value = item.display_name;
                            suggestionsList.style.display = 'none';
                            searchLocation(item.display_name); // Trigger the search on click
                        };
                        suggestionsList.appendChild(suggestionItem);
                    });
                    suggestionsList.style.display = 'block';
                } else {
                    suggestionsList.style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
    }
    // Set up the search button event listener
    document.getElementById('search-btn').addEventListener('click', function() {
        var query = document.getElementById('search-input').value;
        if (query.trim() !== '') {
            performSearch(query);
        } else {
            alert("Please enter a search term.");
        }
    });

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }


    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;

    }


    function getOffices() {
        // if (zone == "") return;
        // if (document.getElementById('department').value == "") return;
        let postObj = {
            // zone: zone,
            // department: document.getElementById('department').value,
            GET_LAT_LON_ALL: "true"
        }

        let post = JSON.stringify(postObj)
        const url = "https://maps.devsecit.com/ajax/"
        let xhr = new XMLHttpRequest()
        xhr.open('POST', url, true)
        xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
        xhr.send(post);
        xhr.onload = function() {
            console.warn(xhr.responseText);
            locations = xhr.responseText;

            // document.getElementById('office').innerHTML = xhr.responseText;
        }
    }

    // setTimeout(function() {
    //     getOffices();
    // }, 1000);
    </script>
</body>
<style>
* {

    padding: 0;
    margin: 0;

}

#map {

    height: 100vh;
    width: 100%
}

.leaflet-right,
.leaflet-routing-container {
    display: none
}
</style>

</html>