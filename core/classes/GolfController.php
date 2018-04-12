<?php
class GolfController  
{
    
	public static function add($form='Admin'){
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
        $error_msg = '';
        
        if($form=='Admin'){
            $prfx = 'participant-';
            foreach($_POST as $index=>$val){
                $ar = explode($prfx,$index);
                if(count($ar)){
                    $_SUBMIT[end($ar)] = $val;
                }
            }
        }elseif($form=='API'){
            $prfx = 'register-';
            foreach($_POST as $index=>$val){
                $ar = explode($prfx,$index);
                if(count($ar)){
                    $_SUBMIT[end($ar)] = $val;
                }
            }
        }
        
        $str = new \Str();
            
        $categ = firstUC(@$_GET['categ']);
        $category = $categ;
        $pass_type = $category;

        if ($category=='Golf') {
            $category='Golf';
        }else{
            $category = $categ;
        }
            

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
                    'othername' => array(
                        'name' => 'Other name',
                        'string' => true
                    ),
                    'gender' => array(
                        'name' => 'Gender',
                        'required' => true
                    ),
                    'email' => array(
                        'name' => 'Email',
                        'matches'=>'confirm_email',
                        'unique'=>'events_participant',
                        'required' => true
                    ),
                    'telephone' => array(
                        'name' => 'Telephone',
                        'required' => true
                    ),
                    'residence_country' => array(
                        'name' => 'Residence Country',
                        'required' => true
                    ),
                    'residence_city' => array(
                        'name' => 'Residence City',
                        'required' => true
                    ),
                    'citizenship_country' => array(
                        'name' => 'Country of Citizenship',
                        'required' => true
                    ),
                    'document_type' => array(
                        'name' => 'Document type',
                        'required' => true
                    ),
                    'document_number' => array(
                        'name' => 'Document number',
                        'unique'=>'events_participant',
                        'required' => true
                    ),
                    'category' => array(
                        'name' => 'Category',
                        'required' => true
                    ),
                    'pass_type' => array(
                        'name' => 'Pass type',
                        'required' => true
                    ),
                    'registration_type' => array(
                        'name' => 'Registration Type'
                    ),
                    'group_size' => array(
                        'name' => 'Group Size'
                    ),
                    'org_country' => array(
                        'name' => 'Country',
                        'string' => true,
                        'required' => true
                    ),
                    'org_city' => array(
                        'name' => 'City',
                        'string' => true,
                        'required' => true
                    ),
                    'company' => array(
                        'name' => 'Company name',
                        'string' => true,
                        'required' => true
                    ),
                    'jobtitle' => array(
                        'name' => 'Job title',
                        'alt' => 'jobtitle1',
                        'string' => true,
                        'required' => true
                    ),
                    'orgcategory' => array(
                        'name' => 'Organisation Category',
                        'alt' => 'orgcategory1',
                        'string' => true,
                        'required' => true
                    ),
                    'industry' => array(
                        'name' => 'Organization Industry',
                        'alt' => 'industry1',
                        'string' => true,
                        'required' => true
                    ),
                    'orgaddress' => array(
                        'name' => 'Organization Physical address',
                        'string' => true,
                        'required' => true
                    )
                );

        $validate_array = array_merge($validate_array_0,$validate_array_2);

        $validation = $validate->check($_SUBMIT, $validate_array);

        if($validation->passed()){

            $participantTable = new \Participant();

            $title = $str->sanAsName(@$_SUBMIT['title']);
            $firstname = $str->sanAsName(@$_SUBMIT['firstname']);
            $lastname = $str->sanAsName(@$_SUBMIT['lastname']);
            $othername = $str->sanAsName(@$_SUBMIT['othername']);
            
            $email= $str->data_in(@$_SUBMIT['email']);
            $telephone= $str->data_in(@$_SUBMIT['telephone']);
            $telephone_office= $str->data_in(@$_SUBMIT['telephone_office']);
            
            $gender = $str->data_in(@$_SUBMIT['gender']);
            
            $company_name = $str->data_in(@$_SUBMIT['company']);

            $company_category = $str->data_in(@$_SUBMIT['orgcategory']);
            if($company_category == 'Other'){
                $company_category = $str->data_in(@$_SUBMIT['orgcategory1']);
            }

            $company_industry = $str->data_in(@$_SUBMIT['industry']);
            if($company_industry == 'Other'){
                $company_industry = $str->data_in(@$_SUBMIT['industry1']);
            }
            
            $jobtitle = $str->data_in(@$_SUBMIT['jobtitle']);
            if($jobtitle == 'Other'){
                $jobtitle = $str->data_in(@$_SUBMIT['jobtitle1']);
            }

            $company_country = $str->data_in(@$_SUBMIT['org_country']);
            $company_city = $str->data_in(@$_SUBMIT['org_city']);
            $company_address = $str->data_in(@$_SUBMIT['orgaddress']);


            $website= $str->data_in(@$_SUBMIT['website']);
            $residence_country= trim($str->data_in(@$_SUBMIT['residence_country']));
            $residence_city= $str->data_in(@$_SUBMIT['residence_city']);
            $citizenship_country = $str->data_in(@$_SUBMIT['citizenship_country']);

            $document_type = $str->data_in(@$_SUBMIT['document_type']);
            $document_number= $str->data_in(@$_SUBMIT['document_number']); 

            $payment_state = 'Free';
            
            $company_ID = $str->data_in(@$_SUBMIT['company_id']);
            $event_ID = $str->data_in(@$_SUBMIT['event_id']);

            $seconds = \Config::get('time/seconds');
            $token_hash = md5($seconds.$event_ID);
            $participant_token = $validation->autoUniqueMaker('events_participant','token',$token_hash);

            if($diagnoArray[0] == 'NO_ERRORS'){

                try{
                    $participantTable->insert(array(
                        'company_ID' => $company_ID,
                        'event_ID' => $event_ID,

                        'title' => $title,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'othername' => $othername,
                        'gender' => $gender,

                        'company_name' => $company_name,
                        'company_category' => $company_category,
                        'company_industry' => $company_industry,
                        'company_address' => $company_address,
                        'company_country' => $company_country,
                        'company_city' => $company_city,

                        'jobtitle' => $jobtitle,
                        'telephone' => $telephone,
                        'telephone_office' => $telephone_office,
                        'email' => $email,

                        'residence_country' => $residence_country,
                        'residence_city' => $residence_city,
                        'citizenship_country' => $citizenship_country,
                        'document_type' => $document_type,
                        'document_number' => $document_number,

                        'category' => $category,
                        'pass_type' => $pass_type,

                        'payment_state' => $payment_state,
                        
                        'added_date' => Dates::get('d-m-Y',$seconds),
                        'added_date_to' => Dates::get('d-m-Y',$seconds),
                        'added_temp' => $seconds,
                        'token' => $participant_token,
                        
                        'State' => "Pending",
                        'form' => $form
                    ));

                    $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `state`=? AND `email`=? ORDER BY `ID` DESC LIMIT 1",array('Pending',$email));

                    if($participantTable->count() && $participantTable->first()->email == $email){
                        $participant_data = $participantTable->first();


                        $participant_ID = $participant_data->ID;

                        $cur_digit = strlen($participant_ID);
                        $total_digit = 6;
                        $code_string = $participant_ID;

                        if($cur_digit < $total_digit){
                            while(strlen($code_string) < $total_digit){
                                $code_string = '0'.$code_string;
                            }
                        }

                        $categ_code = "GLF";

                        $participant_code = 'TAS-'.$categ_code.'-'.$code_string;

                        $reference_number = $participant_code;
                        
                            // Uploading proposal
                            $dir = 'data_user/participants';
                            $fileName = $_FILES["register-photo"]["name"]; 
                            $fileTmpLoc = $_FILES["register-photo"]["tmp_name"]; 
                            $fileType = $_FILES["register-photo"]["type"]; 
                            $fileSize = $_FILES["register-photo"]["size"];
                            $fileErrorMsg = $_FILES["register-photo"]["error"]; 
                            $kaboom = explode(".", $fileName); 
                            $fileExt = strtolower(end($kaboom)); 
                            $newfilename = $participant_code.'.'.$fileExt; 
                            $upload_path = Config::get('url/bk_dir')."/$dir/$newfilename";
                            $file_path = "$dir/$newfilename";

                            if($fileName){
                                if(in_array($fileExt,array('pdf','png','jpg','jpeg'))){
                                    $moveResult = move_uploaded_file($fileTmpLoc, $upload_path);
                                    if($moveResult){
                                        $file_uploaded = true;
                                    }
                                }
                            }

                        // UPDATE reference numbers
                        
                        $photo_field = @$_SUBMIT['photo'];
                        $photo_db = '';

                        if($photo_field){
                            $photo_db = 'data_user/participants/'.$participant_code.'.jpg';
                            $photo_url = 'admin/data_user/participants/'.$participant_code.'.jpg';
                            if(is_file($photo_url) and !empty($photo_field)){
                                unlink($photo_url);
                            }

                            $tmp_array = explode('base64,',$photo_field);
                            $photo_field = $tmp_array[1];
                            $photo_field = base64_decode($photo_field);
                            @file_put_contents($photo_url, $photo_field);
                        }

                        $participantTable->update(array('code'=>$participant_code,'photo'=>$photo_db),$participant_ID); 
                        
                        // IF INVITATION //

                        global $_invitation_source;

                        if($_invitation_source){
                            global $_invitation_data;
                            
                            $invite_data = $_invitation_data['invite'];
                            $subscateg_data = $_invitation_data['subscateg'];
                            $subscriber_data = $_invitation_data['subscriber'];
                                
                            $participantTable->update(array('host_ID'=>$subscriber_data->group_ID,'host_account_ID'=>$invite_data->subscriber_ID,'invite_ID'=>$invite_data->ID),$participant_ID);
                            
                            // Keep log
                            $data_binded = array('invite_ID'=>$invite_data->ID,'participant_ID'=>$participant_ID);
                            SubscriberCategoryController::logAction('ticketUsed',$subscateg_data->ID,$data_binded);
                        }  

                        // END INVITATION //

                        $subject= 'Thank you for your application for a Golf tournament for Transform Africa Summit 2018';

                        $messageText_0= 'Dear <b>'.$participant_data->firstname.' '.$participant_data->lastname.' '.$participant_data->othername.'</b>,';

                        $messageText_1= 'Thank you for your registration request for Transform Africa Summit (Kigali, Rwanda, 8 - 11 May 2018)';

                        $messageText_2= 'We will review your registration and get back to you within 14 working days.';

                        // $messageText_2= 'Free exhibition passes are available to specific segments including students, academics, ICT related incubation hub tenants amongst others. <br>Passes are given at the organizers discretion and are dependent on availability.';

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
                                        <br>
                                        <p style="font-size: 13px;">
                                            <b>Stay connected</b> <br>
                                            <b>Twitter / Facebook:</b> TASummit <br>
                                            <b>Connect with our official tag:</b> #TAS2018<br>
                                            <b>Youtube:</b> TransformAfricaSummit<br>
                                        </p>
                                        <br>
                                    </section>
                                    <section>
                                        <p style="font-size: 13px;">
                                            <b>Accommodation:</b><br>
                                            <a href="'.DN.'/accomodation">Click here to submit your group accommodation requirements.</a>
                                            
                                        </p>
                                        <p style="font-size: 13px;">
                                            <b>Plan your trip:</b><br>
                                            <a href="'.DN.'/plan">Click here for travel information including visas requirements.</a>
                                        </p>
                                        <p style="font-size: 13px;">
                                            <b>Dedicated Bus Network:</b><br>
                                            To ease your travel around Kigali, you will receive an email at a later date with details 
                                            on the dedicated Transform Africa Summit bus network. You will be able to purchase pre-paid 
                                            travelcards giving you access to the entire Transform Africa Summit bus network during your 
                                            stay in Kigali.
                                        </p>
                                        <p style="font-size: 13px;">
                                            <b>Car hire:</b><br>
                                            Should you wish to hire a car during your stay here? Please email transport@smartafrica.org.
                                        </p>
                                        <br>
                                        <p style="font-size: 13px;">
                                            <b>Stay connected</b> <br>
                                            <b>Twitter / Facebook:</b> TASummit <br>
                                            <b>Connect with our official tag:</b> #TAS2018<br>
                                            <b>Youtube:</b> TransformAfricaSummit<br>
                                        </p>
                                        <br>
                                        <p style="font-size: 13px;">
                                            If you have received this email in error, please forward to it enquiries@smartafrica.org.
                                        </p>
                                        <br>
                                    </section>
                                    <div style="font-size: 13px; padding: 0px; position: relative">
                                        <div style="text-align: left; border-top: 1px solid #ddd; padding: 10px 5px">
                                            Regards,<br><br>

                                            Transform Africa Summit Team<br>
                                            Smart Africa Secretariat<br>
                                            E:  enquiries@smartafrica.org<br>
                                            T:  + 250 732 301011<br>
                                            + 250 732 301013<br>
                                            + 250 732 301014<br>
                                            <a href="'.DN.'/tcs">Terms & Conditions</a> | 
                                            <a href="'.DN.'/privacy">Privacy Policy</a>
                                        </div>
                                    </div>
                                </div>
                            </body>
                        ';

                        $message_alt = $messageText_0.' '.$messageText_1.' '.$messageText_2;

                        $contactDetails['from_email'] = 'tas2018@smartafrica.org';
                        $contactDetails['from_names'] = 'Smart Africa Team';
                        $contactDetails['to_email'] = $participant_data->email;

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);

                        // end EMAIL //

                        // REdirection To Next Page

                        Redirect::to(DN.'/success/'.$participant_code.'/registered');

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
    
    public static function participantConfirmedEmail($participant_data){
        $currency = $participant_data->residence_country == 'Rwanda' ? 'RWF' : 'USD';
        $subject= 'Your registration confirmation for Transform Africa Summit 2018';

        $messageText_0= 'Dear <b>'.$participant_data->firstname.' '.$participant_data->lastname.' '.$participant_data->othername.'</b>,';

        $messageText_1= 'Your registration for the Transform Africa Summit 2018 (Kigali, Rwanda, 8 - 11 May 2018) has been confirmed.';

        $messageText_2= 'Your confirmation details are:';
        
        $messageText_3='';
        $messageText_4='';
        $other_data_1 ='';
        
        if($participant_data->category=='Delegate'){
            
            // $messageText_3 = '<p style="font-size: 13px;">
            //                     <b>Setting up meetings at the Summit – Meet me at TAS</b><br>
            //                     From March 2018, you will be able to log-in to your profile and set-up meetings with other delegates as so will others be able to do the same to meet with you. Your company name will be displayed as well as your job title and so will that of other delegates so you can select who you would like to request a meeting with. You will be notified when the meeting requests start. 
            //                     If you wish to not participate in Meet me @ TAS, click here. 
            //                 </p>';
            $messageText_4 = '
                             Price: '.$currency.' '.$participant_data->amount.'
                            '.$other_data_1;
            if($participant_data->payment_state == 'Pending'){
                $other_data_1 = '<br>Payment link: <a href="'.DN.'/payment/'.$participant_data->code.'">[Click here to the payment form]</a>';
            }
            if($participant_data->payment_state == 'Confirmed'){
                $other_data_1 = '<br>Payment Receipt: <a href="'.DN.'/receipt-single/'.$participant_data->token.'"> [Click here to your Receipt]</a>';
            }
        }
        
        $message_body = '
            <body>
                <div style="padding: 10px; margin-left: 10px margin-right: 10px">

                    <section>
                        <p style="margin-bottom: 25px; font-size: 13px;">
                            '.$messageText_0.'
                        </p>
                        <p style="font-size: 13px;">
                            '.$messageText_1.'
                        </p style="font-size: 13px;">
                        <p style="font-size: 13px;">
                             '.$messageText_2.'
                        </p>
                        <p style="font-size: 13px;">
                            Registration ID: '.$participant_data->code.'<br>
                            Names: '.$participant_data->firstname.' '.$participant_data->lastname.' '.$participant_data->othername.'<br>
                            Pass Type: '.$participant_data->pass_type.'<br>
                            '.$messageText_4.'
                        </p>
                        <p style="font-size: 13px;">
                            <b>Accommodation:</b><br>
                            Click here to book your accommodation. You will need your registration ID. <a href="'.DN.'/accomodation">[Click here to  book your accommodation]</a>
                        </p>
                        <p style="font-size: 13px;">
                            <b>Plan your trip:</b><br>
                            Click here for travel information including visas requirements.
                        </p>
                        <p style="font-size: 13px;">
                            <b>Dedicated Bus Network:</b><br>
                            To ease your travel around Kigali, you will receive an email at a 
                            later date with details on the dedicated Transform Africa Summit 
                            bus network.  You will be able to purchase pre-paid travel cards 
                            giving you access to the entire Transform Africa Summit bus network 
                            during your stay in Kigali.
                        </p>
                        <p style="font-size: 13px;">
                            <b>Car hire:</b><br>
                            Should you wish to hire a car during your stay in Kigali, click here [Click here to plan your trip]
                        </p>
                        '.@$messageText_3.'
                        <p style="font-size: 13px;">
                            <b>Stay connected</b> <br>
                            <b>Twitter / Facebook:</b> TASummit <br>
                            <b>Connect with our official tag:</b> #TAS2018<br>
                            <b>Youtube:</b> TransformAfricaSummit<br>
                        </p>
                        <br>
                    </section>
                    <div style="font-size: 13px; padding: 0px; position: relative">
                        <div style="text-align: left; border-top: 1px solid #ddd; padding: 10px 5px">
                            Regards,<br><br>

                            Transform Africa Summit Team<br>
                            Smart Africa Secretariat<br>
                            E:	'.$participant_data->category.'@smartafrica.org<br>
                            T: 	+ 250 732 301011<br>
                            + 250 732 301013<br>
                            + 250 732 301014<br>
                            <a href="'.DN.'/tcs">Terms & Conditions</a> | 
                            <a href="'.DN.'/privacy">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </body>
        ';

        $message_alt = $messageText_0.' '.$messageText_1.' '.$messageText_2;

        $contactDetails['from_email'] = 'tas2018@smartafrica.org';
        $contactDetails['from_names'] = 'Smart Africa Team';
        $contactDetails['to_email'] = $participant_data->email;

        $contactDetails['attach'] = false;

        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
        return $email_status;
    }
}