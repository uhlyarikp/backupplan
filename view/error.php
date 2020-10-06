<?php
include_once(viewPath.'header.php');
?>
<title><?=$this->lbl_error?></title>

</head>
<body>
<?php
	include_once(viewPath.'navigation.php');
?>

<div class="jumbotron text-center bg-danger text-white">
	<h1 class="main-title"><?=$this->lbl_error?></h1>
	<p class="main-sub-title"><?=$errorMsg?></p>
	<button class="btn btn-back" onClick="history.back();"><?=$this->lbl_back?></button>
</div>
<?php
include_once(viewPath.'footer.php');?>

</body>
</html>
