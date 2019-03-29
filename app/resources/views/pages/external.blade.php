<!DOCTYPE html>
<html>
<head>
    <title>External</title>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .isa_info, .isa_success, .isa_warning, .isa_error {
            margin: 10px 0px;
            padding:12px;

        }
        .isa_info {
            color: #00529B;
            background-color: #BDE5F8;
        }
        h2 {
            font-size: 1em;
            font-weight: 100%;
            text-align: center;
            display: block;
            line-height: 1em;
            padding-bottom: 2em;
            background-color: #fff;
            color: #ffe9e6;
        }
        h2 {
            display: block;
            font-size: 1.5em;
            font-weight: bold;
        }
    </style>
    <script type="text/javascript">
        navigator.geolocation.getCurrentPosition(doStuff, error, setOptions);

        function setOptions(geoLoc) {
            geoLoc.enableHighAccuracy = true;
            geoLoc.timeout = 30;
            geoLoc.maximumAge = 0;
        }
        function init() {
            navigator.geolocation.getCurrentPosition(doStuff, error, setOptions);
        }

        var wpid = navigator.geolocation.watchPosition(geo_success, geo_error, geo_options);
        function doStuff(geoLoc) {
            document.getElementById("refreshTimestamp").innerHTML = "Last refresh: " + Date.now();
            document.getElementById("latitude").innerHTML = "Latitude: " + geoLoc.coords.latitude;
            document.getElementById("longitude").innerHTML = "Longitude: " + geoLoc.coords.longitude;
            document.getElementById("altitude").innerHTML = "Altitude: " + geoLoc.coords.altitude;
            document.getElementById("accuracy").innerHTML = "Accuracy: " + geoLoc.coords.accuracy;
            document.getElementById("altitudeAccuracy").innerHTML = "Altitude Accuracy: " + geoLoc.coords.altitudeAccuracy;
            document.getElementById("heading").innerHTML = "Heading: " + geoLoc.coords.heading;
            document.getElementById("speed").innerHTML = "Speed: " + geoLoc.coords.speed;
        }

        function error(geoLoc) {
            document.getElementById("error").innerHTML = "ERROR! Code: " + geoLoc.code + "; Message: " + geoLoc.message;
        }
    </script>
</head>
<body onload="init()">
<h1>Current geoposition's properties:</h1>
<p id="refreshTimestamp"></p>
<p id="latitude"></p>
<p id="longitude"></p>
<p id="altitude"></p>
<p id="accuracy"></p>
<p id="altitudeAccuracy"></p>
<p id="heading"></p>
<p id="speed"></p>
<p id="error"></p>
</body>
</html>