<!DOCTYPE html>
<html>
    <head>
        <title>Not found.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
                width: 50%;
                height: 50%;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body style="background: #FFF url({{ \PRStats\Models\Server::all()->random()->getLastMapImageUrl('background') }}) no-repeat fixed; background-size: cover;">
        <div class="container">
            <div class="content" style="background-color: rgba(0, 0, 0, 0.5)">
                <div class="title">Not found.</div>
                <p><a href="{{ url('/') }}" style="float: right"><img src="/images/logo.png" class="logo" /></a></p>
            </div>
        </div>
    </body>
</html>
