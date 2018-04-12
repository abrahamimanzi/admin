<section class="content-header">
	<div class="page_header">
		<div class="row">
			<div class="col-xs-12 col-sm-8">
				<h3 class="content-title">
                    <a href="<?=DNADMIN?>/app/EventApp/home"><i class="fa fa-book fa-lg pink-col"></i></a> <small> <i class="fa fa-angle-double-right"></i> </small>
					<?=$event_data->name;?>
				</h3>
			</div>
			<div class="col-xs-12 col-sm-4 hidden-xs">
				<!-- Main search form -->
				<form action="#" method="get" class="mainsearch-form">
					<div class="input-group">
						<input type="text" name="keyword" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
                            <input name="search" value="1" type="hidden">
							<button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<section class="content-header navbar_header">
	<div>
		 <nav class="navbar navbar-static-top navbar_content" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-branch-menu">
            <ul class="nav navbar-nav">
                
              <li class="">
                <a href="<?=DNADMIN?>/app/EventApp/<?=$event_ID?>/"> <i class="fa fa-home"></i> Route </a>
              </li>
              <!-- New activities Menu -->
              <li class="dropdown notifications-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-angle-down"></i> Participants
                </a>
                <ul class="dropdown-menu dropdown-menu-right" style="height: 83px;">
                  <li>
                    <!-- Inner Menu: contains the notifications -->
                    <ul class="menu">
                      <li><!-- start notification -->
                        <a href="<?=DNADMIN?>/app/EventApp/<?=$event_ID?>/participant-new">
                          <i class="fa fa-plus text-blue"></i>New participant
                        </a>
                      </li><!-- end notification -->
                      <li><!-- start notification -->
                        <a href="<?=DNADMIN?>/app/EventApp/<?=$event_ID?>/participant-list">
                            <i class="fa fa-bars text-blue"></i>All participants
                        </a>
                      </li><!-- end notification -->
                    </ul>
                  </li>
                </ul>
              </li>
                
            </ul>
          </div>
        </nav>
	</div>
</section>

<!-- Main content -->
<section class="content">
		
 <!-- Small boxes (Stat box) -->
	  <div class="row">
          <div class="col-sm-12 col-md-12">
                 <!--RECENT REGISTER -->
              <div>
                                        
                     <?php 

                    $search_sql = "";
                    $search_form = false;
                    $sql_val_array = array('Deleted',$event_ID);
                   
                    if(Input::checkInput('search','get','1')){
                        $search_form = true;
                        $fullname = data_in(Input::get('name','get'));
                        $keyword = data_in(Input::get('keyword','get'));
                        $state = data_in(Input::get('state','get'));

                        if(Input::checkInput('keyword','get','1')){
                            $search_sql .= " 
                                AND (`firstname` LIKE '%{$keyword}%' || 
                                    `lastname` LIKE '%{$keyword}%' || 
                                    `category` LIKE '%{$keyword}%'|| 
                                    `email` LIKE '%{$keyword}%' ||
                                    `telephone` LIKE '%{$keyword}%' ||
                                    `package_type` LIKE '%{$keyword}%' ||
                                    `state` LIKE '%{$keyword}%' ||
                                    `company_name` LIKE '%{$keyword}%' ||
                                    `ID` = '{$keyword}')";
                        }
                        if(Input::checkInput('name','get','1')){
                            $search_sql .= " 
                                AND (`firstname` LIKE '%{$fullname}%' || 
                                    `lastname` LIKE '%{$fullname}%' || 
                                    `category` LIKE '%{$fullname}%')";
                        }
                        if(Input::checkInput('state','get','1')){
                            if(Input::get('state','get')=='Activated'){
                                $search_sql .= " AND `state` = ?";
                                $sql_val_array[] = $state;
                            }elseif(Input::get('state','get')=='Deactivated'){
                                $search_sql .= " AND `state` = ?";
                                $sql_val_array[] = $state;
                            }
                        }
                    }
                    ?>
                    
                        
                    
                </div>
                <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Participant list</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    
                    <div class="box-header with-border">
                        <form method="get" action="">	
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-xs-5 col-md-3">
                                    <input class="form-control" name="name" type="search" value="<?php if($search_form == true){ echo Input::get('title','post');}?>" placeholder="Names ">
                                </div>
                                <div class="col-xs-5  col-md-3">
                                    <select class="form-control" name="state" >
                                        <option value="">-all-</option>
                                        <option value="Activated">Activated</option>
                                        <option value="Deactivated">Deactivated</option>
                                    </select>
                                </div>
                                <div class="col-xs-2 col-md-2">
                                    <input name="search" value="1" type="hidden">
                                    <button class="btn btn-default pull-right" type="submit" style="display: inline-block; margin-top: 4px!important"><i class=" fa fa-search"></i> <span class="hidden-xs">Search</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    
                    <div class="box-body">
                      <div class="table-responsive">
                        <table class="table no-margin">
                          <thead>
                          <tr>
                            <th style="width: 80px">UniqueID</th>
                            <th>Full Name</th>
                            <th>Category</th>
                            <th>Package</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Company</th>
                            <th style="width: 90px">Status</th>
                            <th style="width: 60px">Menu</th>
                          </tr>
                          </thead>
                          <tbody>
                            <?php
                              $participantTable = new Participant();
								$participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `state`!=? AND `event_ID`=? $search_sql ",$sql_val_array);
                               // $events_select = $participantDb->get('all','events_participant',array('company_ID','=',$company_data->ID));
                                
								/*Start Pagination Setting*/
									$rowsLimit = 20;
									$pageNumber = 0;
									if(Input::checkInput('page','get','1')){
										$pageNumber = (int)sanAsID(Input::get('page','get'));
									}
									$requesturl = "?check";
									
									$totalStore=$participantTable->count();
									$totalPages = upfloat($totalStore/$rowsLimit);
									$offsetNumber = $pageNumber*$rowsLimit;
									if($offsetNumber >= $totalStore){
										$pageNumber=0;
										$offsetNumber = $pageNumber*$rowsLimit;
									}
								/*End Pagination Setting*/
                                
                                $dataArr = array();
                              
                              $participantTable = new Participant();
                                                          
                              $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `state`!=? AND `event_ID`=? $search_sql ORDER BY ID DESC LIMIT {$offsetNumber},{$rowsLimit}",$sql_val_array);
                              
								if($participantTable->count()){
									$i = 0;
									foreach($participantTable->data() as $participant_data){
										$i++;
                                        $participant_ID = $participant_data->ID;
									?>
                                        <?php $stateColor =  Functions::getStateCol($participant_data->state);?>
                                      <tr class="row_layout">
                                        <td>
                                            <a style="border-color: <?=$stateColor?>" class="id popover-el participant-menu-popover-<?=$participant_data->ID?>"><?=$participant_data->ID;?> <i class="fa fa-angle-down pull-right menu_icon"></i></a>
                                        </td>
                                        <td><?=$participant_data->firstname?> <?=$participant_data->lastname?></td>
                                        <td><?=$participant_data->category?></td>
                                        <td><?=$participant_data->package_type?></td>
                                        <td><?=$participant_data->email?></td>
                                        <td><?=$participant_data->telephone?></td>
                                        <td><?=$participant_data->company_name?></td>
                                        <td><span style="color: <?=$stateColor?>"><?=$participant_data->state?></span></td>
                                        <td>
                                            <div>
                                              <a class="participant-menu-popover-<?=$participant_data->ID?> popover-el" tabindex="0" role="button" >More</a>
                                            </div>
                                            
                                            <div id="participant-menu-content-<?=$participant_data->ID?>" class="hidden">
                                                <form method="post">
                                                    <input type="hidden" name="webToken" value="56">
                                                    <input type="hidden" name="request" value="participant-state">
                                                    <input type="hidden" name="participant-event_id" value="<?=$event_ID?>">
                                                    <input type="hidden" name="participant-company_id" value="<?=$company_data->ID?>">
                                                    <input type="hidden" name="participant-id" value="<?=$participant_data->ID?>">
                                                    <ul class="popover-menu-list">
                                                        <li><a href="<?=DNADMIN?>/app/EventMan/<?=$event_ID?>/participant-print/" class="menu"><i class="fa fa-print icon"></i> Print Bagde</a></li>
                                                        <li><a class="menu"><i class="fa fa-eye icon"></i> View</a></li>
                                                          
                                                        <?php if($participant_data->state!='Attended'){?>
                                                        <li> 
                                                            <button class="menu" name="attend" type="submit"><i class="fa fa-check-circle icon"></i> Attended</button>
                                                        </li>
                                                        <?php }?>
                                                        
                                                        <?php if($participant_data->state!='Activated'){?>
                                                        <li>
                                                            <button class="menu" name="deactivate" type="submit"><i class="fa fa-times-circle icon"></i> Deactivate</button>
                                                        </li>
                                                        <?php }?>
                                                        
                                                        <?php if($participant_data->state!='Deactivated'){?>
                                                        <li>
                                                            <button class="menu" name="activate" type="submit"><i class="fa fa-check icon"></i> Activate</button>
                                                        </li>
                                                        <?php }?>
                                                        <li><a class="menu"><i class="fa fa-pencil icon"></i> Edit</a></li>
                                                        <li><a class="menu"><i class="fa fa-trash icon"></i> Delete</a></li>
                                                        <li><a class="menu popover-close" data-popoverid=".participant-menu-popover-<?=$participant_data->ID?>"><i class="fa fa-times icon"></i> Cancel</a></li>
                                                    </ul>
                                                </form>
                                            </div>

                                            <script>
                                                $(function(){
                                                    // Enables popover #2
                                                    $(".participant-menu-popover-<?=$participant_data->ID?>").popover({
                                                        html : true, 
                                                        placement : 'bottom', 
                                                        trigger: 'manual',
                                                        content: function() {
                                                          return $("#participant-menu-content-<?=$participant_data->ID?>").html();
                                                        },
                                                        title: function() {
                                                          return $("#eparticipant-menu-title-<?=$participant_data->ID?>").html();
                                                        }
                                                    });
                                                });
                                            </script>
                                        </td>
                                      </tr>	
                                  <?php }?>
                                <tr>
                                    <td colspan="9">
                                        <?php include 'views/pagination'.PL;?>
                                    </td>
                                </tr>
                            <?php }else{?>
                                <tr>
                                    <td colspan="7"><br/>No Participant recorded</td>
                                </tr>
                            <?php }?>
                          </tbody>
                        </table>
                      </div>
                      <!-- /.table-responsive -->
                        
                        <script>
                            $(document).on("click", ".popover-close", function(e) {
                                $($(this).data('popoverid')).popover('hide');
                                $($(this).data('popoverid')).removeClass('open');
                            });
                            
                            $('.popover-el').click(function (e) {
                                if(!$(this).hasClass('open')){
                                    $(this).popover('show');
                                    $(this).addClass('open');
                                }else{
                                    $(this).popover('hide');
                                    $(this).removeClass('open');
                                }
                            });

                       
                        $(document).ready(function(){
                            $('body').on('click', function (e) {
                                $('.popover-el.open').each(function () {
                                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                                        $(this).popover('hide');
                                        $(this).removeClass('open');
                                    }
                                });
                            });
                        });
                        </script>
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                      <a href="<?=DNADMIN?>/app/EventApp/<?=$event_ID?>/participant-new" class="btn btn-sm btn-info btn-flat pull-left">Add New Participant</a>
                      <a href="<?=DNADMIN?>/app/EventApp/<?=$event_ID?>/participant-list" class="btn btn-sm btn-default btn-flat pull-right">View All Participants</a>
                    </div>
                    <!-- /.box-footer -->
                  </div>
                 <!--RECENT REGISTER -->
          </div>
	  </div><!-- /.row -->
    
	  <div class="row">
		<div class="col-md-8 col-xs-12">
           
		</div><!-- ./col -->
		<div class="col-md-4 col-xs-12">
           
		</div><!-- ./col -->
	  </div><!-- /.row -->
    
</section>