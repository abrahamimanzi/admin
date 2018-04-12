
<?php

class EnquiriesController  
{
    
    public static function add($form='Admin'){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $error_msg = '';
        
        // if($form=='Admin'){
        //     $prfx = 'register-';
        //     foreach($_POST as $index=>$val){
        //         $ar = explode($prfx,$index);
        //         if(count($ar)){
        //             $_SUBMIT[end($ar)] = $val;
        //         }
        //     }
        // }elseif($form=='API'){
        //     $prfx = 'register-';
        //     foreach($_POST as $index=>$val){
        //         $ar = explode($prfx,$index);
        //         if(count($ar)){
        //             $_SUBMIT[end($ar)] = $val;
        //         }
        //     }
        // }

            $prfx = 'register-';
            foreach($_POST as $index=>$val){
                $ar = explode($prfx,$index);
                if(count($ar)){
                    $_SUBMIT[end($ar)] = $val;
                }
            }

        $str = new \Str();

        // $categ = firstUC(@$_GET['categ']);
        // $category = $categ;
        // $pass_type = $category;

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
                    'email' => array(
                        'name' => 'Email',
                        'matches'=>'confirm_email',
                        'required' => true
                    ),
                    'telephone' => array(
                        'name' => 'Telephone',
                        'required' => true
                    ),
                    'registration_type' => array(
                        'name' => 'Registration Type'
                    )
                );

        $validate_array = array_merge($validate_array_0,$validate_array_2);

        $validation = $validate->check($_SUBMIT, $validate_array);

        if($validation->passed()){

            $missingTable = new \Missing();

            $title = $str->sanAsName(@$_SUBMIT['title']);
            $firstname = $str->sanAsName(@$_SUBMIT['firstname']);
            $lastname = $str->sanAsName(@$_SUBMIT['lastname']);
            $othername = $str->sanAsName(@$_SUBMIT['othername']);
            
            $email= $str->data_in(@$_SUBMIT['email']);
            $telephone= $str->data_in(@$_SUBMIT['telephone']);
            $telephone_office= $str->data_in(@$_SUBMIT['telephone_office']);
            
            
            $action = $str->data_in(@$_SUBMIT['action']);
            $info = $str->data_in(@$_SUBMIT['info']);
            
            $company_ID = $str->data_in(@$_SUBMIT['company_id']);
            $event_ID = $str->data_in(@$_SUBMIT['event_id']);

            $seconds = \Config::get('time/seconds');

            if($diagnoArray[0] == 'NO_ERRORS'){

                try{
                    $missingTable->insert(array(
                        'company_ID' => $company_ID,
                        'event_ID' => $event_ID,

                        'title' => $title,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'othername' => $othername,

                        'telephone' => $telephone,
                        'telephone_office' => $telephone_office,
                        'email' => $email,

                        'action' => $action,
                        'info' => $info,
                        
                        'added_date' => Dates::get('d-m-Y',$seconds),
                        'added_temp' => $seconds,
                        
                        'State' => "Pending"
                    ));


                    $missingTable->selectQuery("SELECT * FROM `missing` WHERE `state`=? AND `email`=? ORDER BY `ID` DESC LIMIT 1",array('Pending',$email));

                    if($missingTable->count() && $missingTable->first()->email == $email){
                        $missing_data = $missingTable->first();

                        $missing_ID = $missing_data->ID;
                        $missing_code = $missing_data->ID;

                        // END INVITATION //
                        

                        $subject= 'Thank you for reporting missing property in Transform Africa Summit 2018';

                        $messageText_0= 'Dear <b>'.$missing_data->firstname.' '.$missing_data->lastname.' '.$missing_data->othername.'</b>,';

                        $messageText_1= 'Thank you for reporting missing property in Transform Africa Summit (Kigali, Rwanda, 8 - 11 May 2018)';

                        $messageText_2= 'We will get back to you shortly.';

                        $messageText_3= 'For urgent matters, directly email transformafricasummit@smartafrica.org';

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
                        $contactDetails['to_email'] = $missing_data->email;

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
                                
                        // end EMAIL //

                        $subject= 'Some one intersted in TAS2018';

                        $messageText_0= 'Dear Smartafrica Team,';

                        $messageText_1= '<b>'.$missing_data->firstname.' '.$missing_data->lastname.' '.$missing_data->othername.'</b> is intersted in Transform Africa Summit (Kigali, Rwanda, 9 - 11 May 2018)';

                        $messageText_2= 'Below more details';

                        $messageText_3= '<b>Telephone: </b>'.$missing_data->telephone.'';

                        $messageText_4= '<b>Telephone Office: </b>'.$missing_data->telephone_office.'';

                        $messageText_5= '<b>Email: </b>'.$missing_data->email.'';

                        $messageText_6= '<b>Intersted in: </b>'.$missing_data->action.'';

                        $messageText_7= '<b>Request: </b>'.$missing_data->info.'';

                        $messageText_8= 'For urgent matters, directly email transformafricasummit@smartafrica.org';

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
                                        <p style="font-size: 13px;">
                                             '.$messageText_8.'
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

                        // $contactDetails['from_email'] = 'tas2017@smartafrica.org';
                        $contactDetails['from_email'] = 'abrahamahoshakiye@gmail.com';
                        $contactDetails['from_names'] = 'Smart Africa Team';
                        $contactDetails['to_email'] = $missing_data->email;

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
                                
                        // end EMAIL //
                        
                        // REdirection To Next Page

                        // Redirect::to(DN.'/404');

                        Redirect::to(DN.'/enquiries');

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
        
        $participantTable = new \Participant();
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
                            To ease your travel around Kigali, you will receive an email at a later date with details on the dedicated Transform Africa Summit bus network.  
                            You will be able to purchase pre-paid travel cards giving you access to the entire Transform Africa Summit bus network during your stay in Kigali.
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
        return $email_status;
    }
}














