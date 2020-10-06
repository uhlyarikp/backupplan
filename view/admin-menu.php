<?php
/*
 * Created on 2020.03.10.
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
if ($this->isAdmin()){
?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarAdmin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?=$this->lbl_admin?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarAdmin">
          <a class="dropdown-item" href="admin/users"><?=$this->lbl_users?></a>
          <a class="dropdown-item" href="admin/stickers"><?=$this->lbl_stickers?></a>
          <a class="dropdown-item" href="admin/replacements"><?=$this->lbl_stickersreplacement?></a>
          <a class="dropdown-item" href="admin/uploads/uploadstickers"><?=$this->lbl_uploadstickersdatafile?></a>
          <a class="dropdown-item" href="admin/campaigns"><?=$this->lbl_campaigns?></a>
          <a class="dropdown-item" href="admin/emails"><?=$this->lbl_emailtemplates?></a>
          <a class="dropdown-item" href="admin/accounttypes"><?=$this->lbl_memberships?></a>
          <a class="dropdown-item" href="admin/settings"><?=$this->lbl_settings?></a>
        </div>
      </li>
<?php
}
?>