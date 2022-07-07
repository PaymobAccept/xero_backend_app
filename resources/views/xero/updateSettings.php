<?php echo view('common.header');?>
<!------ Include the above in your HEAD tag ---------->

<div class="row affix-row">
    <div class="col-sm-3 col-md-2 affix-sidebar">
		<?php echo view('common.sidebar');?>
	</div>
	<div class="col-sm-9 col-md-10 affix-content">
		<div class="container">
			
<div class="page-header">
	<h3><span class="glyphicon glyphicon-th-list"></span> Update Settings</h3>
</div>

<?php 
if(isset($message)){
    echo $message;
}
?>
<form action="<?php echo URL('/update-settings');?>" enctype="multipart/form-data" method="post">
    <div id="collapse1" class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="control-label">First Name</label>
                            <input type="hidden" name="uniqueId" value="<?php echo $data[0]->uniqueId;?>" />
                            <input type="text" class="form-control" name="firstName" value="<?php echo $data[0]->firstName;?>"/>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Last Name</label>
                            <input type="text" class="form-control" name="lastName" value="<?php echo $data[0]->lastName;?>"/>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="text" class="form-control" name="email" value="<?php echo $data[0]->email;?>"/>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-3">
                        <div class="form-group">
                            <label class="control-label">change Password</label>
                            <input type="password" class="form-control" name="password" />
                        </div>
                    </div>
                   <div class="col-md-1 col-lg-3">
                        <div class="form-group" style="margin-top: 23px;">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    
                </div>

            </div>`
        </div>
     
 
 
</form>
		</div>
	</div>
</div>