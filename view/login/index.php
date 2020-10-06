<?php
include_once(viewPath.'header.php');
?>
<title><?=$this->lbl_websitename?></title>
<link rel="preload" href="css/font-awesome-4.3.0/css/font-awesome.min.css" as="style">
<link rel="stylesheet" href="css/font-awesome-4.3.0/css/font-awesome.min.css" media="print" onload="this.media='all'">
</head>
<body>
<?php
 include_once(viewPath.'navigation.php');
?>

<div class="jumbotron text-center bg-custom1 text-white">
  <h1 class="main-title"><?=$this->lbl_login?></h1>
</div>

<div class="container">
<?php
if (isset($errorMsg) && $errorMsg==$this->lbl_loginfailed) echo '<h3 class="text-danger">'.$this->lbl_loginfailed.'</h3>';
?>

<?php $this->loadForm('login/login-form');?>
</div>
<?php include_once(viewPath.'footer.php');?>
<script language="JavaScript" type="text/javascript">
  	jQuery(document).ready(function(){
		function showHide(pwdInput, iTag){
			if ( pwdInput.attr("type") == "text" ){
	           pwdInput.attr("type", "password");
	           iTag.addClass( "fa-eye" );
	           iTag.removeClass( "fa-eye-slash" );
			}else if (pwdInput.attr("type") == "password" ){
	            pwdInput.attr("type", "text");
	            iTag.removeClass( "fa-eye" );
	            iTag.addClass( "fa-eye-slash" );
			}
		}

	    $(".show_hide_password i").on("click", function(event) {
	        event.preventDefault();
			pwdInput = $("#"+$(this).attr("data-p-id"));
			showHide(pwdInput, $(this));
	    });

	    $(".show_hide_password").on("keypress", function(event) {
	        event.preventDefault();
			pwdInput = $("#"+$(this).children().attr("data-p-id"));
			iTag = $(this).children();
			showHide(pwdInput, iTag);
	    });



	    $("#loginform").validate({
			onkeyup:false,
			rules: {
				login_email:{
					required: true,
					validate_email: true,
				},
				login_password:{
					required: true,
				},
			},
			messages: {
				login_email: {
					required: "<?=$this->lbl_emailrequired?>",
					email: "<?=$this->lbl_incorrectemailformat?>",
				},
				login_password: {
					required: "<?=$this->lbl_passwordrequired?>",
				},
			},

			errorPlacement: function ( error, element ) {
				error.addClass( "invalid-feedback" );
				element.closest('.form-group').append(error);
			},

		    highlight: function (element, errorClass, validClass) {
		        $(element).addClass('is-invalid');
		    },

		    unhighlight: function (element, errorClass, validClass) {
		        $(element).removeClass('is-invalid');
		    }
		});


		$.validator.addMethod("validate_email", function(value, element) {

		    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
		        return true;
		    } else {
		        return false;
		    }
		}, "<?=$this->lbl_incorrectemailformat?>");


  	}); // end $(document).ready(function(){
</script>

</body>
</html>
