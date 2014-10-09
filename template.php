<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
<?php
if (!empty($isLoggedIn)) {
    ?>
    <h1>You are logged in</h1>
    <a href="/" class="btn btn-default">Home</a>
    <h1>Your profile data:</h1>
    <pre><?php echo $profileData; ?></pre>
    <?php
} else {
    ?>
    <h1>Social Example</h1>
    <a class="btn btn-default" href="/facebook.php" title="Login with Facebook">
        <i class="glyphicon glyphicon-"></i> Login with Facebook
    </a>
    <a class="btn btn-default" href="/linkedin.php" title="Login with Linkedin">Login with
        LinkedIn</a>
    <a class="btn btn-default" href="/teamviewer/index.php" title="Login with TeamViewer">Login with
        TeamViewer</a>
<?php
}
?>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>