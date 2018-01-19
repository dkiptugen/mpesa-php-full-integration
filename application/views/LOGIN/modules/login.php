<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title></title>
    <link rel="stylesheet" href="<?=base_url("assets/css/bootstrap.css"); ?>">
    <link rel="stylesheet" href="<?=base_url("assets/css/bootstrap-grid.css"); ?>">
    <link rel="stylesheet" href="<?=base_url("assets/css/bootstrap-reboot.css"); ?>">
    <link rel="stylesheet" href="<?=base_url("assets/css/login.css"); ?>">
</head>
<body>
<nav class="navbar navbar-light bg-faded mb-3">
    <a class="navbar-brand" href="#">
        <img src="<?=base_url("assets/img/logo.png"); ?>" width="30" height="30" alt="">
    </a>
</nav>
<div class="row">
	
	<div class="col-md-2 offset-md-4 ">
	   	<div class="card mx-3">
		   	<div class="card-block">
		   		<form action="" method="post" role="form" class="form form-horizontal">
		   		    <div class="form-group row">
		   		        <label for="username" class="control-label col-md-2">Username</label>
		   		        <div class="col-md-8">
		   		            <input type="text" name="username" id="username" class="form-control">
		   		        </div>
		   		    </div>
		   		    <div class="form-group row">
		   		        <label for="password" class="control-label col-md-2">Password</label>
		   		        <div class="col-md-8">
		   		            <input type="password" name="password" id="password" class="form-control">
		   		        </div>
		   		    </div>
		   		    <div class="clearfix"></div>
		   		    <div class="form-group col-md-10 p-0 row justify-content-end">
		   		        <button type="submit" class="btn btn-primary ml-auto">Login</button>
		   		    </div>
		   		</form>
		   	</div>
	   	</div>
	</div>
</div>
<script src="<?=base_url("assets/js/jquery.js"); ?>" type="text/javascript" />
<script src="<?=base_url("assets/js/bootstrap.js"); ?>" type="text/javascript" />
<script src="<?=base_url("assets/js/login.js"); ?>" type="text/javascript" />
</body>
</html>