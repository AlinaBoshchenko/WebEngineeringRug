<!DOCTYPE html>
<html>
<head>
    <title>Post review</title>
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
            background: #1f1b45;
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
            box-shadow: 0 0 5px #2d3b57;
            padding: 3%;
            border: 1px solid #432dd1;

        }

        .form-style-6 input[type="submit"],
        .form-style-6 input[type="button"]{
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            padding: 3%;
            background: #2d265a;
            border-bottom: 2px solid #16176c;
            border-top-style: none;
            border-right-style: none;
            border-left-style: none;
            color: #fff;

        }
        .form-style-6 input[type="submit"]:hover,
        .form-style-6 input[type="button"]:hover{
            background: #3246bc;

        }
        body{
            background-size: 100%;
            margin-top: 15%;
        }
    </style>
    <script>
        window.addEventListener("load", function () {
            function sendData() {
                var XHR = new XMLHttpRequest();

                // Bind the FormData object and the form element
                var form = document.getElementById("myForm");
                var FD = new FormData(form);

                // Define what happens on successful data submission
                XHR.addEventListener("load", function(event) {
                    alert(event.target.responseText);
                });

                // Define what happens in case of error
                XHR.addEventListener("error", function(event) {
                    alert('Oops! Something went wrong.');
                });

                // Set up our request
                XHR.open('POST', '{!! URL::route('api_post_review') !!}',true);

                // The data sent is what the user provided in the form
                XHR.send(FD);
            }

            // Access the form element...


            // ...and take over its submit event.
            form.addEventListener("submit", function (event) {
                event.preventDefault();

                sendData();
            });
        });



    </script>
</head>

<body  background="../images/more1.jpg">
<div></div>
<div class="form-style-6">
    <h1>Post Review</h1>
    <form id="myForm">
        <input type="text" name="user_name" placeholder="User" />
        <input type="text" name="review" placeholder="Review" />
        <input type="text" name="carrier_code_rank_1" placeholder="Carrier #1" />
        <input type="text" name="carrier_code_rank_2" placeholder="Carrier #2" />
        <input type="text" name="carrier_code_rank_3" placeholder="Carrier #3" />
        <input type="submit" value="Send!">
    </form>


</div>


</body>
</html>