<?php
class FaceGorillaController  
{
    public static function add($form='Admin'){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $error_msg = '';
        
        if($form=='Admin'){
            $prfx = 'register-';
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

        if ($category=='ftg' || $category=='Ftg') {
            $category='Face-the-Gorillas-Applicant';
            $pass_type='No pass';
        }else{
            $category = $categ;
            $pass_type='No pass';
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
                    'company_name' => array(
                        'name' => 'Company Name',
                        'required' => true
                    ),
                    'company_regname' => array(
                        'name' => 'Registered Name',
                        'string' => true,
                        'required' => true
                    ),
                    'company_number' => array(
                        'name' => 'Registered Number',
                        'required' => true
                    ),
                    'org_country' => array(
                        'name' => 'Country',
                        'string' => true,
                        'required' => true
                    ),
                );

        $validate_array = array_merge($validate_array_0,$validate_array_2);

        $validation = $validate->check($_SUBMIT, $validate_array);

        if($validation->passed()){

            $participantTable = new \Participant();
            $faceGorillaTable = new \FaceGorilla();


            $firstname = $str->sanAsName(@$_SUBMIT['firstname']);
            $lastname = $str->sanAsName(@$_SUBMIT['lastname']);
            $othername = $str->sanAsName(@$_SUBMIT['othername']);
            $telephone= $str->data_in(@$_SUBMIT['telephone']);

            $email= $str->data_in(@$_SUBMIT['email']);

            $gender = $str->data_in(@$_SUBMIT['gender']);

            // COMPANY

            $company_name = $str->data_in(@$_SUBMIT['company_name']);
            $company_regname = $str->data_in(@$_SUBMIT['company_regname']);
            $company_number = $str->data_in(@$_SUBMIT['company_number']);
            $company_country = $str->data_in(@$_SUBMIT['org_country']);

            $website= $str->data_in(@$_SUBMIT['website']);
            $residence_country= trim($str->data_in(@$_SUBMIT['residence_country']));
            $residence_city= $str->data_in(@$_SUBMIT['residence_city']);
            $citizenship_country = $str->data_in(@$_SUBMIT['citizenship_country']);

            $document_type = $str->data_in(@$_SUBMIT['document_type']);
            $document_number= $str->data_in(@$_SUBMIT['document_number']); 
            
            $legal_structure = $str->data_in(@$_SUBMIT['legal_structure']);
            if($legal_structure == 'Other'){
                $legal_structure = $str->data_in(@$_SUBMIT['legal_structure1']);
            }

            $date_founded = $str->data_in(@$_SUBMIT['date_founded']);
            $employee_number = $str->data_in(@$_SUBMIT['employee_number']);
            $org_stage = $str->data_in(@$_SUBMIT['org_stage']);
            $funding_state = $str->data_in(@$_SUBMIT['funding_state']);

            $investname = $str->data_in(@$_SUBMIT['investname']);
            $investlocation = $str->data_in(@$_SUBMIT['investlocation']);

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

                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'othername' => $othername,
                        'gender' => $gender,

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

                        $categ_code = "FTG";

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

                        // END INVITATION //
                        
                        $subject= 'Thank you for your application submission for Face the Gorillas - Smart Cities Edition';

                        $messageText_0= 'Dear <b>'.$participant_data->firstname.' '.$participant_data->lastname.'</b>,';

                        $messageText_1= 'Thank you for your application request for Face the Gorillas - Smart Cities Edition (Kigali, Rwanda, 8 - 11 May 2018)';

                        $messageText_2= 'We will review your application and get back to you shortly';

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


                        // Send EMAIL to smartcitiesftg@smartafrica.org//
                        
                        $subject= 'New FTG Applicant';

                        $messageText_0= 'Dear FTG team <b>';

                        $messageText_1= 'We have a new application for the Face the Gorilla: Smartcities edition. Below are the applicants details.';

                        $messageText_2= '<b>APPLICATION DETAILS</b>';

                        $messageText_3= 'Application date: '.Dates::get('d-m-Y',$seconds);

                        $messageText_4= '<b>PERSONAL DETAILS</b>';

                        $messageText_5= 'Registration ID: '.$participant_code.'<br>Names: '.$participant_data->firstname.' '.$participant_data->lastname.'<br>
                                        ID number/passport: '.$participant_data->document_number.'<br>
                                        Email: '.$participant_data->email.'<br>Telephone: '.$participant_data->telephone.'<br>
                                        Country of Residence: '.$participant_data->residence_country.'<br>';

                        $messageText_6= '<b>COMPANY DETAILS</b>';

                        $messageText_7= 'Company name: '.$participant_data->company_name.'<br>Company impact: '.$business_impact.'<br>
                                        Country: '.$participant_data->company_country.'<br>
                                        Year founded: '.$date_founded.'<br>
                                        Product / service: '.$business_products.'<br>
                                        Website: '.$participant_data->website.'<br>Who referred you to Smart Africa: '
                                        .$tas_referrence.'<br>';

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

                        $contactDetails['from_email'] = $participant_data->email;
                        $contactDetails['from_names'] = 'Smart Africa Team';
                        $contactDetails['to_email'] = 'enquiries@smartafrica.org';
                        //$contactDetails['to_email'] = 'kadibra2@gmail.com';

                        $contactDetails['attach'] = false;

                        $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);
                        $contactDetails['to_email'] = 'kadibra2@gmail.com';
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

        $messageText_1= 'Your registration for the Transform Africa Summit 2018 (Kigali, Rwanda, 10 - 12 May 2018) has been confirmed.';

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
                            To ease your travel around Kigali, you will receive an email at a later date with details on the dedicated Transform Africa Summit bus network.  You will be able to purchase pre-paid travel cards giving you access to the entire Transform Africa Summit bus network during your stay in Kigali.
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