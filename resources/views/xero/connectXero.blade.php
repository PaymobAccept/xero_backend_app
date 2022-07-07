<?php echo view('common.header');?>
<!------ Include the above in your HEAD tag ---------->

<div class="row affix-row">
    <div class="col-sm-3 col-md-2 affix-sidebar">
		<?php echo view('common.sidebar');?>
	</div>
	<div class="col-sm-9 col-md-10 affix-content">
		<div class="container">
			
				<div class="page-header">
	<h3><span class="glyphicon glyphicon-th-list"></span> Connectivity</h3>
</div>
<p>
    <?php if(isset($companyName) && $companyName != '') {?>
<h4>Connected To <?php echo $companyName;?></h4>
<a style="margin-top: 18px;" class="btn btn-primary" href="<?php echo url('xero-disconnect'); ?>">Disconnect Xero</a><br>
<?php }else {?>
<a style="margin-top: 18px;" class="btn btn-primary" href="<?php echo url('xero-connect'); ?>">Connect Xero</a><br>
<?php }?>
    
</p>
		</div>
	</div>
</div>