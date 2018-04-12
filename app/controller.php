<?php

$error_message = '';
$form_error = false;
$form = (object)['ERRORS' => false];

$request = Input::get('request','get');
$url_struc['tree'] = Input::get('request','get');
$url_struc['trunk'] = Input::get('trunk','get');
$url_struc['branch'] = Input::get('branch','get');
$var_branch = array();

if(Input::checkInput('branch','get','1')){
    $var_branch = explode('-',Input::get('branch','get'));
    $url_struc['branch'] = $var_branch[0];
}

$url_struc['branch-sub1'] = @$var_branch[1];
$url_struc['branch-sub2'] = @$var_branch[2];


if($url_struc['tree'] =='app'){
    $url_struc['app-idname'] = Input::get('idname','get');
}
if($url_struc['branch-sub2'] =='export'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/export/'.$event_ID);
}

if($url_struc['branch-sub2'] =='exportsearch'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportsearch/');
}

if($url_struc['branch-sub2'] =='exportforapproval'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportforapproval/');
}
if($url_struc['branch-sub2'] =='exportdelegate'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportdelegate/');
}
if($url_struc['branch-sub2'] =='exportexhibitor'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportexhibitor/');
}
if($url_struc['branch-sub2'] =='exportftg'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportftg/');
}
if($url_struc['branch-sub2'] =='exportmsgeek'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportmsgeek/');
}
if($url_struc['branch-sub2'] =='exportpendingbanktransfer'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportpendingbanktransfer/');
}
if($url_struc['branch-sub2'] =='exportspeaker'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportspeaker/');
}
if($url_struc['branch-sub2'] =='exportvisitor'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportvisitor/');
}
if($url_struc['branch-sub2'] =='exportmedia'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportmedia/');
}
if($url_struc['branch-sub2'] =='exportapproved'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportapproved/');
}
if($url_struc['branch-sub2'] =='exportdenied'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportdenied/');
}
if($url_struc['branch-sub2'] =='exportpending'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportpending/');
}
if($url_struc['branch-sub2'] =='exportgovernment'){
    
    $event_ID = Input::get('id','get');
    Redirect::to(DNADMIN.'/exportgovernment/');
}

//********************//
//    GET DETECTS    //
//******************//

if(Input::checkInput('request','get','1')){
	$post_request = Input::get('request','get');
	switch($post_request){
            
       // Logout
            
		case 'logout':
			$db = DB::getInstance();
            $sessionName = Config::get('session/session_name');
            $cookieName = Config::get('remember/cookie_name');
            $temp = Config::get('time/seconds');
            if(isset($_SESSION[$sessionName])){
                $user_ID = Session::get($sessionName);
                
                $pageviewClass = new PageView();
                $page_type = 'Logout';
                $page_item_ID = 3;

                $grab_info = '';
                $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                         'user_ID'=>$session_user_ID,
                                         'email'=>$session_user_data->email,
                                         'type'=>$page_type));
        
                $db->delete('user_session',array('user_ID','=',$user_ID));
                $db->update('app_users',$user_ID,array('account_session'=>'0','last_access'=>$temp));
                Cookie::delete($cookieName);
                
                session_destroy();
                session_unset();
                session_regenerate_id(true);

                $sessionName = Config::get('session/session_name');
                $cookieName = Config::get('remember/cookie_name');

                if(isset($_COOKIE["$sessionName"])){
                    unset($_COOKIE["$sessionName"]);
                    setcookie($sessionName, null, -1, '/');
                    Cookie::delete($cookieName);
                } 
            }
            Redirect::to(DNADMIN.'/login');
		break;
            
		case 'resetpassword':
            if(Input::checkInput('id','get','1')){
               $generated_string = Input::get('code','get');

                $user_id = Input::get('id','get');
                $userTable = new User();
                $userTable->selectQuery("SELECT * FROM `app_users` WHERE `ID`= ? AND `recovery_string`!=''",array($user_id));
                if(!$userTable->count()){
                    Redirect::to(DNADMIN.'/login/forgotpassword');
                }else{

                    $user_data = $userTable->first();
                    $secret_key = $user_data->password;
                    $recovery_string = strtoupper(hash_hmac('SHA256', $generated_string, pack('H*',$secret_key)));
                    if($recovery_string == $user_data->recovery_string){

                        $user_ID = $user_data->ID;                 
                    }else{
                        Redirect::to(DNADMIN.'/login/forgotpassword');
                    }
                } 
            }else{
                if(Input::get('response','get') != 'success'){
                    Redirect::to(DNADMIN.'/login/forgotpassword');
                }
            }
		break;
    }
    
}
?>
  


<?php 
          
		// USERS
if(Input::checkInput('webToken','post','1') && Input::checkInput('request','post','1')){
	$post_request = Input::get('request','post');
	switch($post_request){
		// USERS
		case 'user_sigggnup':
			$form = UserController::signup();
			if($form->ERRORS == false){
                $_POST['login_username'] = $_POST['signup_username'];
                $_POST['login_password'] = $_POST['signup_password'];
                $form = UserController::login('Signup');
                $form = CompanyController::create();
				Redirect::to('login');
			}else{
				// echo errors
			}
		break;
		case 'user_login':
			$form = UserController::login();
			if($form->ERRORS == false){
				Redirect::to(DNADMIN);
			}else{
				//echo errors
			}
		break;
		case 'recover-login':
            if(Input::checkInput('recover-email','post','1')){
                $user_email = Input::get('recover-email','post');
                $userTable = new User();
                $userTable->selectQuery("SELECT * FROM `app_users` WHERE `email`= ?",array($user_email));
                if(!$userTable->count()){
                    $form->ERRORS = true;
                }else{
                    $user_data = $userTable->first();
                    if($user_data->email == $user_email){
                        
                        $user_ID = $user_data->ID;

                        $form = UserController::requestPasswordReset($user_ID);

                        if($form->ERRORS == false){
                            Redirect::to(DNADMIN.'/login/forgotpassword/success');
                        }else{
                            //echo errors
                        }                   
                    }
                }
			}
            Redirect::to(DNADMIN.'/login/forgotpassword/errors');
		break;
		case 'user-new':
            $CompanyDb = DB::getInstance();
            $Company_select = $CompanyDb->get(array('ID'),'app_company',array('ID','=',$session_company_ID));
            if($Company_select->count()){
                $_POST['user-company_ID'] = $session_company_ID;
                $form = UserController::add();
                if($form->ERRORS == false){
                   Redirect::to(DNADMIN.'/company/users/list');
                }else{
                    //echo errors
                }
            }
		break;
		case 'user-update':
            if(Input::checkInput('id','get','1')){
                $user_ID = Input::get('id','get');
                $userTable = new User();
                $userTable->selectQuery("SELECT * FROM `app_users` WHERE `company_ID`=? AND `ID`= ?",array($session_company_ID,$user_ID));
                if(!$userTable->count()){
                    Functions::errorPage(404);
                }else{
                    $user_data = $userTable->first();
                    $user_ID = $user_data->ID;
                
                    $user_ID = Str::sanAsID(Input::get('id','get'));
                    if($url_struc['branch-sub1'] == 'password'){
                        $form = UserController::updatePassword($user_ID);
                    }else{
                        $form = UserController::update($user_ID);
                    }
                    
                    if($form->ERRORS == false){
				        Session::put('success','User account updated successfully');
                        Redirect::to(DNADMIN.'/company/users/list');
                    }else{
                        //echo errors
                    }
                }
			}else{
				Session::put('errors','Bad request! Please, contact the Admin'); 
                Redirect::to(DNADMIN.'/company/users/list');
			}
		break;
		case 'reset-password':
            if(Input::checkInput('id','get','1')){
                $user_ID = Input::get('id','get');
                $userTable = new User();
                $userTable->selectQuery("SELECT * FROM `app_users` WHERE `ID`= ?",array($user_ID));
                if(!$userTable->count()){
                   // Redirect::to(DNADMIN.'/404');
                }else{
                    $user_data = $userTable->first();
                    $user_ID = $user_data->ID;
                
                    $form = UserController::resetPassword($user_ID);
                    
                    if($form->ERRORS == false){
				        Session::put('success','Password changed successfully');
                        Redirect::to(DNADMIN.'/login/resetpassword/success');
                    }else{
                        //echo errors
                    }
                }
			}else{
                Redirect::to(DNADMIN.'/404');
			}
		break;
            
		case 'user-state':
            $user_ID = Str::sanAsID(Input::get('user-id','post'));
            $userTable = new User();
            $userTable->selectQuery("SELECT * FROM `app_users` WHERE `company_ID`=? AND `ID`= ?",array($session_company_ID,$user_ID));
            if(!$userTable->count()){
                Functions::errorPage(404);
            }else{
                $user_data = $userTable->first();
                if(Input::checkInput('block','post','0')){
                     $state = 'Block';
                     $form = UserController::changeState($state,$user_ID);
                 }elseif(Input::checkInput('activate','post','0')){
                     $state = 'Activate';
                     $form = UserController::changeState($state,$user_ID);
                     if($form){
                         
                     }
                    
                    if($form->ERRORS == false){
                       Redirect::to(DNADMIN.'/company/users/list');
                    }else{
                        //echo errors
                    }
                }
			}
        break;
            
        // SUBSCRIBER
        
        case 'subscriber-new':
            $_POST['subscriber-admin_ID'] = $session_user_ID;
            $form = SubscriberController::addSpecialAccount();
            if($form->ERRORS == false){
                $subscriber_data = $form->ERRORS_SCRIPT['data'];
               Redirect::to(DNADMIN.'/company/subscriber/'.$subscriber_data->ID.'/category');
            }else{
                //echo errors
            }
        break;
        case 'subscriber-new-invite':
            $_POST['subscriber-admin_ID'] = $session_user_ID;
            $form = SubscriberController::addSpecialAccountInvite();
            if($form->ERRORS == false){
                $subscriber_data = $form->ERRORS_SCRIPT['data'];
               Redirect::to(DNADMIN.'/company/subscriber/'.$subscriber_data->ID.'/categoryinvite');
            }else{
                //echo errors
            }
        break;
        case 'subscriber-new-invite-reminder':
            $_POST['subscriber-admin_ID'] = $session_user_ID;
            $form = SubscriberController::addSpecialAccountInviteReminder();
            if($form->ERRORS == false){
                $subscriber_data = $form->ERRORS_SCRIPT['data'];
               Redirect::to(DNADMIN.'/company/subscriber/list');
            }else{
                //echo errors
            }
        break;
        case 'subscriber-newreminder':
            if(Input::checkInput('subscriber-id','post','1')){
                $user_ID = Input::get('subscriber-id','post');
                $subscriberTable = new Subscriber();
                $subscriberTable->selectQuery("SELECT * FROM `subscriber` WHERE `ID`= ?",array($user_ID));
                if(!$subscriberTable->count()){
                    Functions::errorPage(404);
                }else{
                    $user_data = $subscriberTable->first();
                    $user_ID = $user_data->ID;
                    $user_email = $user_data->email;
                    // $user_email = 'abrahamahoshakiye@gmail.com';
                    $user_fullname = $user_data->firstname.' '.$user_data->lastname;
                
                    // $user_ID = Str::sanAsID(Input::get('id','get'));
                    // if($url_struc['branch-sub1'] == 'password'){
                    //     $form = SubscriberController::updatePassword($user_ID);
                    // }else{
                    //     $form = SubscriberController::update($user_ID);
                    // }
                    
                    $form = SubscriberController::aSpecialAccountInviteReminder($user_email, $user_fullname);
                    // if($form->ERRORS == false){
                    //     $subscriber_data = $form->ERRORS_SCRIPT['data'];
                    //     Redirect::to(DNADMIN.'/company/subscriber/'.$subscriber_data->ID.'/categoryinvite');
                    // }else{
                    //     //echo errors
                    // }

                    if($form->ERRORS == false){
                        Session::put('success','Reminder sended successfully');
                        Redirect::to(DNADMIN.'/company/subscriber/list');
                    }else{
                        //echo errors
                    }
                }
            }else{
                Session::put('errors','Bad request! Please, contact the Admin!!!'); 
                Redirect::to(DNADMIN.'/company/subscriber/list');
            }



            // $_POST['subscriber-admin_ID'] = $session_user_ID;
            // $form = SubscriberController::SpecialAccountInviteReminder();
            // if($form->ERRORS == false){
            //     $subscriber_data = $form->ERRORS_SCRIPT['data'];
            //    Redirect::to(DNADMIN.'/company/subscriber/'.$subscriber_data->ID.'/categoryinvite');
            // }else{
                //echo errors
            // }
        break;
		case 'subscriber-update':
            if(Input::checkInput('id','get','1')){
                $user_ID = Input::get('id','get');
                $subscriberTable = new Subscriber();
                $subscriberTable->selectQuery("SELECT * FROM `subscriber` WHERE `ID`= ?",array($user_ID));
                if(!$subscriberTable->count()){
                    Functions::errorPage(404);
                }else{
                    $user_data = $subscriberTable->first();
                    $user_ID = $user_data->ID;
                
                    $user_ID = Str::sanAsID(Input::get('id','get'));
                    if($url_struc['branch-sub1'] == 'password'){
                        $form = SubscriberController::updatePassword($user_ID);
                    }else{
                        $form = SubscriberController::update($user_ID);
                    }
                    
                    if($form->ERRORS == false){
				        Session::put('success','User account updated successfully');
                        Redirect::to(DNADMIN.'/company/subscriber/list');
                    }else{
                        //echo errors
                    }
                }
			}else{
				Session::put('errors','Bad request! Please, contact the Admin'); 
                Redirect::to(DNADMIN.'/company/subscriber/list');
			}
		break;
		case 'subscateg-update':
            if(Input::checkInput('id','get','1')){
                $subscriber_ID = Input::get('id','get');
                $subscriberTable = new Subscriber();
                $subscriberTable->selectQuery("SELECT * FROM `subscriber` WHERE `ID`= ?",array($subscriber_ID));
                if(!$subscriberTable->count()){
                    Functions::errorPage(404);
                }else{
                    $subscriber_data = $subscriberTable->first();
                    $subscriber_ID = $subscriber_data->ID;
                
                    $subscriber_ID = Str::sanAsID(Input::get('id','get'));
                    
                    $form = SubscriberCategoryController::add($subscriber_ID);
                    
                    if($form->ERRORS == false){
                        Session::put('success','Record successful');
                        Redirect::to(DNADMIN."/company/subscriber/$subscriber_ID/category");
                    }else{
                        //echo errors
                    }
                }
			}else{
				Session::put('errors','Bad request! Please, contact the Admin'); 
                Redirect::to(DNADMIN.'/company/subscriber/list');
			}
		break;
//		case 'subscateg-update_allocated':
//            if(Input::checkInput('id','get','1')){
//                $subscriber_ID = Input::get('id','get');
//                $subscriberTable = new Subscriber();
//                $subscriberTable->selectQuery("SELECT * FROM `subscriber` WHERE `ID`= ?",array($subscriber_ID));
//                if(!$subscriberTable->count()){
//                    Functions::errorPage(404);
//                }else{
//                    $subscriber_data = $subscriberTable->first();
//                    $subscriber_ID = $subscriber_data->ID;
//                
//                    $subscriber_ID = Str::sanAsID(Input::get('id','get'));
//                    
//                    $form = SubscriberCategoryController::updateAllocatedPasses($subscriber_ID);
//                    
//                    if($form->ERRORS == false){
//                        Session::put('success','Record successful');
//                        Redirect::to(DNADMIN."/company/subscriber/$subscriber_ID/category");
//                    }else{
//                        //echo errors
//                    }
//                }
//			}else{
//				Session::put('errors','Bad request! Please, contact the Admin'); 
//                Redirect::to(DNADMIN.'/company/subscriber/list');
//			}
//		break;
		case 'subscriber-password_reset':
            if(Input::checkInput('id','get','1')){
                $user_ID = Input::get('id','get');
                $userTable = new User();
                $userTable->selectQuery("SELECT * FROM `app_users` WHERE `ID`= ?",array($user_ID));
                if(!$userTable->count()){
                   // Redirect::to(DNADMIN.'/404');
                }else{
                    $user_data = $userTable->first();
                    $user_ID = $user_data->ID;
                
                    $form = UserController::resetPassword($user_ID);
                    
                    if($form->ERRORS == false){
				        Session::put('success','Password changed successfully');
                        Redirect::to(DNADMIN.'/login/resetpassword/success');
                    }else{
                        //echo errors
                    }
                }
			}else{
                Redirect::to(DNADMIN.'/404');
			}
		break;
            
		case 'subscriber-state':
            $user_ID = Str::sanAsID(Input::get('user-id','post'));
            $userTable = new User();
            $userTable->selectQuery("SELECT * FROM `app_users` WHERE `company_ID`=? AND `ID`= ?",array($session_company_ID,$user_ID));
            if(!$userTable->count()){
                Functions::errorPage(404);
            }else{
                $user_data = $userTable->first();
                if(Input::checkInput('block','post','0')){
                     $state = 'Block';
                     $form = UserController::changeState($state,$user_ID);
                 }elseif(Input::checkInput('activate','post','0')){
                     $state = 'Activate';
                     $form = UserController::changeState($state,$user_ID);
                     if($form){
                         
                     }
                    
                    if($form->ERRORS == false){
                       Redirect::to(DNADMIN.'/company/subscriber/list');
                    }else{
                        //echo errors
                    }
                }

			}
        break;
            
        // Participant Category //
            
            
		case 'category-new':
            if($session_user_data->groups == 'Admin'){
               $participantCategTable = new ParticipantCategory();
                $form = ParticipantCategoryController::add();
                if($form->ERRORS == false){ 
                    Redirect::to(DNADMIN.'/app/EventApp/5/category-list');
                }else{
                    //echo errors
                }
            }
		break;
            
        // PARTICIPANTS //
            
        case 'participant-state':
            if(
                Input::checkInput('participant-event_id','post','1') &&
                Input::checkInput('participant-id','post','1')
              ){
                
                $company_ID = $session_company_data->ID;
                $event_ID = sanAsID(Input::get('participant-event_id','post'));
                $participant_ID = sanAsID(Input::get('participant-id','post'));
                $CompanyDb = DB::getInstance();
                $participantTable = new Participant();

                $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                if($participantTable->count()){
                    $participant_data = $participantTable->first();
                
                    if($session_user_data->groups == 'Admin' || $session_user_data->groups == 'RG-Admin' || $session_user_data->groups == 'RG-SUPER-Admin'){
                         if(Input::checkInput('pending','post','0')){
                             $state = 'Pending';
                             $form = ParticipantController::changeState($state,$participant_ID);
                         }elseif(Input::checkInput('confirm','post','0')){
                             $state = 'Confirm';
                             $form = ParticipantController::changeState($state,$participant_ID);
                             $email_status = ParticipantController::participantConfirmedEmail($participant_data);

                         }elseif(Input::checkInput('deny','post','0')){
                             $state = 'Deny';
                             $form = ParticipantController::changeState($state,$participant_ID);
                             $email_status = ParticipantController::participantDenyEmail($participant_data);
                         }
                        if($form->SUCCESS==true){

                            $pageviewClass = new PageView();
                            $page_type = $participant_data->code;
                            $page_item_ID = 5;

                            $grab_info = "Registration-State: $state";
                            $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                         'user_ID'=>$session_user_data->ID,
                                                         'email'=>$session_user_data->email,
                                                         'grabbed_info'=>$grab_info,
                                                         'type'=>$page_type));

                        }
                    }  
                    if($session_user_data->groups == 'Admin' || $session_user_data->groups == 'YCA-SUPER-Admin' || $session_user_data->groups == 'YCA-Admin' || $session_user_data->groups == 'OGS-User'){

                        if(Input::checkInput('payment-confirm','post','0')){
                             $state = 'Confirm';
                             $form = ParticipantController::changePaymentState($state,$participant_ID);
                             if($form->SUCCESS==true){

                                $participantTable = new Participant();

                                $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                                if($participantTable->count()){
                                    $participant_data = $participantTable->first();

                                    $pageviewClass = new PageView();
                                    $page_type = $participant_data->code;
                                    $pass_type = $participant_data->pass_type;
                                    $page_item_ID = 5;

                                    $grab_info = "Payment State: $state";
                                    $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                             'user_ID'=>$session_user_data->ID,
                                                             'email'=>$session_user_data->email,
                                                             'grabbed_info'=>$grab_info,
                                                             'type'=>$page_type));
                                    if ($pass_type!="Government") {
                                        // $email_status = ParticipantController::paymentConfirmedEmail($participant_data);
                                    }
                                    
                                }
                            }

                        }
                        if(Input::checkInput('payment-pending','post','0')){
                             $state = 'Pending';
                             $form = ParticipantController::changePaymentState($state,$participant_ID);
                             if($form->SUCCESS==true){

                                $participantTable = new Participant();

                                $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                                if($participantTable->count()){
                                    $participant_data = $participantTable->first();


                                    $pageviewClass = new PageView();
                                    $page_type = $participant_data->code;
                                    $page_item_ID = 5;

                                    $grab_info = "Payment State: $state";
                                    $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                             'user_ID'=>$session_user_data->ID,
                                                             'email'=>$session_user_data->email,
                                                             'grabbed_info'=>$grab_info,
                                                             'type'=>$page_type));

    //                               $email_status = ParticipantController::paymentReactivatedEmail($participant_data);
                                }
                            }

                        }
                        if(Input::checkInput('payment-refund','post','0')){
                             $state = 'Refund';
                             $form = ParticipantController::changePaymentState($state,$participant_ID);
                             if($form->SUCCESS==true){

                                $participantTable = new Participant();

                                $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                                if($participantTable->count()){
                                    $participant_data = $participantTable->first();


                                    $pageviewClass = new PageView();
                                    $page_type = $participant_data->code;
                                    $page_item_ID = 5;

                                    $grab_info = "Payment State: $state";
                                    $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                             'user_ID'=>$session_user_data->ID,
                                                             'email'=>$session_user_data->email,
                                                             'grabbed_info'=>$grab_info,
                                                             'type'=>$page_type));
    //                               $email_status = ParticipantController::paymentCRefundedEmail($participant_data);
                                }
                            }

                        }
                        if(Input::checkInput('payment-close','post','0')){
                             $state = 'Close';
                             $form = ParticipantController::changePaymentState($state,$participant_ID);
                             if($form->SUCCESS==true){

                                $participantTable = new Participant();

                                $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                                if($participantTable->count()){
                                    $participant_data = $participantTable->first();


                                    $pageviewClass = new PageView();
                                    $page_type = $participant_data->code;
                                    $page_item_ID = 5;

                                    $grab_info = "Payment State: $state";
                                    $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                             'user_ID'=>$session_user_data->ID,
                                                             'email'=>$session_user_data->email,
                                                             'grabbed_info'=>$grab_info,
                                                             'type'=>$page_type));

                                   //$email_status = ParticipantController::paymentClosedEmail($participant_data);
                                }
                            }

                        }
                     }

                    if($form->ERRORS == false){
                       // Redirect::to(DNADMIN._.'app/EventApp/'.$event_ID.'/participant-list');
                        $url = $_SERVER['REQUEST_URI'];
                        Redirect::to($url);
                    }else{
                        //echo errors
                    }
                }else{
                    Redirect::to(DN.'/404');
                }
            }
        break;
            
        case 'participant-editCategory':
            if(
                Input::checkInput('participant-event_id','post','1') &&
                Input::checkInput('participant-id','post','1')
              ){
                
                $company_ID = $session_company_data->ID;
                $event_ID = sanAsID(Input::get('participant-event_id','post'));
                $participant_ID = sanAsID(Input::get('participant-id','post'));
                $participant_category = Input::get('participant-category','post');
                $participant_pass = Input::get('participant-pass','post');
                $CompanyDb = DB::getInstance();
                $participantTable = new Participant();

                $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                if($participantTable->count()){
                    $participant_data = $participantTable->first();

                    $code=$participant_data->code;
                    $payment_state=$participant_data->payment_state;
                    $participant_code= $participant_data->code;
                
                    if($session_user_data->groups == 'Admin' || $session_user_data->groups == 'RG-Admin' || $session_user_data->groups == 'RG-SUPER-Admin' || $session_user_data->groups == 'YCA-Admin' || $session_user_data->groups == 'YCA-SUPER-Admin'){
                        $form = ParticipantController::changeCategory($participant_category,$participant_pass,$code,$participant_ID,$payment_state);



                        $allowedExts = array("jpg","jpeg");
                        $extension = end(explode(".", $_FILES["register-photo"]["name"]));
                        if (( ($_FILES["register-photo"]["type"] == "image/jpeg")
                            || ($_FILES["register-photo"]["type"] == "image/jpg"))
                            && ($_FILES["register-photo"]["size"] < 2000000)
                            && in_array($extension, $allowedExts)) {
                            if ($_FILES["register-photo"]["error"] > 0) {
                                echo "Return Code: " . $_FILES["register-photo"]["error"] . "<br />";
                            } else {
                                // echo "Upload: " . $_FILES["register-photo"]["name"] . "<br />";
                                // echo "Type: " . $_FILES["register-photo"]["type"] . "<br />";
                                // echo "Size: " . ($_FILES["register-photo"]["size"] / 2000) . " Kb<br />";
                                // echo "Temp file: " . $_FILES["register-photo"]["tmp_name"] . "<br />";

                                // if (file_exists("upload/" . $_FILES["register-photo"]["name"])) {
                                //     // echo $_FILES["register-photo"]["name"] . " already exists. ";
                                //     Session::put('errors',$_FILES["register-photo"]["name"] . " already exists. ");
                                // } else {
                                //     move_uploaded_file($_FILES["register-photo"]["tmp_name"],
                                //     "data_user/participants/" . $_FILES["register-photo"]["name"]);
                                //     // echo "Stored in: " . "../" . $_FILES["register-photo"]["name"];
                                //     Session::put('success',"Stored in: " . "data_user/participants/" . $_FILES["register-photo"]["name"]);
                                // }


                                $newfilename = $participant_code.'.'.$extension; 
                                move_uploaded_file($_FILES["register-photo"]["tmp_name"],"data_user/participants/".$newfilename);

                            }
                        } else {
                            // echo "Invalid file";
                            Session::put('errors','Invalid file');
                        }



                            // Uploading proposal

                            // $dir = 'data_user/participants';
                            // $fileName = $_FILES["register-photo"]["name"]; 
                            // $fileTmpLoc = $_FILES["register-photo"]["tmp_name"]; 
                            // $fileType = $_FILES["register-photo"]["type"]; 
                            // $fileSize = $_FILES["register-photo"]["size"];
                            // $fileErrorMsg = $_FILES["register-photo"]["error"]; 
                            // $kaboom = explode(".", $fileName); 
                            // $fileExt = strtolower(end($kaboom)); 
                            // $newfilename = $participant_code.'.'.$fileExt; 
                            // $upload_path = Config::get('url/bk_dir')."/$dir/$newfilename";
                            // $file_path = "$dir/$newfilename";

                            // if($fileName){
                            //         $moveResult = move_uploaded_file($fileTmpLoc, $upload_path);
                            //     if(in_array($fileExt,array('pdf','png','jpg','jpeg'))){
                            //         // $moveResult = move_uploaded_file($fileTmpLoc, $upload_path);
                            //         $moveResult = move_uploaded_file($fileTmpLoc, $upload_path);
                            //         if($moveResult){
                            //             Session::put('success','Upload');
                            //             // $file_uploaded = true;
                            //         }
                            //             Session::put('success','Upload1 '.$newfilename);
                            //     }

                            //             // Session::put('success','Upload2');
                            // }else {
                            //         // echo "Invalid file";
                            //         Session::put('errors','Invalid file');
                            //     }



                                /*
                                $allowedExts = array("jpeg", "jpg", "png");
                                $extension = end(explode(".", $_FILES["register-photo"]["name"]));
                                if ((($_FILES["register-photo"]["type"] == "image/png")
                                    || ($_FILES["register-photo"]["type"] == "image/jpeg")
                                    || ($_FILES["register-photo"]["type"] == "image/jpg"))
                                && ($_FILES["register-photo"]["size"] < 2000000)
                                && in_array($extension, $allowedExts)) {
                                    if ($_FILES["register-photo"]["error"] > 0) {
                                        Session::put('errors','Return Code'.$_FILES["register-photo"]["error"]);
                                        // echo "Return Code: " . $_FILES["register-photo"]["error"] . "<br />";
                                    } else {
                                        
                                        // echo "Upload: " . $_FILES["register-photo"]["name"] . "<br />";
                                        // echo "Type: " . $_FILES["register-photo"]["type"] . "<br />";
                                        // echo "Size: " . ($_FILES["register-photo"]["size"] / 2000) . " Kb<br />";
                                        // echo "Temp file: " . $_FILES["register-photo"]["tmp_name"] . "<br />";
                                        
                                        // if (file_exists("upload/" . $_FILES["file"]["name"])) {
                                        //     echo $_FILES["file"]["name"] . " already exists. ";
                                        // } else {
                                        //     move_uploaded_file($_FILES["file"]["tmp_name"],
                                        //     "../" . $_FILES["file"]["name"]);
                                        //     echo "Stored in: " . "../" . $_FILES["file"]["name"];
                                        // }
                                        move_uploaded_file($_FILES["register-photo"]["tmp_name"],DNADMIN."/data_user/participants/pic.".$extension);
                                        Session::put('success','Upload'. $_FILES["register-photo"]["name"] .'<br />');
                                    }
                                } else {
                                    // echo "Invalid file";
                                    Session::put('errors','Invalid file');
                                }
                                */


                        $participantTable = new Participant();

                        $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                        if($participantTable->count()){
                            $participant_data = $participantTable->first();
                            // $email_status = ParticipantController::editCategoryConfirmationEmail($participant_data);
                        }


                            // Uploading proposal
                            // $dir = 'data_user/participants';
                            // $fileName = $_FILES["register-photo"]["name"]; 
                            // $fileTmpLoc = $_FILES["register-photo"]["tmp_name"]; 
                            // $fileType = $_FILES["register-photo"]["type"]; 
                            // $fileSize = $_FILES["register-photo"]["size"];
                            // $fileErrorMsg = $_FILES["register-photo"]["error"]; 
                            // $kaboom = explode(".", $fileName); 
                            // $fileExt = strtolower(end($kaboom)); 
                            // $newfilename = $participant_code.'.'.$fileExt; 
                            // $upload_path = Config::get('url/bk_dir')."/$dir/$newfilename";
                            // $file_path = "$dir/$newfilename";

                            // if($fileName){
                            //     if(in_array($fileExt,array('pdf','png','jpg','jpeg'))){
                            //         $moveResult = move_uploaded_file($fileTmpLoc, $upload_path);
                            //         if($moveResult){
                            //             $file_uploaded = true;
                            //         }
                            //     }
                            // }




                        if($form->SUCCESS==true){

                            $pageviewClass = new PageView();
                            $page_type = $participant_data->code;
                            $page_item_ID = 5;

                            $grab_info = "Registration-State: $participant_category";
                            $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                         'user_ID'=>$session_user_data->ID,
                                                         'email'=>$session_user_data->email,
                                                         'grabbed_info'=>$grab_info,
                                                         'type'=>$page_type));

                        }

                    }
                    

                    if($form->ERRORS == false){
                        $url = $_SERVER['REQUEST_URI'];
                        Redirect::to($url);
                        // Redirect::to(DNADMIN._.'app/EventApp/'.$event_ID.'/participant-list');
                    }else{
                        //echo errors
                    }
                }else{
                    Redirect::to(DN.'/404');
                }
            }
        break;
            
            
        case 'participant-editSessions':
            if(
                Input::checkInput('participant-event_id','post','1') &&
                Input::checkInput('participant-id','post','1')
              ){
                
                $company_ID = $session_company_data->ID;
                $event_ID = sanAsID(Input::get('participant-event_id','post'));
                $participant_ID = sanAsID(Input::get('participant-id','post'));
                $participant_gala_dinner = Input::get('participant-gala_dinner','post');
                $participant_board_meeting = Input::get('participant-board_meeting','post');
                $participant_smart_women = Input::get('participant-smart_women','post');
                $participant_mayors_lunch = Input::get('participant-mayors_lunch','post');
                $participant_ceo_lunch = Input::get('participant-ceo_lunch','post');
                $CompanyDb = DB::getInstance();
                $participantTable = new Participant();

                $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                if($participantTable->count()){
                    $participant_data = $participantTable->first();

                    $code=$participant_data->code;
                
                    if($session_user_data->groups == 'Admin' || $session_user_data->groups == 'RG-Admin' || $session_user_data->groups == 'RG-SUPER-Admin' || $session_user_data->groups == 'YCA-SUPER-Admin' || $session_user_data->groups == 'YCA-Admin'){
                        $form = ParticipantController::changeSessions($participant_gala_dinner,
                            $participant_board_meeting,$participant_smart_women,
                            $participant_mayors_lunch,$participant_ceo_lunch,$participant_ID);

                        $participantTable = new Participant();

                        $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                        if($participantTable->count()){
                            $participant_data = $participantTable->first();
                            $email_status = ParticipantController::changeSessionsConfirmationEmail($participant_data);
                        }
                        if($form->SUCCESS==true){

                            $pageviewClass = new PageView();
                            $page_type = $participant_data->code;
                            $page_item_ID = 5;

                            $grab_info = "Invite in session";
                            $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                         'user_ID'=>$session_user_data->ID,
                                                         'email'=>$session_user_data->email,
                                                         'grabbed_info'=>$grab_info,
                                                         'type'=>$page_type));

                        }

                    }
                    

                    if($form->ERRORS == false){
                        $url = $_SERVER['REQUEST_URI'];
                        Redirect::to($url);
                        // Redirect::to(DNADMIN._.'app/EventApp/'.$event_ID.'/participant-list');
                    }else{
                        //echo errors
                    }
                }else{
                    Redirect::to(DN.'/404');
                }
            }
        break;

		case 'participant-payment-state':
            if(
                Input::checkInput('participant-event_id','post','1') &&
                Input::checkInput('participant-id','post','1')
              ){
                
                $company_ID = $session_company_data->ID;
                $event_ID = sanAsID(Input::get('participant-event_id','post'));
                $participant_ID = sanAsID(Input::get('participant-id','post'));
                $CompanyDb = DB::getInstance();
                if($session_user_data->groups == 'Admin' && $session_user_data->ID == $session_company_data->user_ID){
                    if(Input::checkInput('confirm','post','0')){
                         $state = 'Confirm';
                         $form = ParticipantController::changePaymentState($state,$participant_ID);
                         if($form->SUCCESS==true){
                           
                            $participantTable = new Participant();

                            $participantTable->selectQuery("SELECT* FROM `events_participant` WHERE `ID`=? ORDER BY ID DESC LIMIT 1",array($participant_ID));

                            if($participantTable->count()){
                                $participant_data = $participantTable->first();

                                
                                $pageviewClass = new PageView();
                                $page_type = $participant_data->code;
                                $page_item_ID = 5;

                                $grab_info = "Payment State: $state";
                                $pageviewClass->insert(array('page_ID'=>$page_item_ID,
                                                         'user_ID'=>$session_user_data->ID,
                                                         'email'=>$session_user_data->email,
                                                         'grabbed_info'=>$grab_info,
                                                         'type'=>$page_type));
                            
                               $email_status = ParticipantController::paymentConfirmationEmail($participant_data);
                            }
                        }
                     }elseif(Input::checkInput('deny','post','0')){
                         $form = ParticipantController::changeState('Deny',$participant_ID);
                     }
                    
                    if($form->ERRORS == false){
                        $url = $_SERVER['REQUEST_URI'];
                        Redirect::to($url);
                        // Redirect::to(DNADMIN._.'app/EventApp/'.$event_ID.'/participant-list');
                    }else{
                        //echo errors
                    }
                }
            }
		break;
	}
}

?>