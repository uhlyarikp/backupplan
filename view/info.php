<?php
include_once(viewPath.'header.php');
?>
<title><?=$this->lbl_error?></title>

</head>
<body>
<?php
	include_once(viewPath.'navigation.php');
?>

<div class="jumbotron text-center bg-custom1 text-white">
	<h1 class="main-title"><?=$this->lbl_error?></h1>
	<p class="main-sub-title"><?=$errorMsg?></p>
	<a class="btn btn-custom1-outline" href="<?=$_SERVER['HTTP_REFERER']?>"><?=$this->lbl_back?></a>
</div>
<?php
include_once(viewPath.'footer.php');?>

</body>
</html>
