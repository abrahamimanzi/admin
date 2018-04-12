<?php
	$error_msg = '';
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'entity' => array(
			'name' => 'Target an entity',
			'required' => true,
		),
		'speaker_title' => array(
			'name' => 'Speaker title',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'serie_title' => array(
			'name' => 'Serie title',
			'required' => true
		),
		'start' => array(
			'name' => 'Start time',
			'required' => true,
			'hour_min' => true
		),
		'end' => array(
			'name' => 'End time',
			'required' => true,
			'hour_min' => true
		),
		'today' => array(
			'name' => 'Date',
			'required' => true
		),
		'description' => array(
			'name' => 'Description'
		)
	));
	if($validation->passed()){
		$scheduleClass = new Schedule();
		
		$speaker_title = sanName(Input::get('speaker_title','post'));
		$serie_title = sanLabel(Input::get('serie_title','post'));
		$entity = sanName(Input::get('entity','post'));
		$entityid = sanAsID(Input::get('entityid','post'));
		if($entity == 'Tv'){
			$entityid = '0';
		}
		$start = data_in(Input::get('start','post'));
		$end = data_in(Input::get('end','post'));
		$today = data_in(Input::get('today','post'));
		
		$schedule_date = $date;
		$tree_part = explode('-',$today);
		$schedule_date->setDate($tree_part[2],$tree_part[1],$tree_part[0]);
		$schedule_temp = $schedule_date->format('U');
		
		$week_year = $schedule_date->format('W-Y');
		$row_year = $schedule_date->format('Y');
		$row_months = $schedule_date->format('m');
		$row_day = $schedule_date->format('N');
		
		$cur_time = dates('H:i');
		$cur_date = dates('d-m-Y');
		
		$description = data_in(Input::get('description','post'));
		
		$temp = Config::get('time/temp');
        
		if( $today >= $cur_date && $start < $end){
			$scheduleClass->select("WHERE 
										(`entity` = '{$entity}' AND `entity_ID` = '{$entityid}')
										AND (`today`='{$today}' AND `start`='{$start}' AND `end`='{$end}'
												OR (`today`='{$today}' AND `start` <= '{$start}' AND `end` >= '{$end}')
											)
									");
			if($scheduleClass->count()){
				$form_error = true;
				$error_msg .= 'Time enterval expired ('.$start.' to '.$end.') , Please check your schedule ';
			}
		}else{
			$form_error = true;
			$error_msg .= ' Time enterval expired';
		}
		
        if($form_error == false){
            try{
                $scheduleClass->insert(array(
					'speaker_title' => $speaker_title,
					'entity' => $entity,
					'entity_ID' => $entityid,
					'serie_title' => $serie_title,
					'start' => $start,
					'end' => $end,
					'today' => $today,
					'day' => $row_day,
					'year' => $row_year,
					'months' => $row_months,
					'week_year' => $week_year,
					'this_temp' => $schedule_temp,
					'description' => $description
                ));

                Session::put('success', 'The schedule has been recorded successfully!');

            }catch(Exeption $e){
                $form_error = true;
				$error_msg = $e->getMessage();
				Session::put('errors',$error_msg);
            }
        }
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
    }
	
	if($form_error){
		Session::put('errors',$error_msg);
	}
?>