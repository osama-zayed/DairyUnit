<!DOCTYPE html>
<html lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('title')
    </title>
    <style>
        html,
        body {
            margin: 10px;
            padding: 10x;
            direction: rtl;
            font-family: 'Arial', sans-serif;
        }

        .header {
            width: 100%;
            overflow: hidden;
            height: 100px;
        }

        .left,
        .center,
        .right {
            width: 33.33%;
            float: left;
            text-align: center;
        }



        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }

        table thead th {
            text-align: center;
            font-size: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            font-size: 10px;
        }

        .order-details thead tr th {
            background-color: #1f4e78;
            color: #fff;
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt-3 {
            margin-top: 3px;
        }
    </style>
</head>

<body>
    @include('report.layouts.header')
    <table class="order-details">
        <thead>
            <tr>
                <td colspan="@yield('colspan')"
                    style="text-align: center;border: solid 1px black;font-size: 18px;font-weight: 700;background-color: #ddebf7">
                    @yield('title')
                </td>
            </tr>
            @yield('thead')

        </thead>
        <tbody>
            @yield('tbody')


        </tbody>
    </table>
</body>

</html>
