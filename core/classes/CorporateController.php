<?php
class CorporateController  
{
	public static function add($form='Admin'){
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
        $error_msg = '';
        

            $prfx = 'register-';
            foreach($_POST as $index=>$val){
                $ar = explode($prfx,$index);
                if(count($ar)){
                    $_SUBMIT[end($ar)] = $val;
                }
            }

        $str = new \Str();

        $validate_array = array();
        $validate_array_1 = array();
        $validate_array_2 = array();

        global $session_subscriber;
        
        if(!$session_subscriber->isLoggedIn()){
            $validate_array_2 = array(
                    'check_capcha' => array(
                        'name' => 'Captcha',
                        'matches' => 'capcha',
                        'required' => true
                    ),
                    // 'check' => array(
                    //     'name' => 'To agree the Terms and Conditions',
                    //     'required' => true
                    // )
                    );
        }

        $validate_array_0 = array(
                    'title' => array(
                        'name' => 'Title',
                        'string' => true,
                        'required' => true
                    ),
                    'firstname' => array(
                        'name' => 'First name',
                        'string' => true,
                        'required' => true
                    ),
                    'lastname' => array(
                        'name' => 'Last name',
                        'string' => true,
                        'required' => true
                    ),
                    'email' => array(
                        'name' => 'Email',
                        'matches'=>'confirm_email',
                        // 'unique'=>'events_participant',
                        'required' => true
                    ),
                    'company' => array(
                        'name' => 'company',
                        'required' => true
                    ),
                    'telephone' => array(
                        'name' => 'Telephone',
                        'required' => true
                    )
                );

        $validate_array = array_merge($validate_array_0,$validate_array_2);

        $validation = $validate->check($_SUBMIT, $validate_array);

        if($validation->passed()){

            $accommadationTable = new \Corporate();


            $title = $str->sanAsName(@$_SUBMIT['title']);
            $firstname = $str->sanAsName(@$_SUBMIT['firstname']);
            $lastname = $str->sanAsName(@$_SUBMIT['lastname']);
            $email= $str->data_in(@$_SUBMIT['email']);
            $telephone= $str->data_in(@$_SUBMIT['telephone']);
            $company_name = $str->data_in(@$_SUBMIT['company']);
            $company_category = $str->data_in(@$_SUBMIT['orgcategory']);
            // $company_category = $str->data_in(@$_SUBMIT['company_category']);
            $jobtitle = $str->data_in(@$_SUBMIT['jobtitle']);
            $country = $str->data_in(@$_SUBMIT['country']);
            $gold = $str->data_in(@$_SUBMIT['gold']);
            $platinum = $str->data_in(@$_SUBMIT['platinum']);



            $seconds = \Config::get('time/seconds');


            if($diagnoArray[0] == 'NO_ERRORS'){

                try{
                    $accommadationTable->insert(array(
                        'title' => $title, 
                        'firstname' => $firstname, 
                        'lastname' => $lastname, 
                        'email' => $email, 
                        'telephone' => $telephone, 
                        'company_name' => $company_name, 
                        'company_category' => $company_category, 
                        'jobtitle' => $jobtitle, 
                        'country' => $country, 
                        'gold' => $gold, 
                        'platinum' => $platinum, 
                        'added_date' => Dates::get('d-m-Y',$seconds),
                        'form' => $form
                    ));

                    $accommadationTable->selectQuery("SELECT* FROM `events_corporate` WHERE `email`=? ORDER BY `ID` DESC LIMIT 1",array($email));

                    // if($participantTable->count() && $participantTable->first()->email == $email){
                    if($accommadationTable->count() && $accommadationTable->first()->email == $email){
                        $accommadation_data = $accommadationTable->first();
                        // $participant_data = $participantTable->first();


                        $accommadation_ID = $accommadation_data->ID;


                        // END INVITATION //
                        
                        $subject= 'Corporate registration request for Transform Africa Summit ';

                        $messageText_0= 'Dear <b>'.$accommadation_data->firstname.' '.$accommadation_data->lastname.'</b>,';

                        $messageText_1= 'We have received your enquiry for corporate group registration.';

                        $messageText_2= 'The Smart Africa team will be touch with you shortly.';

                        $messageText_3= 'We look forward to seeing you at the Transform Africa Summit!';

                        $message_body = '
                            <body>
                                <div style="padding: 10px; margin-left: 10px margin-right: 10px">

                                    <section>
                                        <p style="margin-bottom: 25px; font-size: 13px;">
                                            '.$messageText_0.'
                                        </p>
                                        <p style="font-size: 13px;">
                                            '.$messageText_1.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_2.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_3.'
                                        </p>
                                        <br>
                                    </section>
                                    <section>
                                        <br>
                                        <p style="font-size: 13px;">
                                            <b>Stay connected</b> <br>
                                            <b>Twitter / Facebook/Instagram:</b> RealSmartAfrica <br>
                                            <b>Connect with our official tag:</b> #TAS2018<br>
                                            <b>Website:</b> www.smartafrica.org<br>
                                        </p>
                                        <br>
                                    </section>
                                    <div style="font-size: 13px; padding: 0px; position: relative">
                                        <div style="text-align: left; border-top: 1px solid #ddd; padding: 10px 5px">
                                            Regards,<br><br>

                                            Transform Africa Summit Team<br>
                                            E: enquiries@smartafrica.org<br>
                                            T: +250 (0) 732301014<br>
                                            T: +250 (0) 732301013<br>
                                            T: +250 (0) 734016854<br>
                                            T: +250 (0) 734016853<br>
                                        </div>
                                        <br>
                                        <p style="font-size: 13px;">
                                            <a href="'.DN.'/tcs">Terms & Conditions</a> | 
                                            <a href="'.DN.'/privacy">Privacy Policy</a>
                                        </p>
                                    </div>
                                </div>
                            </body>
                        ';

                        $message_alt = $messageText_0.' '.$messageText_1.' '.$messageText_2.' '.$messageText_3;

                        $contactDetails['from_email'] = 'enquiries@smartafrica.org';
                        $contactDetails['from_names'] = 'Transform Africa Summit Team';
                        $contactDetails['to_email'] = $accommadation_data->email;

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);

                        

                        // Send EMAIL to smartcitiesftg@smartafrica.org//
                        
                        $subject= 'New Corporate account request';

                        $messageText_0= 'Dear Event Organizer team, <b>';

                        $messageText_1= 'We have a new group request for the '.$accommadation_data->hotel.': Transform Africa Summit. Below are the applicants details.';

                        $messageText_2= '<b>APPLICATION DETAILS</b>';

                        $messageText_3= 'Application date: '.Dates::get('d-m-Y',$seconds);

                        $messageText_4= '<b>PERSONAL DETAILS</b>';

                        $messageText_5= 'Title: '.$accommadation_data->title.'<br>Names: '.$accommadation_data->firstname.' '.$accommadation_data->lastname.'<br>
                                        Email: '.$accommadation_data->email.'<br> Telephone:'.$accommadation_data->telephone.'<br>';

                        $messageText_6= '<b>ORGANIZATION DETAILS</b>';

                        $messageText_7= '<b>Organisation name:</b> '.$accommadation_data->company_name.'<br>
                                        <b>Organisation category:</b> '.$accommadation_data->company_category.'<br>
                                        <b>Job title:</b> '.$accommadation_data->jobtitle.'<br>
                                        <b>No. of gold:</b> '.$accommadation_data->gold.'<br>
                                        <b>No. of platinum:</b> '.$accommadation_data->platinum.'<br>';

                        $message_body = '
                            <body>
                                <div style="padding: 10px; margin-left: 10px margin-right: 10px">

                                    <section>
                                        <p style="margin-bottom: 25px; font-size: 13px;">
                                            '.$messageText_0.'
                                        </p>
                                        <p style="font-size: 13px;">
                                            '.$messageText_1.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_2.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_3.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_4.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_5.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_6.'
                                        </p>
                                        <p style="font-size: 13px;">
                                             '.$messageText_7.'
                                        </p>
                                        <br>
                                    </section>
                                    <div style="font-size: 13px; padding: 0px; position: relative">
                                        <div style="text-align: left; border-top: 1px solid #ddd; padding: 10px 5px">
                                            Regards,<br><br>

                                            Transform Africa Summit Team<br>
                                            E: enquiries@smartafrica.org<br>
                                            T: +250 (0) 732301014<br>
                                            T: +250 (0) 732301013<br>
                                            T: +250 (0) 734016854<br>
                                            T: +250 (0) 734016853<br>
                                        </div>
                                    </div>
                                    <section>
                                        <br>
                                        <p style="font-size: 13px;">
                                            <b>Stay connected</b> <br>
                                            <b>Twitter / Facebook/Instagram:</b> RealSmartAfrica <br>
                                            <b>Connect with our official tag:</b> #TAS2018<br>
                                            <b>Website:</b> www.smartafrica.org<br>
                                        </p>
                                        <br>
                                        <p style="font-size: 13px;">
                                            <a href="'.DN.'/tcs">Terms & Conditions</a> | 
                                            <a href="'.DN.'/privacy">Privacy Policy</a>
                                        </p>
                                        <br>
                                    </section>
                                </div>
                            </body>
                        ';

                        $message_alt = $messageText_0.' '.$messageText_1.' '.$messageText_2;

                        $contactDetails['from_email'] = $accommadation_data->email;
                        $contactDetails['from_names'] = 'Transform Africa Summit Team';
                        $contactDetails['to_email'] = 'enquiries@smartafrica.org, abrahamahoshakiye@gmail.com';
                        // $contactDetails2['to_email'] = 'kadibra2@gmail.com';

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
                        $contactDetails['to_email'] = 'kadibra2@gmail.com';
                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
                        

                        // end EMAIL //

                        // REdirection To Next Page
                        // Redirect::to(DN.'/success/'.$participant_code.'/registered');
                        Redirect::to(DN.'/success/corporate');

                    }
                }catch(Exeption $e){
                    $diagnoArray[0] = "ERRORS_FOUND";
                    $diagnoArray[] = $e->getMessage();
                }
            }
        }else{
            return (object)[
                'ERRORS'=>true,
                'ERRORS_SCRIPT' => $validate->getErrorLocation()
            ];
        }
        
		if($diagnoArray[0] == 'ERRORS_FOUND'){
			return (object)[
				'ERRORS'=>true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation()
			];
		}else{
			return (object)[
				'ERRORS'=>false,
				'SUCCESS'=>true,
				'ERRORS_SCRIPT' => ""
			];
		}
	}
    
	public static function changeState($state,$participant_ID){
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
        
        $ID = $participant_ID;
        
        $seconds = \Config::get('time/seconds');
        
        $participantTable = new \FaceGorilla();
        global $session_user_data;
        global $participant_data;
        
        
        if($diagnoArray[0] == 'NO_ERRORS'){
            try{
                switch($state){
                    case 'Confirm';
                        $participantTable->update(array(
                            'state' => 'Confirmed',
                            'updated_date' => $seconds,
                            'user_ID' => $session_user_data->ID
                        ),$ID);
                        $fields['firstname'] = $participant_data->firstname;
                        $fields['lastname'] = $participant_data->lastname;
                        $fields['telephone'] = $participant_data->telephone;
                        $fields['email'] = $participant_data->email;
                        $fields['code'] = $participant_data->code;
                        $fields['photo'] = $participant_data->photo;
                        SubscriberController::addAccount($fields);
                    break;
                    case 'Pending';
                        $participantTable->update(array(
                            'state' => 'Pending',
                            'updated_date' => $seconds,
                            'user_ID' => $session_user_data->ID
                        ),$ID);
                    break;
                    case 'Deny';
                        $participantTable->update(array(
                            'state' => 'Denied',
                            'updated_date' => $seconds,
                            'user_ID' => $session_user_data->ID
                        ),$ID);
                    break;
                }
            }catch(Exeption $e){
                $diagnoArray[0] = "ERRORS_FOUND";
                $diagnoArray[] = $e->getMessage();
            }
        }
		if($diagnoArray[0] == 'ERRORS_FOUND'){
			return (object)[
				'ERRORS'=>true,
				'ERRORS_SCRIPT' => $diagnoArray
			];
		}else{
			return (object)[
				'ERRORS'=>false,
				'SUCCESS'=>true,
				'ERRORS_SCRIPT' => ""
			];
		}
	}
    
    
}