# USA airports database
# Backend  
The final website provides access to all airports and US airlines, flight delay statistics, user reviews, and company ratings. 
Both back-end and front-end were developed from scratch.  
Based on the REST principles, we implemented the web API endpoints for the airport's database according to the requirements document (included in the project’s Github repository). Every API endpoint we implemented has the ’API/’ prefix in the URL.
The API provides a set of endpoints, each with its own unique path and returns JSON objects as the default response.  
We decided that for this project, we would utilize an SQL database, namely MariaDB. The reason we chose this over a no-SQL
database or a caching engine like due to the  (relatively) we have to deal with (in the thousands).   

# Frontend
For the front-end component the following tools were used:  
* HTML/CSS  
* Bootstrap  
* JavaScript  
* jQuery  
  
We designed a number of pages the result in a user-friendly  way. The main one is our intro page, 
from which you can access all the relevant pages. Pages are made for the following categories of data:  
* airports  
* carriers  
* statistical data  
* reviews (additional)  
* external API (additional)  
* ranking (additional)  
divided into two main types: forms requiring user’s input and tables presenting data.
# External API  
In addition, we made a decision to implement an external API and for this aim the Geo-location API in Google Chrome (v13) 
was chosen. We took data which provides the current location’s properties such as Latitude, Longitude, Altitude, Accuracy, Altitude Accuracy, Heading and Speed. The user is asked to allow Google to define his location and we deliver all 
the necessary information regarding his position. This API is open-source and free.  
# Technologies  
PHP Laravel, CSS, HTML, Bootstrap, Javascript, MySQL, Apache, XAMPP.
