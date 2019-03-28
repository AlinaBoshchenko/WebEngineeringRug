<!DOCTYPE html>
<html>
<head>
    <title>Flight statistic</title>
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
            background: #8c8f88;
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
            background-color: #7d86cf;
            color: white;
            font-style: bold;
            font-size: 35px;
        }
        a {
            color: #FFE8E6;
        } /* link color */

        .button {
            display: inline-block;
            padding: 15px 25px;
            font-size: 24px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #617d88;
            border: none;
            border-radius: 15px;
            box-shadow: 0 9px #999;
        }

        .button:hover {background-color: #6c8e84
        }

        .button:active {
            background-color: #406575;
            box-shadow: 0 5px #666;
            transform: translateY(4px);
        }


    </style>
    <script >
        //JSON Object................

        var showData = new XMLHttpRequest();
        var data;

        showData.open('GET',"{!!  URL::route('api_get_flight_statistics', ['carrier_code'=>$data['carrier_code'],
                             'route' =>$data['route'], 'month' =>$data['month'], 'year' =>$data['year'],
                             'airport_code' => $data['airport_code']]) !!} ");

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

                var table_body = '<table width="100%"><thead><tr><th>Route</th><th>Date</th><th>Cancelled</th><th>On time</th><th>Delayed</th><th>Diverted</th><th>Total</th></tr></thead><tbody>';


                table_body+='<tr>';

                table_body +='<td>';
                table_body +=data[0]["route"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[0]["month"] + ' / ' + data[0]["year"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[0]["statistics"]["cancelled"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[0]["statistics"]["on_time"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[0]["statistics"]["delayed"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[0]["statistics"]["diverted"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[0]["statistics"]["total"];
                table_body +='</td>';
                table_body+='</tr>';


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

<body background="/images/airstat.jpg">
<div class="isa_info" id = "p1"></div>

<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">

    <div id="tableDiv" style="margin-top: 40px">
    </div>
</div>

</body>
</html>