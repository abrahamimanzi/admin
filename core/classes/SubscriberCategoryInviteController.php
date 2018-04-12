<?php
class SubscriberCategoryInviteController  
{
    
    public static function addInvite(){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $prfx = 'invite-';
        foreach($_POST as $index=>$val){
            $ar = explode($prfx,$index);
            if(count($ar)){
                $_SIGNUP[end($ar)] = $val;
            }
        }
        
        global $session_subscriber_data;
        $session_subscriber_ID = $session_subscriber_data->ID;
        
        $validation = $validate->check($_SIGNUP, array(
            'firstname' => array(
                'name' => 'First Name',
                'string' => true,
                'required' => true
            ),
            'lastname' => array(
                'name' => 'Last Names',
                'string' => true
            ),
            'email' => array(
                'name' => 'Email Address',
                'email' => true,
                'unique' => "subscriber_category_invite|events_participant",
                'required' => true
            )
        ));
        
        if($validation->passed()){
            
            $subscriberTable = new \Subscriber();
            
            $str = new \Str();
            
            $_SIGNUP = (object)$_SIGNUP;
            
            $firstname = $str->sanAsName($_SIGNUP->firstname);
            
            $lastname = $str->sanAsName($_SIGNUP->lastname);
            
            $email = $str->data_in($_SIGNUP->email);
            
            $invite_ID = $str->data_in($_SIGNUP->id);
                
            $salt = Hash::salt(32);
            $rand_string = $email.rand(99,9999999999).$firstname;
            $shared_string = Hash::make($rand_string,$salt);

            $hash_string = SubscriberCategoryInvite::getInviteHash($invite_ID,$session_subscriber_ID,$shared_string);

            $subscategInviteTable = new \SubscriberCategoryInvite();
            $subscategInviteTable->selectQuery("SELECT* FROM `subscriber_category_invite` WHERE `subscriber_ID`='{$session_subscriber_ID}' AND `ID`=?",array($invite_ID));

            if(!$subscategInviteTable->count()){
                $diagnoArray[0] == 'ERRORS_FOUND';
            }else{
                if($invite_data->ID!=$invite_ID){
                    $diagnoArray[0] == 'ERRORS_FOUND';
                }else{
                    $invite_data = $subscategInviteTable->first();
                }
            }
            
            $seconds = \Config::get('time/seconds');
            $token = md5($invite_ID);
            
            if($diagnoArray[0] == 'NO_ERRORS'){
                try{
                    $subscategInviteTable->update(array(
                        'firstname'=>$firstname,
                        'lastname'=>$lastname,
                        'email'=>$email,
                        'status'=>'Invited',
                        'hash'=>$hash_string,
                        'shared_string'=>$shared_string,
                        'token'=>$token
                    ),$invite_ID);
                }catch(Exeption $e){
                    $diagnoArray[0] = "ERRORS_FOUND";
                    $diagnoArray[] = $e->getMessage();
                }
                
                $invite_url = DN.'/register/'.$token.'/'.$shared_string;
                 
                $subject= 'Your Transform Africa Summit 2018 Registration invite from '.$session_subscriber_data->company_name;

                $messageText_0= 'Dear <b>'.$firstname.' '.$lastname.'</b>,';

                $messageText_1= 'You have been invited to register for the Transform Africa Summit 2018 in Kigali by:';

                $messageText_2= 'Group Name: '.$session_subscriber_data->company_name.'<br>
                                Group Contact email: '.$session_subscriber_data->email.'<br>
                                ';
                // $messageText_2= 'Group Name: '.$session_subscriber_data->company_name.'<br>
                //                 Group Contact:
                //                 Group Contact email: '.$session_subscriber_data->email.'<br>
                //                 Group Contact tel: 
                //                 ';
                $messageText_3= 'Kindly proceed to the link below to register your attendance. This is the only link that will register you under your group.';
                $messageText_4= '<b>Registration link.</b>';

                $messageText_5= 'Please note that registering directly via the Transform Africa Summit website, and not through this link, will not include you in your group or give access to any discounts your group is eligible for.';

                $messageText_6= '<b>Deadline for registration:</b><br>
                    •   International delegates i.e. outside of Rwanda: 3 May 2018<br>
                    •   Local delegates i.e. residents of Rwanda: 24 April 2018<br>';

                $messageText_7= '<b>Key dates:</b><br>
                    •   Transform Africa Economic Forum: 7 May 2018 (Platinum delegates)<br> 
                    •   Transform Africa Summit: 8 – 9 May 2018 (Silver, Gold, Platinum delegates)<br> 
                    •   Transform Africa Summit Golf Tournament: 10 May 2018 (Gold & Platinum delegates)<br> ';


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
                                    '.$messageText_4.'<br>
                                    <a href="'.$invite_url.'">[Click here to the registration form]</a><br>
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
                                    <b>Confirmation:</b><br>
                                    You will receive your confirmation to attend the summit within a few days at which point you may proceed to book your accommodation.

                                </p>
                                <p style="font-size: 13px;">
                                    <b>Collecting your badge:</b><br>
                                    You will be advised on the venue for badge collection. Ensure you bring the identification document you used in your registration process. Please note that your badge will only be issued once you have received your confirmation to attend the summit.

                                </p>
                                <p style="font-size: 13px;">
                                    <b>Accommodation:</b><br>
                                    You will receive a link to book your accommodation with your confirmation email.<br><br>

                                </p>
                                <p style="font-size: 13px;">
                                    <b>Plan your trip:</b><br>
                                    <a href="'.DN.'/plan">Click here for travel information including visas requirements.</a>
                                </p>
                                <p style="font-size: 13px;">
                                    <b>Dedicated Transport Network:</b><br>
                                    To ease your travel around Kigali, silver, gold and platinum pass holders enjoy a shuttle service from the airport to the official Transform Africa hotel booked through the website and between the conference venue, social events and the hotel.
                                </p>
                                <p style="font-size: 13px;">
                                    <b>Car hire:</b><br>
                                    Should you wish to hire a car during your stay here? Please email transformafricasummit@smartafrica.org
                                </p>

                                <p style="font-size: 13px;">
                                    <b>Stay connected </b> <br>
                                    <b>Twitter / Facebook / Youtube:</b> Real Smart Africa <br>
                                    <b>Connect with our official tag:</b> #TAS2018<br>
                                    <b>Website:</b> smartafrica.org<br>
                                </p>
                                <br>
                            </section>
                            <div style="font-size: 13px; padding: 0px; position: relative">
                                <div style="text-align: left; border-top: 1px solid #ddd; padding: 10px 5px">
                                    Regards,<br><br>

                                    '.$session_subscriber_data->company_name.'<br>
                                    Group Admin: '.$session_subscriber_data->firstname.' '.$session_subscriber_data->lastname.'<br>
                                    E:  '.$session_subscriber_data->email.'<br>
                                    Transform Africa Team<br>
                                    E:  enquiries@smartafrica.org<br>
                                    T: +250 (0) 732106106<br>
                                    T: +250 (0) 732107107<br>
                                    T: +250 (0) 732301014<br>
                                    T: +250 (0) 732301013<br>
                                    <a href="'.DN.'/tcs">Terms & Conditions</a> | 
                                    <a href="'.DN.'/privacy">Privacy Policy</a>
                                </div>
                            </div>
                        </div>
                    </body>
                ';

                $message_alt = $messageText_0.' '.$messageText_1.' '.$messageText_2;

                // $contactDetails['from_email'] = 'noreply@smartafrica.org';
                $contactDetails['from_email'] = 'enquiries@smartafrica.org';
                $contactDetails['from_names'] = 'Transform Africa Team';
                $contactDetails['to_email'] = $email;

                $contactDetails['attach'] = false;

                $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);

            }
        }else{
            $diagnoArray[0] = 'ERRORS_FOUND';
            $error_msg = ul_array($validation->errors());
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
                'ERRORS_SCRIPT' => ''
            ];
        }
    }
    
    public static function reminderInvite(){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $prfx = 'invite-';
        foreach($_POST as $index=>$val){
            $ar = explode($prfx,$index);
            if(count($ar)){
                $_SIGNUP[end($ar)] = $val;
            }
        }
        
        global $session_subscriber_data;
        $session_subscriber_ID = $session_subscriber_data->ID;
        
        $validation = $validate->check($_SIGNUP, array(
            'firstname' => array(
                'name' => 'First Name',
                'string' => true,
                'required' => true
            ),
            'lastname' => array(
                'name' => 'Last Names',
                'string' => true
            ),
            'email' => array(
                'name' => 'Email Address',
                'email' => true,
                // 'unique' => "subscriber_category_invite|events_participant",
                'required' => true
            )
        ));
        
        if($validation->passed()){
            
            $subscriberTable = new \Subscriber();
            
            $str = new \Str();
            
            $_SIGNUP = (object)$_SIGNUP;
            
            $firstname = $str->sanAsName($_SIGNUP->firstname);
            
            $lastname = $str->sanAsName($_SIGNUP->lastname);
            
            $email = $str->data_in($_SIGNUP->email);
            
            $invite_ID = $str->data_in($_SIGNUP->id);

            $subscategInviteTable = new \SubscriberCategoryInvite();
            $subscategInviteTable->selectQuery("SELECT* FROM `subscriber_category_invite` WHERE `ID`=?",array($invite_ID));

            if(!$subscategInviteTable->count()){
                $diagnoArray[0] == 'ERRORS_FOUND';
            }else{
                if($invite_data->ID!=$invite_ID){
                    $diagnoArray[0] == 'ERRORS_FOUND';
                }else{
                    $invite_data = $subscategInviteTable->first();
                }

                foreach($subscategInviteTable->data() as $subinvite_data){

                    $shared_string = $subinvite_data->shared_string;
                }
            }
            
            $seconds = \Config::get('time/seconds');
            $token = md5($invite_ID);
            
            if($diagnoArray[0] == 'NO_ERRORS'){

                $invite_url = DN.'/register/'.$token.'/'.$shared_string;
                 
                $subject= 'Reminder: Complete your Transform Africa Summit 2018 Registration invite from '.$session_subscriber_data->company_name;

                $messageText_0= 'Dear <b>'.$firstname.' '.$lastname.'</b>,';

                $messageText_1= 'You were invited to register for the Transform Africa Summit 2018 in Kigali from the 7 – 10 May 2018 by: ';

                $messageText_2= 'Group Name: '.$session_subscriber_data->company_name.'<br>
                                Group Admin Name: '.$session_subscriber_data->firstname.' '.$session_subscriber_data->lastname.'<br>
                                Group Contact email: '.$session_subscriber_data->email.'<br>
                                ';

                $messageText_3='The deadline for registration is fast approaching.';
                $messageText_4= 'Kindly proceed to the link below to register your attendance. This is the only link that will register you under your group.';

                $messageText_5= 'Registration link.';

                $messageText_6= 'Please note that registering directly via the Transform Africa Summit website, and not through this link will not include you in your group or give access to any discounts your group is eligible for.';
                
                $messageText_7= '<b>Deadline for registration:</b><br>
                    •   International delegates i.e. outside of Rwanda: 3 May 2018<br>
                    •   Local delegates i.e. residents of Rwanda: 24 April 2018<br>';

                $messageText_8= '<b>Key dates:</b><br>
                    •   Transform Africa Economic Forum: 7 May 2018 (Platinum delegates)<br> 
                    •   Transform Africa Summit: 8 – 9 May 2018 (Silver, Gold, Platinum delegates)<br> 
                    •   Transform Africa Summit Golf Tournament: 10 May 2018 (Gold & Platinum delegates)<br> ';

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
                                    '.$messageText_5.'<br>
                                    <a href="'.$invite_url.'">[Click here to the registration form]</a><br>
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
                                <p style="font-size: 13px;">
                                    <b>Confirmation:</b><br>
                                    You will receive your confirmation to attend the summit within a few days at which point you may proceed to book your accommodation.

                                </p>
                                <p style="font-size: 13px;">
                                    <b>Collecting your badge:</b><br>
                                    You will be advised on the venue for badge collection. Ensure you bring the identification document you used in your registration process. Please note that your badge will only be issued once you have received your confirmation to attend the summit.

                                </p>
                                <p style="font-size: 13px;">
                                    <b>Accommodation:</b><br>
                                    You will receive a link to book your accommodation with your confirmation email.<br><br>

                                </p>
                                <p style="font-size: 13px;">
                                    <b>Plan your trip:</b><br>
                                    <a href="'.DN.'/plan">Click here for travel information including visas requirements.</a>
                                </p>
                                <p style="font-size: 13px;">
                                    <b>Dedicated Transport Network:</b><br>
                                    To ease your travel around Kigali, silver, gold and platinum pass holders enjoy a shuttle service from the airport to the official Transform Africa hotel booked through the website and between the conference venue, social events and the hotel.
                                </p>
                                <p style="font-size: 13px;">
                                    <b>Car hire:</b><br>
                                    Should you wish to hire a car during your stay here? Please email transformafricasummit@smartafrica.org
                                </p>
                                <br>
                                <p style="font-size: 13px;">
                                    <b>Stay connected </b> <br>
                                    <b>Twitter / Facebook / Youtube:</b> Real Smart Africa <br>
                                    <b>Connect with our official tag:</b> #TAS2018<br>
                                    <b>Website:</b> smartafrica.org<br>
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

                                    Transform Africa Team<br>
                                    E:  invitations@smartafrica.org<br>
                                    T: +250 (0) 732106106<br>
                                    T: +250 (0) 732107107<br>
                                    T: +250 (0) 732301014<br>
                                    T: +250 (0) 732301013<br>
                                    <a href="'.DN.'/tcs">Terms & Conditions</a> | 
                                    <a href="'.DN.'/privacy">Privacy Policy</a>
                                </div>
                            </div>
                        </div>
                    </body>
                ';

                $message_alt = $messageText_0.' '.$messageText_1.' '.$messageText_2;

                // $contactDetails['from_email'] = 'noreply@smartafrica.org';
                $contactDetails['from_email'] = 'invitations@smartafrica.org';
                $contactDetails['from_names'] = 'Transform Africa Team';
                $contactDetails['to_email'] = $email;

                $contactDetails['attach'] = false;

                $email_status = Functions::smartMailer($contactDetails,$subject,$message_body,$message_alt);

            }
        }else{
            $diagnoArray[0] = 'ERRORS_FOUND';
            $error_msg = ul_array($validation->errors());
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
                'ERRORS_SCRIPT' => ''
            ];
        }
    }
    
    public static function reminderInvite2(){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $prfx = 'invite-';
        foreach($_POST as $index=>$val){
            $ar = explode($prfx,$index);
            if(count($ar)){
                $_SIGNUP[end($ar)] = $val;
            }
        }
        
        global $session_subscriber_data;
        $session_subscriber_ID = $session_subscriber_data->ID;
            
        $subscriberTable = new \Subscriber();

        $str = new \Str();

        $_SIGNUP = (object)$_SIGNUP;

        $invite_ID = $str->data_in($_SIGNUP->id);

        $subscategInviteTable = new \SubscriberCategoryInvite();
        $subscategInviteTable->selectQuery("SELECT* FROM `subscriber_category_invite` WHERE `subscriber_ID`='{$session_subscriber_ID}' AND `ID`=? AND `status`='Invited'",array($invite_ID));

        if(!$subscategInviteTable->count()){
            $diagnoArray[0] == 'ERRORS_FOUND';
        }else{
            $invite_data = $subscategInviteTable->first();
        }

        $seconds = \Config::get('time/seconds');

        if($diagnoArray[0] == 'NO_ERRORS'){
            
            try{
                $subscategInviteTable->update(array(
                    'firstname'=>'',
                    'lastname'=>'',
                    'email'=>'',
                    'status'=>'Available',
                    'hash'=>'',
                    'token'=>''
                ),$invite_ID);
            }catch(Exeption $e){
                $diagnoArray[0] = "ERRORS_FOUND";
                $diagnoArray[] = $e->getMessage();
            }
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
                'ERRORS_SCRIPT' => ''
            ];
        }
    }
    
}