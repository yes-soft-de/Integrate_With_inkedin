<?php
    require 'init.php';
    // Fetch The Profile Object
    $profile = $linkedin->getPerson( $_SESSION['linkedInAccessToken'] );
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
                        <p style="font-style: italic;">Profile</p>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-3">
                                <img src="<?php echo $profile->profilePicture->{"displayImage~"}->elements[0]->identifiers[0]->identifier; ?>" alt="" class="thumbnail">
                            </div>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-12">
                                        <b>Profile ID :</b>
                                    </div>
                                    <div class="col-12">
                                        <?php echo $profile->id; ?>
                                    </div>
                                    <div class="col-12">
                                        <b>Profile Name :</b>
                                    </div>
                                    <div class="col-12">
                                        <?php echo $profile->firstName->localized->en_US; ?>
                                        <?php echo $profile->lastName->localized->en_US ?>
                                    </div>        
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5>Share Post</h5>
                        <form action="redirector.php">
                            <select class="form-control" name="type" id="type" required="required">
                                <option value="">Select Post Type</option>
                                <option value="text">Share Text Post</option>
                                <option value="link">Share Article/Link Post</option>
                                <option value="image">Share Image Post</option>
                            </select>
                            <br>
                            <input class="btn btn-danger btn-block" type="submit" value="Proceed">
                        </form>
                    </div>
                </div>
            </div>
        </div>

</body>
</html>