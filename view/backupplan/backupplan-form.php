<form id="myaccountform" method="post" action="backupplan/save">
<?php
csrfFormTokenInput('planform');
?>
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
			<th>
			</th>
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
							<input type="checkbox" class="form-check-input planchk" id="plan<?=$i?>" name="planform_tables[]" value="<?=$element?>"
							<?php
							if (is_array($planData) && in_array($element,$planData)) echo 'checked="checked"';
							?>
							/>
							<label for="plan<?=$i?>"><?=$element?></label>
						</div>
					</div>
					</td>
					<td>
					<?php
					if (is_file(backupsPath.$element.'.sql.gz')){
						echo '<a href="'.base_url().'backups/'.$element.'.sql.gz'.'">'.$element.'.sql.gz'.'</a>';
					}
					?>
					</td>
				</tr>
<?php
				}
				$i++;
			}

		}
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


