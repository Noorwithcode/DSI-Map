
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Direction</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet Routing Machine for OSRM integration -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <style>
    /* Container for the direction form */
    .direction-form-container {
        background: whitesmoke;
        max-width: 100%;
        width: 400px;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        position: absolute;
        z-index: 999;
        height: auto;
        margin-left: 0px;
    }

    /* Transport modes section */
    .transport-modes {
        display: flex;
        justify-content: space-around;
        padding: 5px 30px;
        width: 360px;
    }

    /* Mode buttons styling */
    .mode-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        font-size: 20px;
    }

    .mode-btn i {
        color: #555;
    }

    .mode-btn.active i {
        color: #0078d7;
        /* Active color for selected transport mode */
    }

    /* Input fields section */
    .input-fields {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Row for each input and reverse button */
    .input-row {
        display: flex;
        align-items: center;
        width: 100%;
        gap: 10px;
    }

    /* Container for each input field */
    .input-container {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .input-container i {
        position: absolute;
        left: 10px;
        color: #888;
        font-size: 18px;
    }

    /* Styling for input fields */
    .input-container input {
        width: 270px;
        padding: 10px 40px;
        /* Extra padding to account for the icon */
        border-radius: 100px;
        border: 1px solid #ccc;
        font-size: 14px;

    }

    /* Reverse button styling */
    .reverse-btn {
        top: 24px;
        position: absolute;
        right: 0;
        background-color: #0078d7;
        border: none;
        color: white;
        padding: 10px;
        cursor: pointer;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .reverse-btn i {
        font-size: 18px;
    }

    /* Input hover and focus effects */
    .input-container input:hover,
    .input-container input:focus {
        border-color: #0078d7;
        outline: none;
    }

    /* Placeholder styling */
    input[type="text"]::placeholder {
        font-size: 14px;
        color: #888;
    }

    /* Responsive behavior */
    @media (max-width: 480px) {
        .direction-form-container {
            width: 100%;
            padding: 10px;

        }

        .reverse-btn {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
    }

    /* Styling for main map container */
    #map {
        height: 100vh;
        width: 100%;
    }


    .leaflet-control-container {
        position: absolute;
        height: 100px;
        width: 50px;
        right: 10px;
        bottom: 10px;
        z-index: 9999 !important
    }

    /* Get directions button styling */
    .direction-btn {
        background-color: #0078d7;
        border: none;
        color: white;
        padding: 10px;
        cursor: pointer;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        left: 10px;
        position: absolute;
        margin: 10px;
        width: 150px;
        display: flex;
        align-content: center;
        border-radius: 100px;
    }

    .direction-btn i {
        font-size: 18px;
    }

    .direction-btn:hover {
        background-color: #005bb5;
    }

    /* Back to Main Page button styling */
    .back-button {
        position: absolute;
        top: 8px;
        left: 10px;
        right: 10px;
        transform: translateX(-50%);
        padding: 18px 20px;
        background-color: #f4f4f4;
        border: 1px solid ;
        border-radius: 0 10px 10px 0;
        cursor: pointer;
        z-index: 999;
        font-family: Arial, sans-serif;
        font-size: 14px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        width: 20px;
    }

    .back-button:hover {
        background-color: #e0e0e0;
    }

    .back-button svg {
        position: absolute;
        margin-right: 10px;
    }

    /* Suggestion box styling */
    .suggestions {
        top: 40px;
        border: 1px solid #ccc;
        background: #fff;
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        width: 340px;
        z-index: 1000;
        border-radius: 10px 10px 10px 10px;
        left: 0px;
    }

    .suggestions div {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #ddd;
    }

    .suggestions div:hover {
        background-color: #f1f1f1;
    }

    /* Responsive design using media queries */
    @media (max-width: 600px) {
      body {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        z-index: 9999;
        padding: 15px;
        box-shadow: none;
        order: 1;
      }

      .input-group input {
        font-size: 18px;
        padding: 15px;
      }

      .btn {
        width: 100%;
        margin-bottom: 12px;
      }

      .reverse-btn {
        position: static;
        margin-bottom: 10px;
        width: 100%;
        font-size: 60px;
      }

    
    }

    @media (max-width: 455px) {
      .input-group input {
        font-size: 16px;
        padding: 12px;
      }

      .reverse-btn {
        font-size: 160px;
        padding: 10px;
      }

      .btn {
        
        padding: 10px 12px;
        font-size: 5px;
      }
      .back-button{
        color: black;
        margin-left: 0px;
        border: 1px solid;

      }      
    }

    
    </style>
</head>

<body>
    <div class="direction-form-container">
        <div class="transport-modes">
            <button class="mode-btn" title="Car"><i class="fas fa-car"></i></button>
            <button class="mode-btn" title="Motorcycle"><i class="fas fa-motorcycle"></i></button>
            <button class="mode-btn" title="Bus"><i class="fas fa-bus"></i></button>
            <button class="mode-btn" title="Walking"><i class="fas fa-walking"></i></button>
            <button class="mode-btn" title="Bicycle"><i class="fas fa-bicycle"></i></button>
            <!-- Back button at the top of the page -->
            <div class="back-button" id="backToMain">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
            </div>
        </div>
        <div class="input-fields">
            <!-- Input field for "From" (starting point) with a label -->
            <div class="input-row">
                <label for="start"></label>
                <div class="input-container">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" id="start" placeholder="Enter starting point..."
                        oninput="showSuggestions(this,'start')">
                    <div id="startSuggestions" class="suggestions"></div>
                </div>
                <button class="reverse-btn" onclick="reverseInputs()" title="Reverse directions">
                    <i class="fas fa-arrows-alt-v"></i>
                </button>
            </div>

            <!-- Input field for "To" (destination) with a label -->
            <div class="input-row">
                <label for="destination"></label>
                <div class="input-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="destination" placeholder="Enter destination..."
                        oninput="showSuggestions(this,'destination')">
                    <div id="destinationSuggestions" class="suggestions"></div>
                </div>
            </div>
        </div>

        <!-- Get Directions button -->
        <div class="get-directions">
            <button class="direction-btn" onclick="calculateRoute()" title="Get Directions">
                <i class="fas fa-directions"></i> Get Directions
            </button>
        </div>
    </div>

    <div id="map"></div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

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
        const url = "/ajax/"
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


    // Initialize the routing control but don't add it to the map yet
    let routingControl;
    let startMarker, endMarker;

    //reverse button 
    function reverseInputs() {
        const startInput = document.getElementById('start');
        const destinationInput = document.getElementById('destination');
        const temp = startInput.value;
        startInput.value = destinationInput.value;
        destinationInput.value = temp;
    }
    // Function to redirect to the main page
    function backToMainPage() {
        window.location.href = 'index.php'; // Replace with the URL of your main page
    }

    // Event listener for the back button
    document.getElementById('backToMain').addEventListener('click', backToMainPage);
    async function calculateRoute() {
        const startLocation = document.getElementById("start").value;
        const destinationLocation = document.getElementById("destination").value;

        if (!startLocation || !destinationLocation) {
            alert("Please enter both start and destination locations.");
            return;
        }

        const startLatLng = await geocodeLocation(startLocation);
        const endLatLng = await geocodeLocation(destinationLocation);

        if (!startLatLng || !endLatLng) {
            alert("Unable to find one or both locations. Please try again.");
            return;
        }

        if (routingControl) {
            map.removeControl(routingControl);
        }

        routingControl = L.Routing.control({
            waypoints: [startLatLng, endLatLng],
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1'
            }),
            lineOptions: {
                styles: [{
                    color: 'blue',
                    weight: 4
                }]
            },
            createMarker: function() {
                return null;
            } // Disable default markers
        }).addTo(map);

        // Remove existing markers if any
        if (startMarker) map.removeLayer(startMarker);
        if (endMarker) map.removeLayer(endMarker);

        // Add default location icon for starting marker
        startMarker = L.marker(startLatLng, {
                icon: createDefaultPinIcon('green')
            }).addTo(map)
            .bindPopup(` ${startLocation}`)
            .openPopup();

        // Add default location icon for ending marker
        endMarker = L.marker(endLatLng, {
                icon: createDefaultPinIcon('red')
            }).addTo(map)
            .bindPopup(`<b>Destination:</b> ${destinationLocation}`);

        map.flyTo(startLatLng, 14, {
            animate: true,
            duration: 1.5
        });
    }

    // Function to create a default location pin icon
    function createDefaultPinIcon(color) {
        const iconMarkup = `
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
          <path fill="${color}" d="M12 0C8.13 0 5 3.13 5 7c0 4.59 7 13 7 13s7-8.41 7-13c0-3.87-3.13-7-7-7zm0 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
        </svg>`;
        const icon = L.divIcon({
            className: 'custom-pin-icon',
            html: iconMarkup,
            iconSize: [50, 50],
            iconAnchor: [25, 50],
            popupAnchor: [0, -30]
        });
        return icon;

    }
    async function geocodeLocation(location) {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(location)}&format=json&addressdetails=1`
        );
        const data = await response.json();

        if (data.length > 0) {
            const {
                lat,
                lon
            } = data[0];
            return L.latLng(lat, lon);
        }

        return null;
    }
    async function showSuggestions(inputElement, type) {
        const query = inputElement.value;
        const suggestionsContainer = document.getElementById(`${type}Suggestions`);

        if (query.length < 3) {
            suggestionsContainer.innerHTML = ''; // Clear suggestions if query is too short
            return;
        }

        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`;
        const response = await fetch(url);
        const data = await response.json();

        suggestionsContainer.innerHTML = ''; // Clear previous suggestions

        data.forEach(place => {
            const suggestionDiv = document.createElement('div');
            suggestionDiv.innerText = place.display_name;
            suggestionDiv.onclick = () => {
                inputElement.value = place.display_name;
                suggestionsContainer.innerHTML = ''; // Clear suggestions on selection
            };
            suggestionsContainer.appendChild(suggestionDiv);
        });
    }


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
        const url = "/ajax/"
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