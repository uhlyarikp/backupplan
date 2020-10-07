<?php
/*
 * Created on 2020.03.10.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once(viewPath.'header.php');
?>
<title><?=$this->lbl_databasebackupsettings?></title>


</head>
<body>
<?php
	include_once(viewPath.'navigation.php');
?>
<div class="container">
<h1 class="main-title text-primary"><?=$this->lbl_databasebackupsettings?></h1>

<?php
	$this->loadForm('backupplan/backupplan-form',$params);
?>
</div>
<?php include_once(viewPath.'footer.php');?>
<script language="JavaScript" type="text/javascript">
	$(document).ready(function(){

		$('#checkall').on('change', function(){
			$('.planchk').prop('checked',$(this).prop('checked'));
		});

	});
</script>

</body>
</html>
