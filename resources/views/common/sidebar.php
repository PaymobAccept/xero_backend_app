	<div class="sidebar-nav">
  <div class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <span class="visible-xs navbar-brand">Sidebar menu</span>
    </div>
    <div class="navbar-collapse collapse sidebar-navbar-collapse">
      <ul class="nav navbar-nav" id="sidenav01">
        <li class="active">
          <a href="#" data-toggle="collapse" data-target="#toggleDemo0" data-parent="#sidenav01" class="collapsed">
          <h4>
          <b><?php echo Session::get('name');?></b>
          <br>
          
          </h4>
          </a>
          <div class="collapse" id="toggleDemo0" style="height: 0px;">
            <ul class="nav nav-list">
              <li><a href="#">ProfileSubMenu1</a></li>
              <li><a href="#">ProfileSubMenu2</a></li>
              <li><a href="#">ProfileSubMenu3</a></li>
            </ul>
          </div>
        </li>
        <li>
          <a href="<?php echo URL('dashboard');?>" >
          <span class="glyphicon glyphicon-cloud"></span> Xero Connectivity <span class="caret pull-right"></span>
          </a>
        
        </li>
        <?php if(Session::get('type') == 1) {?>
        <li class="">
          <a href="<?php echo URL('/create-custom-url');?>" >
          <span class="glyphicon glyphicon-inbox"></span> Custom URL <span class="caret pull-right"></span>
          </a>
         
        </li>
        <?php } else{?>
        <li class="">
          <a href="<?php echo URL('/contact-custom-url');?>" >
          <span class="glyphicon glyphicon-inbox"></span> Custom URL <span class="caret pull-right"></span>
          </a>
         
        </li>
        <li class="">
          <a href="<?php echo URL('/update-settings');?>" >
          <span class="glyphicon glyphicon-inbox"></span> Settings <span class="caret pull-right"></span>
          </a>
         
        </li>
        <?php }?>
        <li><a href="<?php echo URL('/logout');?>"><span class="glyphicon glyphicon-lock"></span>Logout</a></li>
        <!--<li><a href="#"><span class="glyphicon glyphicon-calendar"></span> WithBadges <span class="badge pull-right">42</span></a></li>-->
        <!--<li><a href=""><span class="glyphicon glyphicon-cog"></span> PreferencesMenu</a></li>-->
      </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>