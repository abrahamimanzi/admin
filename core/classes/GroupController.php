<?php
class GroupController  
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

        $categ = firstUC(@$_GET['categ']);
        $category = $categ;
        $pass_type = $category;

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
                    'telephone' => array(
                        'name' => 'Telephone',
                        'required' => true
                    ),
                    'email' => array(
                        'name' => 'Email',
                        'matches'=>'confirm_email',
                        // 'unique'=>'events_participant',
                        'required' => true
                    ),
                    'gender' => array(
                        'name' => 'Gender',
                        'required' => true
                   ),
                    'citizenship_country' => array(
                        'name' => 'Country of Citizenship',
                        'required' => true
                    ),
                    'group_size' => array(
                        'name' => 'Number of people',
                        'required' => true
                    ),
                    'company_name' => array(
                        'name' => 'Company Name',
                        'required' => true
                    ),
                    'jobtitle' => array(
                        'name' => 'Job title',
                        'alt' => 'jobtitle1',
                        'string' => true,
                        'required' => true
                    ),
                    'pass_type' => array(
                        'name' => 'Pass type',
                        'required' => true
                    ),
                    'payment_method' => array(
                        'name' => 'Payment method',
                        'required' => true
                    )
                );

        $validate_array = array_merge($validate_array_0,$validate_array_2);

        $validation = $validate->check($_SUBMIT, $validate_array);

        if($validation->passed()){

            // $participantTable = new \Participant();
            $groupsTable = new \Group();
            // $faceGorillaTable = new \FaceGorilla();


            $title = $str->sanAsName(@$_SUBMIT['title']);
            $firstname = $str->sanAsName(@$_SUBMIT['firstname']);
            $lastname = $str->sanAsName(@$_SUBMIT['lastname']);
//            $group-size = $str->data_in(@$_SUBMIT['group-size']);

            $email= $str->data_in(@$_SUBMIT['email']);


            // COMPANY

            $telephone= $str->data_in(@$_SUBMIT['telephone']);
            
            $gender = $str->data_in(@$_SUBMIT['gender']);
            
            $company_name = $str->data_in(@$_SUBMIT['company_name']);
            
            $jobtitle = $str->data_in(@$_SUBMIT['jobtitle']);
            if($jobtitle == 'Other'){
                $jobtitle = $str->data_in(@$_SUBMIT['jobtitle1']);
            }


            $website= $str->data_in(@$_SUBMIT['website']);
            $citizenship_country = $str->data_in(@$_SUBMIT['citizenship_country']);

            $group_size = $str->data_in(@$_SUBMIT['group_size']);
            $pass_type = $str->data_in(@$_SUBMIT['package_type']);

            $payment_method = $str->data_in(@$_SUBMIT['payment_method']);

            $amount = $residence_country == 'Rwanda' ? Functions::passPrice($pass_type,'Local') : Functions::passPrice($pass_type,'Other'); 

            $payment_state = 'Pending';
            
            $group_pass_array = @$_SUBMIT['group_pass']; //ARRAY
            $group_pass = '';
            if(count($group_pass_array)){
                $group_pass = json_encode($group_pass_array);
            }


            $event_ID = 6;

            $seconds = \Config::get('time/seconds');
            $token_hash = md5($seconds.$event_ID);
            $participant_token = $validation->autoUniqueMaker('events_participant','token',$token_hash);

            /*
            $business_model = '';
            $business_model_array = @$_SUBMIT['business_model'];
            if(count($business_model_array)){
                $business_model = json_encode($business_model_array);
            }

            $business_impact = $str->data_in(@$_SUBMIT['business_impact']);

            $business_sectors_array = @$_SUBMIT['business_sectors']; //ARRAY
            $business_sectors = '';
            if(count($business_sectors_array)){
                $business_sectors = json_encode($business_sectors_array);
            }

            $business_tweet = $str->data_in(@$_SUBMIT['business_tweet']);
            $business_products = $str->data_in(@$_SUBMIT['business_products']);

            $target_markets = '';
            $target_markets_array = @$_SUBMIT['target_markets'];
            if(count($target_markets_array)){
                $target_markets = json_encode($target_markets_array);
            }

            $business_client = $str->data_in(@$_SUBMIT['business_client']);

            $business_textmodel = $str->data_in(@$_SUBMIT['business_textmodel']);

            $market_opps = $str->data_in(@$_SUBMIT['market_opps']);
            $business_advantange = $str->data_in(@$_SUBMIT['business_advantange']);
            $business_strategy = $str->data_in(@$_SUBMIT['business_strategy']);
            $previous_award = $str->data_in(@$_SUBMIT['previous_award']);
            $previous_incubator = $str->data_in(@$_SUBMIT['previous_incubator']);

            $request_availability = '';
            $request_availability_array = @$_SUBMIT['request_availability'];
            if(count($request_availability_array)){
                $request_availability = json_encode($request_availability_array);
            }

            $team_experience = $str->data_in(@$_SUBMIT['team_experience']);
            $other_business = $str->data_in(@$_SUBMIT['other_business']);
            $other_investamount  = $str->data_in(@$_SUBMIT['other_investamount']);
            $advisor_experience  = $str->data_in(@$_SUBMIT['advisor_experience']);
            $business_currenttax  = $str->data_in(@$_SUBMIT['business_currenttax']);
            $revenue_build  = $str->data_in(@$_SUBMIT['revenue_build']);
            $business_breakeven  = $str->data_in(@$_SUBMIT['business_breakeven']);
            $business_valuation  = $str->data_in(@$_SUBMIT['business_valuation']);

            $valuation_method  = $str->data_in(@$_SUBMIT['valuation_method']);
            $current_netburn  = $str->data_in(@$_SUBMIT['current_netburn']);
            $previous_netburn  = $str->data_in(@$_SUBMIT['previous_netburn']);
            $capital_require  = $str->data_in(@$_SUBMIT['capital_require']);
            $external_investment  = $str->data_in(@$_SUBMIT['external_investment']);
            $existing_investors  = $str->data_in(@$_SUBMIT['existing_investors']);
            $existing_strategy  = $str->data_in(@$_SUBMIT['existing_strategy']);
            $tas_referrence  = $str->data_in(@$_SUBMIT['tas_referrence']);

            $company_ID = $str->data_in(@$_SUBMIT['company_id']);
            $event_ID = $str->data_in(@$_SUBMIT['event_id']);


            $payment_state = 'Free';
            */

            $seconds = \Config::get('time/seconds');
            // $token_hash = md5($seconds.$event_ID);
            // $participant_token = $validation->autoUniqueMaker('events_participant','token',$token_hash);

            if($diagnoArray[0] == 'NO_ERRORS'){

                try{
                    $groupsTable->insert(array(
                        'title' => $title, 
                        'firstname' => $firstname, 
                        'lastname' => $lastname, 
                        'telephone' => $telephone,
                        'email' => $email,
                        'gender' => $gender,
                        
                        'company_name' => $company_name,
                        'website' => $website,
                        // 'company_address' => $company_address,
                        
                        'citizenship_country' => $citizenship_country,
                        'jobtitle' => $jobtitle,
                        
                        'group_size' => $group_size,
                        'group_pass' => $group_pass,
                        'pass_type' => $pass_type,
                        'payment_method' => $payment_method,
                        'amount' => $amount,
                        'payment_state' => $payment_state,
                        
                        'added_date' => Dates::get('d-m-Y',$seconds),
                        'token' => $participant_token,
                        'form' => $form
                    ));

                    /*
                    $participantTable->insert(array(
                        'company_ID' => $company_ID,
                        'event_ID' => $event_ID,

                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'othername' => $othername,
                        'gender' => $gender,

                        'dateOfbirth' => $dateOfbirth,
                        'telephone' => $telephone,
                        'email' => $email,
                        
                        'residence_country' => $residence_country,
                        'residence_city' => $residence_city,
                        'citizenship_country' => $citizenship_country,
                        'document_type' => $document_type,
                        'document_number' => $document_number,

                        'company_name' => $company_name,
                        'website' => $website,
                        'company_country' => $company_country,

                        'category' => $category,
                        'pass_type' => $pass_type,

                        'payment_state' => $payment_state,

                        'added_date' => Dates::get('d-m-Y',$seconds),
                        'added_temp' => $seconds,
                        'token' => $participant_token,

                        'State' => "Pending",
                        'form' => $form
                    ));
                    */
                    // $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `state`=? AND `email`=? ORDER BY `ID` DESC LIMIT 1",array('Pending',$email));
                    $groupsTable->selectQuery("SELECT * FROM `groups` WHERE `email`=? ORDER BY `ID` DESC LIMIT 1",array($email));

                    // if($participantTable->count() && $participantTable->first()->email == $email){
                    if($groupsTable->count() && $groupsTable->first()->email == $email){
                        $groups_data = $groupsTable->first();
                        // $participant_data = $participantTable->first();


                        // $participant_ID = $participant_data->ID;
                        $groups_ID = $groups_data->ID;

                        /*
                        $cur_digit = strlen($participant_ID);
                        $total_digit = 6;
                        $code_string = $participant_ID;

                        if($cur_digit < $total_digit){
                            while(strlen($code_string) < $total_digit){
                                $code_string = '0'.$code_string;
                            }
                        }

                        $categ_code = "MSGSC";

                        $participant_code = 'TAS-'.$categ_code.'-'.$code_string;

                        $reference_number = $participant_code;

                        $participantTable->update(array('code'=>$participant_code),$participant_ID); 

                        $faceGorillaTable->insert(array(

                            'ID' => $participant_ID,

                            'company_regname' => $company_regname,
                            'company_number' => $company_number,
                            'legal_structure' => $legal_structure,

                            'date_founded' => $date_founded,
                            'employee_number' => $employee_number,
                            'org_stage' => $org_stage,
                            'funding_state' => $funding_state,

                            'investname' => $investname,
                            'investlocation' => $investlocation,
                            'business_model' => $business_model,
                            'business_impact' => $business_impact,
                            'business_sectors' => $business_sectors,
                            'business_tweet' => $business_tweet,
                            'business_products' => $business_products,
                            'target_markets' => $target_markets,

                            'business_client' => $business_client,
                            'business_textmodel' => $business_textmodel,
                            'market_opps' => $market_opps,
                            'business_advantange' => $business_advantange,
                            'business_strategy' => $business_strategy,
                            'previous_award' => $previous_award,
                            'previous_incubator' => $previous_incubator,
                            'request_availability' => $request_availability,
                            'team_experience' => $team_experience,
                            'other_business' => $other_business,
                            'other_investamount' => $other_investamount,
                            'advisor_experience' => $advisor_experience,
                            'business_currenttax' => $business_currenttax,
                            'revenue_build' => $revenue_build,
                            'business_breakeven' => $business_breakeven,
                            'business_valuation' => $business_valuation,
                            'valuation_method' => $valuation_method,
                            'current_netburn' => $current_netburn,
                            'previous_netburn' => $previous_netburn,
                            'capital_require' => $capital_require,
                            'external_investment' => $external_investment,
                            'existing_investors' => $existing_investors,
                            'existing_strategy' => $existing_strategy,
                            'tas_referrence' => $tas_referrence,

                            'added_temp' => $seconds
                        ));
                        
                        // IF INVITATION //

                        global $_invitation_source;

                        if($_invitation_source){
                            global $_invitation_data;
                            
                            $invite_data = $_invitation_data['invite'];
                            $subscateg_data = $_invitation_data['subscateg'];
                            $subscriber_data = $_invitation_data['subscriber'];
                                
                            $participantTable->update(array('host_ID'=>$subscriber_data->group_ID,'host_account_ID'=>$invite_data->subscriber_ID,'invite_ID'=>$invite_data->ID),$participant_ID);
                            
                            if($subscateg_data->plan=='Free'){
                                $participantTable->update(array('payment_state'=>'Free'),$participant_ID);
                            }
                            // Keep log
                            $data_binded = array('invite_ID'=>$invite_data->ID,'participant_ID'=>$participant_ID);
                            SubscriberCategoryController::logAction('ticketUsed',$subscateg_data->ID,$data_binded);
                        }  
                        
                        */

                        // END INVITATION //
                        
                        $subject= 'Group registration request for Transform Africa Summit 2018';

                        $messageText_0= 'Dear <b>'.$groups_data->firstname.' '.$groups_data->lastname.'</b>,';

                        $messageText_1= 'Thank you for submitting your request.';

                        $messageText_2= 'Our registration team will be in touch with you shortly';

                        $messageText_3= 'Kindly contact us using the details below if you have any further queries.';

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

                                            Transform Africa Team<br>
                                            E: invitations@smartafrica.org<br>
                                            T: +250 732 105 105<br>
                                            T: +250 732 106 106<br>
                                            T: +250 732 107 107<br>
                                        </div>
                                    </div>
                                    <section>
                                        <br>
                                        <p style="font-size: 13px;">
                                            <b>Stay connected</b> <br>
                                            <b>Twitter / Facebook/Instagram:</b> TASummit <br>
                                            <b>Connect with our official tag:</b> #TAS2018<br>
                                            <b>Youtube:</b> TransformAfricaSummit<br>
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

                        $contactDetails['from_email'] = 'invitations@smartafrica.org';
                        $contactDetails['from_names'] = 'Smart Africa Team';
                        $contactDetails['to_email'] = $groups_data->email;
                        // $contactDetails['to_email'] = 'abraham@cube.rw';

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);

                        

                        // Send EMAIL to smartcitiesftg@smartafrica.org//
                        
                        $subject= 'New Group Registration request';

                        $messageText_0= 'Dear regsiteration team, <b>';

                        $messageText_1= 'We have a new group booking application for the '.$groups_data->hotel.': Transform Africa. Below are the applicants details.';

                        $messageText_2= '<b>APPLICATION DETAILS</b>';

                        $messageText_3= 'Application date: '.Dates::get('d-m-Y',$seconds);

                        // $messageText_4= '<b>PERSONAL DETAILS</b>';

                        $messageText_5= 'Names: '.$groups_data->firstname.' '.$groups_data->lastname.'<br>
                                        Email: '.$groups_data->email.'<br>
                                        Job title: '.$groups_data->jobtitle.'<br>
                                        Country residence: '.$groups_data->citizenship_country.'<br>
                                        Organization name: '.$groups_data->company_name.'<br>
                                        Number of people in a group: '.$groups_data->group_size.'<br>
                                        Passes required: '.$groups_data->group_pass.'<br>
                                        Payment method: '.$groups_data->payment_method.'<br>';

                      

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
                                             '.$messageText_5.'
                                        </p>
                                        <br>
                                    </section>
                                    <div style="font-size: 13px; padding: 0px; position: relative">
                                        <div style="text-align: left; border-top: 1px solid #ddd; padding: 10px 5px">
                                            Regards,<br><br>

                                            Transform Africa Team<br>
                                            E: invitations@smartafrica.org<br>
                                            T: +250 732 105 105<br>
                                            T: +250 732 106 106<br>
                                            T: +250 732 107 107<br>
                                        </div>
                                    </div>
                                    <section>
                                        <br>
                                        <p style="font-size: 13px;">
                                            <b>Stay connected</b> <br>
                                            <b>Twitter / Facebook/Instagram:</b> TASummit <br>
                                            <b>Connect with our official tag:</b> #TAS2018<br>
                                            <b>Youtube:</b> TransformAfricaSummit<br>
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

                        $contactDetails['from_email'] = $groups_data->email;
                        $contactDetails['from_names'] = 'Transform Africa Team';
                        $contactDetails['to_email'] = 'invitations@smartafrica.org, enquiries@smartafrica.org';
                        // $contactDetails['to_email'] = 'abrahamahoshakiye@gmail.com';

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
                        

                        // end EMAIL //

                        // REdirection To Next Page
                        // Redirect::to(DN.'/success/'.$participant_code.'/registered');
                        // Redirect::to(DN.'/success/group');
                        Redirect::to(DN.'/grouppayment/'.$groups_ID);

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
                            <b>Accommodation</b><br>
                            <a href="'.DN.'/accomodation">Click here</a> to view accommodation options.

                        </p>
                        <p style="font-size: 13px;">
                            <b>Plan Your Trip</b><br>
                            View visa &amp; other immigration requirements <a href="https://irembo.gov.rw/rolportal/web/rol/conference-visa-single-entry#_48_INSTANCE_I0QYdGCQWtDM_%2525253Dhttps%252525253A%252525252F%252525252Firembo.gov.rw%252525252FrolDGIE%252525252Fvisa_v1%252525252FVisa_v1.xhtml%252525253Fvspte%252525253DT6-1%2525252526%2525252526lang%252525253Den%25253D%252526_48_INSTANCE_I0QYdGCQWtDM_%25253Dhttps%2525253A%2525252F%2525252Firembo.gov.rw%2525252FrolDGIE%2525252Fvisa_v1%2525252FVisa_v1.xhtml%2525253Fvspte%2525253DT6-1%25252526%25252526lang%2525253Den%253D%2526_48_INSTANCE_I0QYdGCQWtDM_%253Dhttps%25253A%25252F%25252Firembo.gov.rw%25252FrolDGIE%25252Fvisa_v1%25252FVisa_v1.xhtml%25253Fvspte%25253DT6-1%252526%252526lang%25253Den%3D%26_48_INSTANCE_I0QYdGCQWtDM_%3Dhttps%253A%252F%252Firembo.gov.rw%252FrolDGIE%252Fvisa_v1%252FVisa_v1.xhtml%253Fvspte%253DT6-1%2526%2526lang%253Den">here.</a>
                            
                        </p>
                        <p style="font-size: 13px;">
                            <b>Discover Remarkable Rwanda</b><br>
                            Rwanda offers activities to suit many interests. <a href="'.DN.'/discover">Click here</a> to discover what you can do in and around
                            Kigali during the week of the summit.

                        </p>
                        <p style="font-size: 13px;">
                            See you soon in Kigali!
                        </p>
                        <br>
                    </section>
                    <div style="font-size: 13px; padding: 0px; position: relative">
                        <div style="text-align: left; border-top: 1px solid #ddd; padding: 10px 5px">
                            Regards,<br><br>

                            Transform Africa Team<br>
                            E: invitations@smartafrica.org<br>
                            T: +250 732 105 105<br>
                            T: +250 732 106 106<br>
                            T: +250 732 107 107<br>
                        </div>
                    </div>
                    <section>
                        <br>
                        <p style="font-size: 13px;">
                            <b>Stay connected</b> <br>
                            <b>Twitter / Facebook/Instagram:</b> TASummit <br>
                            <b>Connect with our official tag:</b> #TAS2018<br>
                            <b>Youtube:</b> TransformAfricaSummit<br>
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

        $contactDetails['from_email'] = 'invitations@smartafrica.org';
        $contactDetails['from_names'] = 'Transform Africa Team';
        $contactDetails['to_email'] = $participant_data->email;

        $contactDetails['attach'] = false;

        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
        return $email_status;
    }
    
}