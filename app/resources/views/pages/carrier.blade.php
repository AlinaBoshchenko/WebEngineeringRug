<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Table</title>
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
            background-color: #460a20;
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
            background: #A7A1AE;
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
            background-color: #261941;
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
{{--       console.log("{{$data["carrier_code"]}}");--}}
        {{--showData.open('GET', 'http://localhost:8000/API/carrier/{{$data["carrier_code"]}}',true);--}}
        showData.open('GET', '{{URL::route('api_get_carriers', $data['carrier_code'])}}',true);

        showData.onload = function(){
           // console.log("hello");

            data = JSON.parse(this.response);
            console.log(data);

        }
        showData.send();


        //JSON Object End................
        //Create table and fetch data from JSON Object.
        $(document).ready(function(){
            $("button").click(function(){


                var table_body = '<table width="100%"><thead><tr><th>Name</th><th>Code</th></tr></thead><tbody>';


                    table_body+='<tr>';
                    table_body +='<td>';
                    table_body += data["carrier_name"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data["carrier_code"];
                    table_body +='</td>';


                    table_body+='</tr>';


                table_body+='</tbody></table>';
                $('#tableDiv').html(table_body);
                //display data..........
            });


        });
    </script>
</head>
<body background="../images/carrier.jpg" style="width: 1000px">
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <button>Show carrier</button>
    <div id="tableDiv" style="margin-top: 40px>
        Table will be generated here.
    </div>
</div>
<p id="p1"></p>
</body>
</html>