<html>
<title>Front Page</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    html,body,h1,h2,h3,h4 {font-family:"Lato", sans-serif}
    .mySlides {display:none}
    .w3-tag, .fa {cursor:pointer}
    .w3-tag {height:15px;width:15px;padding:0;margin-top:6px}
    h1 {
        font-size: 5em;
        font-weight: 300;
        text-align: center;
        display: block;
        line-height: 1em;
        padding-bottom: 2em;
        color: #1B1C1D;
    }
</style>
<body>

<!-- Links (sit on top) -->
<div class="w3-top">
    <div class="w3-row w3-large w3-light-grey">
        <div class="w3-col s3">
            <a href = "http://localhost:8000/airports" class="w3-button w3-block">Find airports</a>
        </div>
        <div class="w3-col s3">
            <a href = "http://localhost:8000/carriers" class="w3-button w3-block">Find carriers</a>
        </div>
        <div class="w3-col s3">
            <a href = "http://localhost:8000/ranking" class="w3-button w3-block">Carriers rank</a>
        </div>
        <div class="w3-col s3">
            <a href = "http://localhost:8000/additional" class="w3-button w3-block">Additional</a>
        </div>
    </div>
</div>
<div class="w3-bottom">
    <div class="w3-row w3-large w3-light-grey">
        <div class="w3-col s4">
            <a href = "http://localhost:8000/statistics/delete" class="w3-button w3-block">Delete statistics</a>
        </div>
        <div class="w3-col s4">
            <a href = "http://localhost:8000/carriers/{carrier_code}/statistics/flights/update" class="w3-button
            w3-block">Update statistics</a>
        </div>
        <div class="w3-col s4">
            <a href = "http://localhost:8000/carriers/{carrier_code}/statistics/flights/post" class="w3-button
            w3-block">Post statistics</a>
        </div>
    </div>
</div>


<!-- Content -->
<div class="w3-content" style="max-width:1100px;margin-top:80px;margin-bottom:80px">

    <div class="w3-panel">
        <h1><b>Beautiful US airports database</b></h1>
    </div>

    <!-- Slideshow -->

    <div class="w3-content w3-section">
        <img class="mySlides w3-animate-fading" src="images/intro1.jpg" style="width:100%">
        <img class="mySlides w3-animate-fading" src="/images/intro2.jpg" style="width:100%">
        <img class="mySlides w3-animate-fading" src="/images/intro3.jpg" style="width:100%">

    </div>

    <script>
        var myIndex = 0;
        carousel();

        function carousel() {
            var i;
            var x = document.getElementsByClassName("mySlides");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            myIndex++;
            if (myIndex > x.length) {myIndex = 1}
            x[myIndex-1].style.display = "block";
            setTimeout(carousel, 10000); // Change image every 2 seconds
        }
    </script>


        <!-- Slideshow next/previous buttons -->
        <div class="w3-container w3-dark-grey w3-padding w3-xlarge">
            <div class="w3-left" onclick="plusDivs(-1)"><i class="fa fa-arrow-circle-left w3-hover-text-teal"></i></div>
            <div class="w3-right" onclick="plusDivs(1)"><i class="fa fa-arrow-circle-right w3-hover-text-teal"></i></div>

            <div class="w3-center">
                <span class="w3-tag demodots w3-border w3-transparent w3-hover-white" onclick="currentDiv(1)"></span>
                <span class="w3-tag demodots w3-border w3-transparent w3-hover-white" onclick="currentDiv(2)"></span>
                <span class="w3-tag demodots w3-border w3-transparent w3-hover-white" onclick="currentDiv(3)"></span>
            </div>
        </div>
    </div>

    <!-- Grid -->
    <div class="w3-row w3-container">
        <div class="w3-center w3-padding-64">
            <span class="w3-xlarge w3-bottombar w3-border-dark-grey w3-padding-16">Avaliable statistics</span>
        </div>
        <div class="w3-col l3 m6 w3-light-grey w3-container w3-padding-16">
            <h3><a href = "http://localhost:8000/statistics/flights">Flights statistics</a></h3>
            <p>Find out statistics of flights to or from your airport for specific carrier</p>
        </div>

        <div class="w3-col l3 m6 w3-grey w3-container w3-padding-16">
            <h3><a href = "http://localhost:8000/statistics/carrier">Carriers statistics</a></h3>
            <p>Planning a trip? Have a look at the statistics of carriers. Includes mean, median and standart deviation</p>
        </div>

        <div class="w3-col l3 m6 w3-dark-grey w3-container w3-padding-16">
            <h3><a href = "http://localhost:8000/statistics">Airports statistics</a></h3>
            <p>Get statistics about flights between two airports.Includes mean, median and standart deviation</p>
        </div>
        <div class="w3-col l3 m6 w3-black w3-container w3-padding-16">
            <h3><a href = "http://localhost:8000/statistics/minutes_delayed">Statistics in minutes</a></h3>
            <p>Get statistics of delays in minutes about flights for particular airport for different reasons</p>
        </div>

      </div>

<!--Additional -->
<div class="w3-row-padding" id="about">
    <div class="w3-center w3-padding-64">
        <span class="w3-xlarge w3-bottombar w3-border-dark-grey w3-padding-16">Want more?</span>
    </div>

    <div class="w3-third w3-margin-bottom">
        <div class="w3-card-4">
            <img src="../images/more1.jpg" alt="Post your own review" style="width:100%">
            <div class="w3-container">
                <h3>Post your review of the carriers </h3>
                <p class="w3-opacity">Just fill the form to express your feedback</p>
                <p><a href="http://localhost:8000/reviews/post" class="w3-button w3-block">Try</a></p>
            </div>
        </div>
    </div>

    <div class="w3-third w3-margin-bottom">
        <div class="w3-card-4">
            <img src="../images/more2.jpg" alt="Reviews" style="width:100%">
            <div class="w3-container">
                <h3>Find review</h3>
                <p class="w3-opacity">Find reviews by user's name</p>
                <p><a href="http://localhost:8000/reviews/byuser" class="w3-button w3-block">Try</a></p>
            </div>
        </div>
    </div>

    <div class="w3-third w3-margin-bottom">
        <div class="w3-card-4">
            <img src="../images/more3.jpg" alt="All" style="width:100%">
            <div class="w3-container">
                <h3>Find review by id</h3>
                <p class="w3-opacity">Find particular review of particular user</p>
                <p><a href="http://localhost:8000/reviews/byuser/byid" class="w3-button w3-block">Try</a></p>
            </div>
        </div>
    </div>
</div>


<!-- Footer -->

<footer class="w3-container w3-padding-32 w3-light-grey w3-center">
   
    <a href="#" class="w3-button w3-black w3-margin"><i class="fa fa-arrow-up w3-margin-right"></i>To the top</a>
    <div class="w3-xlarge w3-section">
        <i class="fa fa-facebook-official w3-hover-opacity"></i>
        <i class="fa fa-linkedin w3-hover-opacity"></i>
    </div>

</footer>


<script>
    // Slideshow
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function currentDiv(n) {
        showDivs(slideIndex = n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demodots");
        if (n > x.length) {slideIndex = 1}
        if (n < 1) {slideIndex = x.length} ;
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" w3-white", "");
        }
        x[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " w3-white";
    }
</script>

</body>
</html>