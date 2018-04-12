<?php
/*
class SubscriberCategoryController  
{   
    public static function exists($subscriber_ID,$category_ID){
        $subscategTable = new \SubscriberCategory();
        $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? ",array($subscriber_ID,$category_ID));
        if($subscategTable->count()){
            return true;
        }
        return false;
    }
    public static function logAction($action,$subscateg_ID,$other_data=array()){
        $subscategTable = new \SubscriberCategory();
        if($action=='ticketUsed'){
            $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `ID`=? ",array($subscateg_ID));
            if($subscategTable->count()){
                $subscateg_data = $subscategTable->first();
                $subscategTable->update(array('size_used'=>$subscateg_data->size_used+1),$subscateg_ID);
                
                if(count($other_data)){
                    $subscategInviteTable = new \SubscriberCategoryInvite();
                    $invite_ID = $other_data['invite_ID'];
                    $participant_ID = $other_data['participant_ID'];
                    $subscategInviteTable->update(array('status'=>'Registered','participant_ID'=>$participant_ID),$invite_ID);
                }
            }
        }elseif($action=='ticketSeen'){
            $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `ID`=? ",array($subscateg_ID));
            if($subscategTable->count()){
                $subscateg_data = $subscategTable->first();
                $subscategTable->update(array('seen'=>$subscateg_data->seen+1),$subscateg_ID);
            }
        }
    }
    
    public static function add($subscriber_ID){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $prfx = 'subscateg-';
        foreach($_POST as $index=>$val){
            $ar = explode($prfx,$index);
            if(count($ar)){
                $_SIGNUP[end($ar)] = $val;
            }
        }
        
        $error_str = '';
        
        $categoryTable = new ParticipantCategory();
        $categoryTable->selectQuery("SELECT * FROM `events_participant_category`");
        
        global $subscriber_data;
        
        if($subscriber_data!='Group'){
            SubscriberController::groupAdminLog($subscriber_ID);
        }

        if($categoryTable->count()){
            $i = 0;
            foreach($categoryTable->data() as $category_data){
                $category_ID = $category_data->ID;
                
                $new_size = @$_SIGNUP[$category_data->name.'_size'];
                if(!$new_size){
                    $new_size = 10;
                }
                
                $new_plan = @$_SIGNUP[$category_data->name.'_plan'];
                
                $discount = @$_SIGNUP[$category_data->name.'_discount'];
                if(is_numeric($discount) && $discount<60){
                    $discount = (int)$discount;
                }else{
                    $discount = 0;
                }
                
                $subscategTable = new \SubscriberCategory();
                    
                $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? AND `type`='Assigned' ",array($subscriber_ID,$category_ID));
                
                if(isset($_SIGNUP[$category_data->name])){
                    if($subscategTable->count()){
                        $subscateg_data = $subscategTable->first();
                        if($new_size > $subscateg_data->seen){
                            $subscategTable->update(array(
                                'size' => $new_size,
                                'plan' => $new_plan,
                                'discount' => $discount
                            ),$subscateg_data->ID);
                        }else{
                            $subscategTable->update(array(
                                'size' => $subscateg_data->seen,
                                'plan' => $new_plan,
                                'discount' => $discount
                            ),$subscateg_data->ID);
                        }
                    }else{
                        $subscategTable->insert(array(
                                'subscriber_ID' => $subscriber_ID,
                                'category_ID' => $category_ID,
                                'size' => $new_size,
                                'plan' => $new_plan,
                                'discount' => $discount,
                                'type' => 'Assigned'
                        ));
                    }
                }else{
                    if($subscategTable->count()){
                        $subscateg_data = $subscategTable->first();
                        if($subscateg_data->seen == 0){
                            $subscategTable->delete(array('ID','=',$subscateg_data->ID));
                        }else{
                            $subscategTable->update(array(
                                'size' => $subscateg_data->seen,
                                'plan' => $new_plan,
                                'discount' => $discount
                            ),$subscateg_data->ID);
                        }
                    }
                }
            }
        }

        if($diagnoArray[0] == 'ERRORS_FOUND'){
            return (object)[
                'ERRORS'=>true,
                'ERRORS_SCRIPT' => $error_str
            ];
        }else{
            return (object)[
                'ERRORS'=>false,
                'SUCCESS'=>true,
                'ERRORS_SCRIPT' => $validate->errors()
            ];
        }
    }
    
    public static function allocatePasses(){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $prfx = 'allocate-';
        foreach($_POST as $index=>$val){
            $ar = explode($prfx,$index);
            if(count($ar)){
                $_SIGNUP[end($ar)] = $val;
            }
        }
        
        $error_str = '';
        
        $categoryTable = new ParticipantCategory();
        $categoryTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `name`=? || `name`=? || `name`=?",array('Individual-Silver','Individual-Gold','Individual-Platinum',));
        
        global $session_subscriber_data;
        $subscriber_ID = $session_subscriber_data->ID;
        
        if($session_subscriber_data!='Group'){
            SubscriberController::groupAdminLog($subscriber_ID);
        }

        if($categoryTable->count()){
            foreach($categoryTable->data() as $category_data){
                $category_ID = $category_data->ID;
                
                $new_size = @$_SIGNUP[$category_data->name.'_size'];
                
                $categTable = new ParticipantCategory();
                $categTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `ID`=? ORDER BY `type`",array($category_ID));

                $linkexists = true;
                $category_data = $categTable->first();

                $discount = 0;
                $new_plan = firstUC($category_data->type);
                
                $subscategTable = new \SubscriberCategory();
                    
                $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? AND `type`='Allocated' ",array($subscriber_ID,$category_ID));
                if($new_size > 0 && $new_size < 10){
                    if($subscategTable->count()){ 
                        $subscateg_data = $subscategTable->first();
                        $new_size += $subscateg_data->size;
                        $subscategTable->update(array(
                            'size' => $new_size,
                            'discount' => $discount
                        ),$subscateg_data->ID);
                    }else{
                        $subscategTable->insert(array(
                            'subscriber_ID' => $subscriber_ID,
                            'category_ID' => $category_ID,
                            'size' => $new_size,
                            'plan' => $new_plan,
                            'discount' => $discount,
                            'type'=>'Allocated'
                        ));
                    }
                }
            }
        }

        if($diagnoArray[0] == 'ERRORS_FOUND'){
            return (object)[
                'ERRORS'=>true,
                'ERRORS_SCRIPT' => $error_str
            ];
        }else{
            return (object)[
                'ERRORS'=>false,
                'SUCCESS'=>true,
                'ERRORS_SCRIPT' => $validate->errors()
            ];
        }
    }
    
//    public static function updateAllocatedPasses($subscriber_ID){
//      $diagnoArray[0] = 'NO_ERRORS';
//      $validate = new \Validate();
//      $prfx = 'subscateg-';
//      foreach($_POST as $index=>$val){
//          $ar = explode($prfx,$index);
//          if(count($ar)){
//              $_SIGNUP[end($ar)] = $val;
//          }
//      }
//        
//        $error_str = '';
//        
//        $categoryTable = new ParticipantCategory();
//        $categoryTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `name`=? || `name`=? || `name`=?",array('Silver','Gold','Platinum'));
//        
//        global $session_subscriber_data;
//        $subscriber_ID = $session_subscriber_data->ID;
//        
//        if($session_subscriber_data!='Group'){
//            SubscriberController::groupAdminLog($subscriber_ID);
//        }
//
//        if($categoryTable->count()){
//            foreach($categoryTable->data() as $category_data){
//                $category_ID = $category_data->ID;
//                
//                $categTable = new ParticipantCategory();
//                $categTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `ID`=? ORDER BY `type`",array($category_ID));
//                $category_data = $categTable->first();
//
//            // IF DISCOUNT FEATURE NEEDED, INTEGRATE IT HERE /
//                $discount = @$_SIGNUP[$category_data->name.'_discount'];
//                if(is_numeric($discount) && $discount<60){
//                    $discount = (int)$discount;
//                }else{
//                    $discount = 0;
//                }
//            //END IF 
//                
//                $subscategTable = new \SubscriberCategory();
//                    
//                $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? AND `type`='Allocated' ",array($subscriber_ID,$category_ID));
//                if($subscategTable->count()){ 
//                    $subscateg_data = $subscategTable->first();
//                    $subscategTable->update(array(
//                        'discount' => $discount
//                    ),$subscateg_data->ID);
//                }
//            }
//        }
//
//        if($diagnoArray[0] == 'ERRORS_FOUND'){
//            return (object)[
//                'ERRORS'=>true,
//                'ERRORS_SCRIPT' => $error_str
//            ];
//        }else{
//            return (object)[
//                'ERRORS'=>false,
//                'SUCCESS'=>true,
//                'ERRORS_SCRIPT' => $validate->errors()
//            ];
//        }
//    }
}
*/
?>

<?php
class SubscriberCategoryController  
{   
    public static function exists($subscriber_ID,$category_ID){
        $subscategTable = new \SubscriberCategory();
        $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? ",array($subscriber_ID,$category_ID));
        if($subscategTable->count()){
            return true;
        }
        return false;
    }
    public static function logAction($action,$subscateg_ID,$other_data=array()){
        $subscategTable = new \SubscriberCategory();
        if($action=='ticketUsed'){
            $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `ID`=? ",array($subscateg_ID));
            if($subscategTable->count()){
                $subscateg_data = $subscategTable->first();
                $subscategTable->update(array('size_used'=>$subscateg_data->size_used+1),$subscateg_ID);
                
                if(count($other_data)){
                    $subscategInviteTable = new \SubscriberCategoryInvite();
                    $invite_ID = $other_data['invite_ID'];
                    $participant_ID = $other_data['participant_ID'];
                    $subscategInviteTable->update(array('status'=>'Registered','participant_ID'=>$participant_ID),$invite_ID);
                }
            }
        }elseif($action=='ticketSeen'){
            $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `ID`=? ",array($subscateg_ID));
            if($subscategTable->count()){
                $subscateg_data = $subscategTable->first();
                $subscategTable->update(array('seen'=>$subscateg_data->seen+1),$subscateg_ID);
            }
        }
    }
    
    public static function add($subscriber_ID){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $prfx = 'subscateg-';
        foreach($_POST as $index=>$val){
            $ar = explode($prfx,$index);
            if(count($ar)){
                $_SIGNUP[end($ar)] = $val;
            }
        }
        
        $error_str = '';
        
        $categoryTable = new ParticipantCategory();
        $categoryTable->selectQuery("SELECT * FROM `events_participant_category`");
        
        global $subscriber_data;
        
        if($subscriber_data!='Group'){
            SubscriberController::groupAdminLog($subscriber_ID);
        }

        if($categoryTable->count()){
            $i = 0;
            foreach($categoryTable->data() as $category_data){
                $category_ID = $category_data->ID;
                
                $new_size = @$_SIGNUP[$category_data->name.'_size'];
                if(!$new_size){
                    $new_size = 10;
                }
                
                $new_plan = @$_SIGNUP[$category_data->name.'_plan'];
                
                $discount = @$_SIGNUP[$category_data->name.'_discount'];
                if(is_numeric($discount) && $discount<60){
                    $discount = (int)$discount;
                }else{
                    $discount = 0;
                }
                
                $subscategTable = new \SubscriberCategory();
                    
                $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? AND `type`='Assigned' ",array($subscriber_ID,$category_ID));
                
                if(isset($_SIGNUP[$category_data->name])){
                    if($subscategTable->count()){
                        $subscateg_data = $subscategTable->first();
                        if($new_size > $subscateg_data->seen){
                            $subscategTable->update(array(
                                'size' => $new_size,
                                'plan' => $new_plan,
                                'discount' => $discount
                            ),$subscateg_data->ID);
                        }else{
                            $subscategTable->update(array(
                                'size' => $subscateg_data->seen,
                                'plan' => $new_plan,
                                'discount' => $discount
                            ),$subscateg_data->ID);
                        }
                    }else{
                        $subscategTable->insert(array(
                                'subscriber_ID' => $subscriber_ID,
                                'category_ID' => $category_ID,
                                'size' => $new_size,
                                'plan' => $new_plan,
                                'discount' => $discount,
                                'type' => 'Assigned'
                        ));
                    }
                }else{
                    if($subscategTable->count()){
                        $subscateg_data = $subscategTable->first();
                        if($subscateg_data->seen == 0){
                            $subscategTable->delete(array('ID','=',$subscateg_data->ID));
                        }else{
                            $subscategTable->update(array(
                                'size' => $subscateg_data->seen,
                                'plan' => $new_plan,
                                'discount' => $discount
                            ),$subscateg_data->ID);
                        }
                    }
                }
            }
        }

        if($diagnoArray[0] == 'ERRORS_FOUND'){
            return (object)[
                'ERRORS'=>true,
                'ERRORS_SCRIPT' => $error_str
            ];
        }else{
            return (object)[
                'ERRORS'=>false,
                'SUCCESS'=>true,
                'ERRORS_SCRIPT' => $validate->errors()
            ];
        }
    }
    
    public static function allocatePasses(){
        $diagnoArray[0] = 'NO_ERRORS';
        $validate = new \Validate();
        $prfx = 'allocate-';
        foreach($_POST as $index=>$val){
            $ar = explode($prfx,$index);
            if(count($ar)){
                $_SIGNUP[end($ar)] = $val;
            }
        }
        
        $error_str = '';
        
        $categoryTable = new ParticipantCategory();
        $categoryTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `name`=? || `name`=? || `name`=?",array('Silver','Gold','Platinum'));
        
        global $session_subscriber_data;
        $subscriber_ID = $session_subscriber_data->ID;
        
        if($session_subscriber_data!='Group'){
            SubscriberController::groupAdminLog($subscriber_ID);
        }

        if($categoryTable->count()){
            foreach($categoryTable->data() as $category_data){
                $category_ID = $category_data->ID;
                
                $new_size = @$_SIGNUP[$category_data->name.'_size'];
                
                $categTable = new ParticipantCategory();
                $categTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `ID`=? ORDER BY `type`",array($category_ID));

                $linkexists = true;
                $category_data = $categTable->first();

            //* IF DISCOUNT FEATURE NEEDED, INTEGRATE IT HERE *//
                $discount = 0;
                $new_plan = firstUC($category_data->type);
            //END IF *//
                
                $subscategTable = new \SubscriberCategory();
                    
                $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? AND `type`='Allocated' ",array($subscriber_ID,$category_ID));
                if($new_size > 0 && $new_size < 10){
                    if($subscategTable->count()){ 
                        $subscateg_data = $subscategTable->first();
                        $new_size += $subscateg_data->size;
                        $subscategTable->update(array(
                            'size' => $new_size,
                            'discount' => $discount
                        ),$subscateg_data->ID);
                    }else{
                        $subscategTable->insert(array(
                            'subscriber_ID' => $subscriber_ID,
                            'category_ID' => $category_ID,
                            'size' => $new_size,
                            'plan' => $new_plan,
                            'discount' => $discount,
                            'type'=>'Allocated'
                        ));
                    }
                }
            }
        }

        if($diagnoArray[0] == 'ERRORS_FOUND'){
            return (object)[
                'ERRORS'=>true,
                'ERRORS_SCRIPT' => $error_str
            ];
        }else{
            return (object)[
                'ERRORS'=>false,
                'SUCCESS'=>true,
                'ERRORS_SCRIPT' => $validate->errors()
            ];
        }
    }
    
//    public static function updateAllocatedPasses($subscriber_ID){
//      $diagnoArray[0] = 'NO_ERRORS';
//      $validate = new \Validate();
//      $prfx = 'subscateg-';
//      foreach($_POST as $index=>$val){
//          $ar = explode($prfx,$index);
//          if(count($ar)){
//              $_SIGNUP[end($ar)] = $val;
//          }
//      }
//        
//        $error_str = '';
//        
//        $categoryTable = new ParticipantCategory();
//        $categoryTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `name`=? || `name`=? || `name`=?",array('Silver','Gold','Platinum'));
//        
//        global $session_subscriber_data;
//        $subscriber_ID = $session_subscriber_data->ID;
//        
//        if($session_subscriber_data!='Group'){
//            SubscriberController::groupAdminLog($subscriber_ID);
//        }
//
//        if($categoryTable->count()){
//            foreach($categoryTable->data() as $category_data){
//                $category_ID = $category_data->ID;
//                
//                $categTable = new ParticipantCategory();
//                $categTable->selectQuery("SELECT * FROM `events_participant_category` WHERE `ID`=? ORDER BY `type`",array($category_ID));
//                $category_data = $categTable->first();
//
//            //* IF DISCOUNT FEATURE NEEDED, INTEGRATE IT HERE *//
//                $discount = @$_SIGNUP[$category_data->name.'_discount'];
//                if(is_numeric($discount) && $discount<60){
//                    $discount = (int)$discount;
//                }else{
//                    $discount = 0;
//                }
//            //END IF *//
//                
//                $subscategTable = new \SubscriberCategory();
//                    
//                $subscategTable->selectQuery("SELECT* FROM `subscriber_category` WHERE `subscriber_ID`=? AND `category_ID`=? AND `type`='Allocated' ",array($subscriber_ID,$category_ID));
//                if($subscategTable->count()){ 
//                    $subscateg_data = $subscategTable->first();
//                    $subscategTable->update(array(
//                        'discount' => $discount
//                    ),$subscateg_data->ID);
//                }
//            }
//        }
//
//        if($diagnoArray[0] == 'ERRORS_FOUND'){
//            return (object)[
//                'ERRORS'=>true,
//                'ERRORS_SCRIPT' => $error_str
//            ];
//        }else{
//            return (object)[
//                'ERRORS'=>false,
//                'SUCCESS'=>true,
//                'ERRORS_SCRIPT' => $validate->errors()
//            ];
//        }
//    }
}

