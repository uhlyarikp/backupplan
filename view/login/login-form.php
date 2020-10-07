<form id="loginform" method="post" action="login/login">
<?php $this->token->csrfFormTokenInput('loginform');?>
	<div class="form-group">
	    <label for="loginname"><?=$this->lbl_loginname?></label>
	    <input type="text" class="form-control" id="loginname" name="login_loginname" maxlength="20" value=""/>
	</div>
	<div class="form-group">
		<label for="pwd"><?=$this->lbl_password?></label>
			<div class="input-group">
				<input type="password" class="form-control" autocomplete="new-password" id="pwd" name="login_password" maxlength="32" >
				<div class="input-group-addon">
			        <span class="input-group-text" id="showpwd"><a href="" id="href" class="show_hide_password"><i data-p-id="pwd" class="fa fa-eye" aria-hidden="true"></i></a></span>
				</div>
			</div>
	</div>
		<input type="submit" name="submit" class="btn btn-primary" value="<?=$this->lbl_login?>" />
</form>
