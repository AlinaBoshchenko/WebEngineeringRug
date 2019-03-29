<!DOCTYPE html>
<html>
<head>
    <title>Airport</title>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
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

            background-size: 100%;
            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 1.42em;
            color: #FFFFFF;

        }
        button{
            border-radius: 10px;
            height: 45px;
            width: 150px;
            text-align: center;
            background-color: #1f2646;
            font-size: 15px;
            color: #ffffff;
        }
        input{
            height: 35px;
            font-size: 15px;
        }
        table {
            border-collapse: collapse;;
            width: 200%;
            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 2em;
            color: #f7c6c5;
            background: #ae787b;
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
            background-color: #4b3132;
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
        showData.open('GET', '{{URL::route('api_get_airports', $data['airport_code'])}}',true);

        showData.onload = function(){
            data = JSON.parse(this.response);
            console.log(data);

        };
        showData.send();

        var showData2 = new XMLHttpRequest();
        var data2;
        showData2.open('GET', '{{URL::route('carriers_from_airport', $data['airport_code'])}}',true);


        showData2.onload = function(){
            data2 = JSON.parse(this.response);
        };
        showData2.send();

        //JSON Object End................
        //Create table and fetch data from JSON Object.
        window.addEventListener("load", function (){

            var table_body = '<table width="100%"><thead><tr><th>Name</th><th>Code</th></tr></thead><tbody>';


            table_body+='<tr>';
            table_body +='<td>';
            table_body += data["airport_name"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data["airport_code"];
            table_body +='</td>';

            table_body+='</tr>';
            table_body+='</tbody></table>';

            table_body += '<table width="100%"><thead><tr><th colspan="2">Carriers</th></tr></thead><tbody>';

            var k =0;
            for(k in data2){
                table_body+='<tr>';
                table_body +='<td>';
                table_body +=data2[k]["carrier_name"] + '</br>';
                table_body += '<a href="http://localhost:8000/carriers/' + data2[k]["carrier_code"] + '">view details</a>';
                table_body +='</td>';

                table_body +='<td>';
                table_body +=data2[k]["carrier_code"];
                table_body +='</td>';

                table_body+='</tr>';

            }
            table_body+='</tbody></table>';

            $('#tableDiv').html(table_body);
        });
    </script>
</head>
<body background="../images/air.jpg" style="width: 1000px">
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <div id="tableDiv" style="margin-top: 40px"></div>
</div>
<p id="p1"></p>
</body>
</html>