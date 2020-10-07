<form id="myaccountform" method="post" action="backupplan/save">
<?php
csrfFormTokenInput('planform');
?>
<a href="<?=base_url()?>mailsender/sendbackups" class="btn btn-custom1-outline">Mentések küldése</a>
<div class="table-responsive">
	<table id="listTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
		<tr>
			<th>
				<div class="form-group">
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="checkall" name="selectall" value="1"/>
						<label for="checkall">Backup</label>
					</div>
				</div>
			</th>
			<th><?=$this->lbl_email?></th>
			<th><?=$this->lbl_backupfile?></th>
		</tr>
<?php
		if ($dbData != false){
			$i = 0;
			foreach ($dbData as $element){
				if (!in_array($element, $excludeDb)){
?>
				<tr>
					<td>
					<div class="form-group">
						<div class="form-check">
							<input type="checkbox" class="form-check-input planchk" id="plan<?=$i?>" name="planform_dbs[<?=$element?>]" value="1"
							<?php
							if (isset($planData['dbs'][$element])) echo 'checked="checked"';
							?>
							/>
							<label for="plan<?=$i?>"><?=$element?></label>
						</div>
					</div>
					</td>
					<td>
						<div class="form-group">
						    <input type="text" class="form-control" id="loginname<?=$i?>" name="planform_email[<?=$element?>]" maxlength="80" value="<?php if (isset($planData['email'][$element]) && $planData['email'][$element]!='') {echo $planData['email'][$element];}?>"/>
						</div>
					</td>
					<td>
					<?php
					if (is_file(backupsPath.$element.'.sql.gz')){
						echo '<a href="'.base_url().str_replace(rootPath,'',backupsPath).$element.'.sql.gz'.'">'.$element.'.sql.gz'.'</a>';
						echo '<br />';
						echo date("Y-m-d H:i:s", filemtime(backupsPath.$element.'.sql.gz'));
					}
					?>
					</td>
				</tr>
<?php
				$i++;
				}// end if (!in_array($element, $excludeDb)){
			}// end foreach ($dbData as $element){
		}// end if ($dbData != false){
?>
	</table>
</div>
<div class="row p-2">
	<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<p class="mt-1">
		<button class="btn form-control btn-custom1" id="submit" aria-label="<?=$this->lbl_save?>"><?=$this->lbl_save?></button>
		</p>
	</div>
</div>

</form>