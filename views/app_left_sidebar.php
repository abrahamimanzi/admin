<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel" style="height: 58px;">
			<div class="pull-left image">
				<?php
				if(file_exists(DNADMIN._.$session_user_data->profile)){?>
					<img src="<?=DNADMIN._.$session_user_data->profile;?>" class="img-circle" alt="User Image">
				<?php }else{?>
					<span style="display: block; padding: 8px 18px; border-radius: 100%; background: #333d41; color: #fff; font-size: 20px; font-weight: 600; border"><?php echo substr($session_user_data->firstname,0,1);?></span>
				<?php }?>
			</div>
			<div class="pull-left info">
			  <p><?=$session_user_data->firstname?></p>
			  <!-- Status -->
			  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>
		
		<!-- Sidebar Menu -->
		<ul class="sidebar-menu">
			<li class="header">Menu</li>
			<!-- Optionally, you can add icons to the links -->
            <li class=""><a href="<?=DNADMIN?>/app/EventApp/5"><i class="fa fa-laptop"></i> <span>Transform Africa 2018</span></a></li>
           
<!--
            <li class="treeview <?php if($url_struc['tree'] == "app" && $url_struc['app-idname']=="EventApp"){ echo 'actived';}?>">
                <a href="#"><i class="fa fa-book blueapp-col"></i> <span>Event-App</span></a>
                <ul class="treeview-menu">
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/home"><i class="fa fa-circle-o icon"></i> <span>Home</span></a></li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="list"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/list"><i class="fa fa-circle-o icon"></i> <span>List Event</span></a
                    </li>
				</ul>
            </li>
-->
            <li class="treeview <?php if($url_struc['tree'] == "app" && $url_struc['app-idname']=="EventApp"){ echo 'actived';}?>">
                <a href="#"><i class="fa fa-book blueapp-col"></i> <span> Participants</span></a>
                <ul class="treeview-menu">
            <?php
                if($session_user_data->groups == 'Admin' || $session_user_data->groups == 'RG-Admin' || $session_user_data->groups == 'RG-SUPER-Admin'){?>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/"><i class="fa fa-circle-o icon"></i> <span>Pending status</span></a>
                    </li>
                    <?php }?>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-list"><i class="fa fa-circle-o icon"></i> <span>All Participants</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-delegate"><i class="fa fa-circle-o icon"></i> <span>Delegates</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-government"><i class="fa fa-circle-o icon"></i> <span>Government</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-media"><i class="fa fa-circle-o icon"></i> <span>Media</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-noc"><i class="fa fa-circle-o icon"></i> <span>NOC</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-liaison"><i class="fa fa-circle-o icon"></i> <span>Delegate Liaison</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-armed"><i class="fa fa-circle-o icon"></i> <span>Armed Security</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-security"><i class="fa fa-circle-o icon"></i> <span>Security</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-protocol"><i class="fa fa-circle-o icon"></i> <span>Protocal</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-medical"><i class="fa fa-circle-o icon"></i> <span>Medical</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-catering"><i class="fa fa-circle-o icon"></i> <span>Catering</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-technician "><i class="fa fa-circle-o icon"></i> <span>Production Technician</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-contractor"><i class="fa fa-circle-o icon"></i> <span>Contractor</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-organiser"><i class="fa fa-circle-o icon"></i> <span>Organiser</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-approved"><i class="fa fa-circle-o icon"></i> <span>Approved</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-denied"><i class="fa fa-circle-o icon"></i> <span>Denied</span></a>
                    </li>
                    <!-- <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-exhibitor"><i class="fa fa-circle-o icon"></i> <span>Exhibitors</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-visitor"><i class="fa fa-circle-o icon"></i> <span>Visitors</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-speaker"><i class="fa fa-circle-o icon"></i> <span>Speakers</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-ftg"><i class="fa fa-circle-o icon"></i> <span>Face the gorilla</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-msgeek"><i class="fa fa-circle-o icon"></i> <span>Ms geek</span></a>
                    </li>
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-backtransfer"><i class="fa fa-circle-o icon"></i> <span>Pending bank transfer</span></a>
                    </li> -->
                    <li class="<?php if($url_struc['tree']=="app" && $url_struc['trunk']=="home"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/app/EventApp/5/participant-finder"><i class="fa fa-circle-o icon"></i> <span>Advanced Filter</span></a>
                    </li>
				</ul>
            </li>
            <?php
                if($session_user_data->groups == 'Admin' || $session_user_data->groups == 'TAS-Admin' || $session_user_data->groups == 'TAS-SUPER-Admin' || $session_user_data->groups == 'OGS-User' || $session_user_data->groups == 'RG-Admin' || $session_user_data->groups == 'RG-SUPER-Admin'){?>
            
            <li class="treeview <?php if($url_struc['tree'] == "company" && $url_struc['trunk']=="subscriber"){ echo 'actived';}?>">
                <a href="#"><i class="fa fa-user blueapp-col"></i> <span>Managed registration</span></a>
                <ul class="treeview-menu">
                    <li class="<?php if($url_struc['trunk']=="subscriber" && $url_struc['branch']=="admins"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/company/subscriber/admins"><i class="fa fa-circle-o icon"></i> <span>Group admin</span></a></li>
                    <li class="<?php if($url_struc['trunk']=="subscriber" && $url_struc['branch']=="new"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/company/subscriber/new"><i class="fa fa-circle-o icon"></i> <span>Create group admin</span></a></li>
                    <li class="<?php if($url_struc['trunk']=="subscriber" && $url_struc['branch']=="newinvite"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/company/subscriber/newinvite"><i class="fa fa-circle-o icon"></i> <span>Send individual invite</span></a></li>
                    <li class="<?php if($url_struc['trunk']=="subscriber" && $url_struc['branch']=="list"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/company/subscriber/list"><i class="fa fa-circle-o icon"></i> <span>View all</span></a></li>
				</ul>
            </li>
            <?php } ?>
            
			<li class="header">Company</li>
            <li class="treeview <?php if($url_struc['tree'] == "company" && $url_struc['trunk']=="users"){ echo 'actived';}?>">
                <a href="#"><i class="fa fa-fort-awesome"></i> <span>Accounts</span></a>
                <ul class="treeview-menu">
					<li class="<?php if($url_struc['tree']=="company" && $url_struc['trunk']=="users"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/company/users/list"><i class="fa fa-circle-o icon"></i><span>Users</span></a></li>
                    <?php
                        if($session_user_data->groups == 'Admin' || $session_user_data->groups == 'RG-Admin' || $session_user_data->groups == 'RG-SUPER-Admin'){?>
					<li class="<?php if($url_struc['tree']=="company" && $url_struc['trunk']=="users"){ echo 'actived';}?>">
                        <a href="<?=DNADMIN?>/company/logs/list"><i class="fa fa-circle-o icon"></i><span>Logs</span></a></li>
                    <?php } ?>
				</ul>
            </li>
            <li class=""><a href="<?=DNADMIN?>/logout"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
			
		</ul><!-- /.sidebar-menu -->
	</section>
	<!-- /.sidebar -->