<!DOCTYPE html>
<html>
<head>
    <title>Post statistics</title>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style type="text/css">
        .form-style-6{
            font: 95% Arial, Helvetica, sans-serif;
            max-width: 400px;
            margin: 10px auto;
            padding: 16px;
            background: #F7F7F7;

        }
        .form-style-6 h1{
            background: #43D1AF;
            padding: 20px 0;
            font-size: 140%;
            font-weight: 300;
            text-align: center;
            color: #fff;
            margin: -16px -16px 16px -16px;


        }
        .form-style-6 input[type="text"],
        .form-style-6 input[type="date"],
        .form-style-6 input[type="datetime"],
        .form-style-6 input[type="email"],
        .form-style-6 input[type="number"],
        .form-style-6 input[type="search"],
        .form-style-6 input[type="time"],
        .form-style-6 input[type="url"],
        .form-style-6 textarea,
        .form-style-6 select
        {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            background: #fff;
            margin-bottom: 4%;
            border: 1px solid #ccc;
            padding: 3%;
            color: #555;
            font: 95% Arial, Helvetica, sans-serif;

        }
        .form-style-6 input[type="text"]:focus,
        .form-style-6 input[type="date"]:focus,
        .form-style-6 input[type="datetime"]:focus,
        .form-style-6 input[type="email"]:focus,
        .form-style-6 input[type="number"]:focus,
        .form-style-6 input[type="search"]:focus,
        .form-style-6 input[type="time"]:focus,
        .form-style-6 input[type="url"]:focus,
        .form-style-6 textarea:focus,
        .form-style-6 select:focus
        {
            box-shadow: 0 0 5px #43D1AF;
            padding: 3%;
            border: 1px solid #43D1AF;

        }

        .form-style-6 input[type="submit"],
        .form-style-6 input[type="button"]{
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            padding: 3%;
            background: #43D1AF;
            border-bottom: 2px solid #30C29E;
            border-top-style: none;
            border-right-style: none;
            border-left-style: none;
            color: #fff;

        }
        .form-style-6 input[type="submit"]:hover,
        .form-style-6 input[type="button"]:hover{
            background: #2EBC99;

        }
        body{
            background-size: 100%;
            margin-top: 15%;
        }
    </style>
    <script>

        $(document).ready(function() {
            $('#MyButton').click(function (form) {
                var XHR = new XMLHttpRequest();
                var form = document.getElementById("myForm");

                // Bind the FormData object and the form element
                var FD = new FormData(form);

                // Define what happens on successful data submission
                XHR.addEventListener("load", function (event) {
                    alert(event.target.responseText);
                });

                // Define what happens in case of error
                XHR.addEventListener("error", function (event) {
                    alert('Oops! Something went wrong.');
                });

                var carrier_code = document.getElementById("carrier_code").value;
                var month = document.getElementById("month").value;
                var year = document.getElementById("year").value;
                var airport_code = document.getElementById("airport_code").value;

                // Set up our request
                XHR.open('POST',"http://localhost:8000/API/carriers/" + carrier_code + "/statistics/flights" + "?month=" + month + "&year=" + year + "&airport_code=" + airport_code);

                // The data sent is what the user provided in the form
                XHR.send(FD);
            });
        });
    </script>
</head>

<body  background="../images/map.png">
<div></div>
<div class="form-style-6">
    <h1>Add new flight statistics</h1>
    <form id="myForm">
        <input type="text" id="carrier_code" placeholder="carrier_code" />
        <input type="text" id="month" placeholder="Month" />
        <input type="text" id="year" placeholder="Year" />
        <input type="text" id="airport_code" placeholder="Airport code" />

        <input type="text" name="cancelled" id="cancelled" placeholder="Cancelled" />
        <input type="text" name="on_time" placeholder="On time" />
        <input type="text" name="delayed" placeholder="Delayed" />
        <input type="text" name="diverted" placeholder="Diverted" />
        <input type="text" name="total" placeholder="Total" />

        <input type="button" value="Post" id="MyButton" >
    </form>
</div>


</body>
</html>