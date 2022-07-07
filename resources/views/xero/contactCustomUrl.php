<?php echo view('common.header');?>
<!------ Include the above in your HEAD tag ---------->

<div class="row affix-row">
    <div class="col-sm-3 col-md-2 affix-sidebar">
		<?php echo view('common.sidebar');?>
	</div>
	<div class="col-sm-9 col-md-10 affix-content">
		<div class="container">
			
				<div class="page-header">
	<h3><span class="glyphicon glyphicon-th-list"></span> Custom URL</h3>
</div>
<?php if(isset($data[0]->accessToken)){?>
<p>
    <?php echo URL('/');?>/xero-redirect?inv=[INVOICENUMBER]&uniqueId=<?php echo Session::get('uniqueId');?>
</p>
<?php }else {?>
<div class="alert alert-danger" role="alert">First Connect with xero !</div>
<?php }?>
		</div>
	</div>
</div>