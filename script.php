<?php
$start = microtime(true);

session_start();

if(!isset($_SESSION["requestsCount"])){
    $_SESSION["requestsCount"] = 0;
}
?>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <title>Result</title>
    <style>
        body {
            background: #e5f8ff;
            font-family: 'Montserrat';
            color: white;
        }

        .block {
            background: #E0FFFF;
            box-shadow: 0 0 5px #999999;

            margin-left: auto;
            margin-right: auto;
            width: 700px;
            padding: 20px;
            margin-bottom: 20px;

            border-radius: 25px;

            transition: 1s;
        }

        .block:hover {
            box-shadow: 0 0 15px #999999;
        }

        button {
            display: block;
            height: 50px;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0;

            background-color: #61c4e2;
            border: none;
            border-radius: 25px;
            box-shadow: 0 0 5px #999999;

            font-family: 'Montserrat';
            font-size: 20px;
            color: white;

            transition: 500ms;
        }

        button:hover {
            cursor: pointer;
            background-color: #61c4e2;
        }

        table {
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
            width: 100%;
            border-radius: 10px;

            font-size: 20px;
            background: #e5f8ff;
        }

        th {
            margin: 10px;
            padding: 10px;
            background: #5eaecc;
            width: auto;
        }

        td {
            width: auto;
            background: #5eaecc;
            text-align: center;
            padding: 10px;
        }

    </style>
</head>
<body>
    <div class="block">
        <table class="results">
            <tr>
                <th>X</th>
                <th>Y</th>
                <th>R</th>
                <th>Result</th>
                <th>Script timestamp</th>
                <th>Script runtime</th>
            </tr>
        <?php
        function isInside($xCoordinates, $yCoordinates, $rCoordinates) {
            if ($xCoordinates <= 0 && $yCoordinates <= 0 && $xCoordinates >= -$rCoordinates && $yCoordinates >= -$rCoordinates/2) { /*прямоугольник*/ 
                return true;
            }
            if ($xCoordinates <= 0 && $yCoordinates >= 0 && sqrt($xCoordinates*$xCoordinates+$yCoordinates*$yCoordinates) <= $rCoordinates) {   /*Часть окружности*/
                return true;
            }
            if ($xCoordinates >= 0 && $yCoordinates >= 0 && $yCoordinates >= $xCoordinates/2 + $rCoordinates/2 ){ /*Треугольник*/
                return true;
            }  
            return false;
        }

        function extendTable($i) {
            echo "<tr><td>" . $_SESSION[$i."xCoordinates"]
                . "</td><td>" . $_SESSION[$i."yCoordinates"]
                . "</td><td>" . $_SESSION[$i."rCoordinates"]
                . "</td><td>" . $_SESSION[$i."result"]
                . "</td><td>" . $_SESSION[$i."time"]
                . "</td><td>" . $_SESSION[$i."runtime"]
                . "</td></tr>";
        }

        function validateNumbers() {
		            $x_check = false;
		            if (is_numeric($_GET['xCoordinates']) && strlen((string)$_GET['xCoordinates']) <= 5) {
		                if (fmod($_GET["xCoordinates"], 1) == 0 && $_GET["xCoordinates"] >= -3 && $_GET["xCoordinates"] <= 5) {
		                    $x_check = true;
		                }
		            }

		            $y_check = false;
		            if (is_numeric($_GET['yCoordinates']) && strlen((string)$_GET['yCoordinates']) <= 5) {
		                if ($_GET["yCoordinates"] > -5 && $_GET["yCoordinates"] < 3) {
		                    $y_check = true;
		                }
		            }

		            $r_check = false;
		            if (is_numeric($_GET['rCoordinates']) && strlen((string)$_GET['rCoordinates']) <= 5) {
		                if (fmod($_GET["rCoordinates"], 1) == 0 && $_GET["rCoordinates"] > 2 && $_GET["rCoordinates"] < 5) {
		                    $r_check = true;
		                }
		            }

		            if ($x_check && $y_check && $r_check) {
		                return true;
		            }
		            return false;
		}
        //Печатаем таблицу со всеми предыдущими данными из $_SESSION
        for ($i = 0; $i < $_SESSION["requestsCount"]; $i++) {
            extendTable($i);
        }

        if (isset($_GET["xCoordinates"], $_GET["yCoordinates"], $_GET["rCoordinates"]) && validateNumbers()) {

            $xCoordinates = $_GET["xCoordinates"];
            $yCoordinates = $_GET["yCoordinates"];
            $rCoordinates = $_GET["rCoordinates"];

            //Добавляем в $_SESSION текущие данные
            $currentRequestId = $_SESSION["requestsCount"];

            $_SESSION[$currentRequestId."xCoordinates"] = $xCoordinates;
            $_SESSION[$currentRequestId."yCoordinates"] = $yCoordinates;
            $_SESSION[$currentRequestId."rCoordinates"] = $rCoordinates;
            $_SESSION[$currentRequestId."result"] = isInside($xCoordinates, $yCoordinates, $rCoordinates) ? "true" : "false";
            $_SESSION[$currentRequestId."runtime"] = round(microtime(true) - $start, 6) . " s";
            $_SESSION[$currentRequestId."time"] = date("d/m/Y h:i:s a", time());

            //Расширяем таблицу для текущего запроса
            extendTable($_SESSION["requestsCount"]);

            //Увеличиваем кол-во запросов на 1
            $_SESSION["requestsCount"]++;
        }
		echo $_SESSION["0yCoordinates"];
		echo $_SESSION["0xCoordinates"];
		echo $_SESSION["0rCoordinates"];
		echo $_SESSION["requestsCount"];
        ?>
        </table>
    </div>
</body>
</html>