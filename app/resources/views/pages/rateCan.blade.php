<!DOCTYPE html>
<html>
<head>
    <title>Rate cancellations</title>
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
        body {

            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 1.42em;
            color: #FFFFFF;
            background-size: 100%;



        }
        button{
            border-radius: 10px;
            height: 45px;
            width: 150px;
            text-align: center;
            background-color: #535454;
            font-size: 15px;
            color: #ffffff;
        }
        input{
            height: 35px;
            font-size: 15px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 2em;
            color: #f7c6c5;
            background: #4c4243;
            padding: 20px;


        }
        th, td {
            text-align: center;
            padding: 15px;
            font-size: 20px;
            color: #ffffff;
            border: 5px groove #ccc /* Граница между ячейками */
        }
        th {
            background-color: #430207;
            color: white;
            font-style: bold;
            font-size: 35px;
        }
        a {
            color: #FFE8E6;
        } /* link color */


    </style>
    <script >
        //JSON Object................

        var showData = new XMLHttpRequest();
        var data;

        console.log("hiii");
        showData.open('GET',"{!!  URL::route('api_get_number_of_delays_ranking', ['rating_type'=>"ratio_of_cancellations", 'year'=>$data['year']]) !!} ");

        showData.onload = function(){
            console.log("hello");

            data = [];
            if (this.status == 404) {
                $('#p1').html("Invalid input in form.");
            } else if (this.status != 200) {
                $('#p1').html(this.response.toString());
            } else {
                data = JSON.parse(this.response);

                if (data.length == 0) {
                    $('#p1').html("No statistics found");
                }
            }
        };
        showData.send();

        //JSON Object End................
        //Create table and fetch data from JSON Object.
        window.addEventListener("load", function (){
                var k = 0;
                var table_body = '<table width="100%"><thead><tr><th>Rank</th><th>Carrier</th><th>Ratio of cancellationss</th></tr></thead><tbody>';
                for(k in data){

                    table_body+='<tr>';
                    table_body +='<td>';
                    table_body +=data[k]["rank"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["carrier_code"] + '</br>';
                    table_body += '<a href="http://localhost:8000/carriers/' + data[k]["carrier_code"] + '">view details</a>';
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["ratio_of_cancellations"];
                    table_body +='</td>';


                    table_body+='</tr>';

                }
                table_body+='</tbody></table>';
                $('#tableDiv').html(table_body);
                //display data..........


// for search function.................................. only............................
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tr").filter(function(index) {
                    if(index>0){
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    }
                });
            });

        });
    </script>
</head>

<body background="/images/lg.png">
<div class="isa_info" id = "p1"></div>
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <div id="tableDiv" style="margin-top: 40px">
    </div>
</div>
<p id="p1"></p>

</body>
</html>