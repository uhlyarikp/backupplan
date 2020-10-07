<nav class="navbar navbar-expand-md navbar-dark sticky-top bg-dark">
  <a class="navbar-brand" href="<?=base_url()?>"><?=$this->lbl_shortwebsitename?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
<?php
	if ($this->loggedIn()){
?>
      <li class="nav-item active">
          <a class="nav-link" href="backupplan"><?=$this->lbl_databasebackupsettings?></a>
      </li>
<?php
	}else{
?>
      <li class="nav-item active">
        <a class="nav-link" href="login"><?=$this->lbl_login?></a>
      </li>
<?php
	}
?>
<?php
      if (isset($_SESSION['userdata']['loggedin']) && $_SESSION['userdata']['loggedin']=='1'){
?>
      <li class="nav-item active">
        <a class="nav-link" href="login/logout"><?=$this->lbl_logout?></a>
      </li>
<?php
      }
?>
    </ul>
  </div>
</nav>
