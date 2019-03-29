<!DOCTYPE html>
<html>
<head>
    <title>Minutes Delayed Statistics</title>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .isa_info, .isa_success, .isa_warning, .isa_error {
            margin: 10px 0px;
            padding:12px;

        }
        .isa_info {
            color: #805c31;
            background-color: #d6af6e;
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
            background: #7b786f;
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
            background-color: #906619;
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

        showData.open('GET',"{!!  URL::route('api_get_minute_delay', ['airport_code'=>$data['airport_code'],'month' =>$data['month'], 'year' => $data['year'], 'reasons' => $data['reasons']]) !!} ");
        showData.onload = function(){
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
            var table_body = '<table width="100%"><thead><tr><th>Carrier code</th><th>Airport</th><th>Date</th><th>Late aircraft</th><th>Carrier</th><th>Total</th></tr></thead><tbody>';
            for(k in data){
                table_body+='<tr>';
                table_body +='<td>';
                table_body +=data[k]["carrier"]["carrier_code"] + '</br>';
                table_body += '<a href="http://localhost:8000/carriers/' + data[k]["carrier"]["carrier_code"] + '">view details</a>';
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[k]["airport_code"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[k]["month"] + ' / ' + data[k]["year"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[k]["reasons"]["late_aircraft"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[k]["reasons"]["carrier"];
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data[k]["reasons"]["total"];
                table_body +='</td>';

                table_body+='</tr>';

            }
            table_body+='</tbody></table>';
            $('#tableDiv').html(table_body);
        });

    </script>
</head>

<body background="/images/map2.jpg">
<div class="isa_info" id = "p1"></div>
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <div id="tableDiv" style="margin-top: 40px">
    </div>
</div>
<p id="p1"></p>

</body>
</html>