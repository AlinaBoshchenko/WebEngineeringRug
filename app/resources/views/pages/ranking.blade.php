<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html {
            box-sizing: border-box;
        }
        body{
            background-size: 130%;
        }
        h2{
            background-color: #FFE8E6;
        }
        a:link {
            color: green;
            background-color: transparent;
            text-decoration: none;
            text-align: center;
        }

        *, *:before, *:after {
            box-sizing: inherit;
        }

        .column {
            float: left;
            width: 33.3%;
            margin-bottom: 20px;
            padding: 0 8px;
        }

        @media screen and (max-width: 650px) {
            .column {
                width: 100%;
                display: block;
            }
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);

            display: block;
            margin-left: 50%;
            margin-right: -40%;
        }

        .container {
            padding: 0 16px;
        }

        .container::after, .row::after {
            content: "";
            clear: both;
            display: table;

            margin-left: auto;
            margin-right: auto;
        }


        img{
            text-align: center;
            display: block;
            margin-left: auto;
            margin-right: 20%;
        }

        .button:hover {
            background-color: #555;
        }
    </style>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body background="../images/lg.png">

<div><center>
    <h1 class="w3-opacity">
        <b>Find your own rating based on:</b></h1>
    </center></div>
<br>

<div class="error_printing" id= "error_printing"></div>

<div class="row">
    <div class="column">
        <div class="card">
            <img src="../images/rate7.jpg" alt="Number of delays" style="width:100%" >
            <div class="container">
                <h2><b>Number of delays</b></h2>
                <h2><form method="GET" action="http://localhost:8000/carriers/rankings/number_of_delays">
                        <input type="text" name="year" placeholder="Year" />
                        <input type="submit" value="Find" />
                    </form></h2>
            </div>
        </div>
    </div>

    <div class="column">
        <div class="card">
            <img src="../images/rate2.jpg" alt="Ratio Of Cancellation" style="width:100%">
            <div class="container">
                <h2><b>Ratio of cancelled flights</b></h2>
                <h2><form method="GET" action="http://localhost:8000/carriers/rankings/ratio_of_cancellations">
                    <input type="text" name="year" placeholder="Year" />
                    <input type="submit" value="Find" />
                </form></h2>
            </div>
        </div>
    </div>
    <div>

    </div>
</div>

</body>
</html>