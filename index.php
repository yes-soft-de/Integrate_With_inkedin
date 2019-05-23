<?php
    require 'init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sharing With linkedin api v2</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body,
        html {
            height: 100%;
        }

        .bg {
            background-image: url("images/cold.jpg");
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover
        }
    </style>
</head>

<body>
    <div class="bg">
        <div class="container">
            <br><br><br>
            <div class="row">
                <div class="col-6 offset-3" style="margin: auto; background: white; padding: 20px; box-shadow: 10px 10px 5px #888">
                    <div class="panel-heading">
                        <h1>share on linkedin</h1>
                        <p style="font-style: italic;">Using Linkedin API V2</p>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <!--will redirect to callback file-->
                        <a href="<?php echo $linkedin->getAuthUrl(); ?>" class="btn btn-primary btn-block">Linkedin</a>
                    </div>
                </div>
            </div>
        </div>

</body>
</html>