<!DOCTYPE html>
<html>
<head>
    <title>External</title>
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

            background-size: 100%;
            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 1.42em;
            color: #ff111d;

        }
        button{
            border-radius: 10px;
            height: 45px;
            width: 150px;
            text-align: center;
            background-color: #460113;
            font-size: 15px;
            color: #ffffff;
        }
        input{
            height: 35px;
            font-size: 15px;
        }
        table {
            border-collapse: collapse;;
            width: 100%;
            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 2em;
            color: #f74077;
            background: #f7dfd3;
            padding: 20px;


        }
        th, td {
            text-align: center;
            padding: 15px;
            font-size: 20px;
            color: #5b1408;
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
        showData.open('GET', 'https://avwx.rest/api/metar/{{$data["location"]}}',true);

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

            var table_body = '<table width="100%"><thead><tr><th>Wind-Speed</th><th>Wind-Direction</th></tr></thead><tbody>';

            table_body+='<tr>';
            table_body +='<td>';
            table_body += data["Wind-Speed"];
            table_body +='</td>';

            table_body +='<td>';
            table_body += data["Wind-Direction"];
            table_body +='</td>';
            table_body+='</tr>';

            table_body += '<table width="100%"><thead><tr><th>Visibility</th><th>Dewpoint</th><th>Temperature</th></tr></thead><tbody>';

            table_body+='<tr>';
            table_body +='<td>';
            table_body +=data["Visibility"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data["Dewpoint"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data["Units"]["Temperature"];
            table_body +='</td>';

            table_body+='</tr>';

            table_body+='</tbody></table>';
            $('#tableDiv').html(table_body);
            //display data..........
        });
    </script>
</head>
<body background="../images/port.png">
<div class="isa_info" id = "p1"></div>
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <div id="tableDiv" style="margin-top: 40px"></div>

</div>
<p id="p1"></p>
</body>
</html>