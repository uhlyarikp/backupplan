<?php
include_once(viewPath.'header.php');
?>
<title><?=$this->lbl_websitename?></title>

</head>
<body>
<?php
 include_once(viewPath.'navigation.php');
?>

<div class="container">
	<h2 class="main-title"><?=$this->lbl_loggedout?></h2>
</div>
<?php include_once(viewPath.'footer.php');?>
</body>
</html>
