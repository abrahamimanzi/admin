<?php
class Functions
{
	public static function json_enc($str){
		return json_encode($str,true);
	}
	
	public static function json_dec($str){
		return json_decode($str,true);
	}
	
	
	public static function print_time_($dt_retr_date){
		$cur_retr_date = Config::get('time/date_time');
		$cur_plit_date = substr($cur_retr_date,5,10);
		$cur_time = substr($cur_retr_date,16,11);
		$cur_day = substr($cur_retr_date,0,3);
		
		$dt_plit_date = substr($dt_retr_date,5,10);
		$dt_time = substr($dt_retr_date,16,11);
		$dt_day = substr($dt_retr_date,0,3);
		
		if($dt_plit_date === $cur_plit_date){
			return '<value title="'.$dt_retr_date.'" style="cursor: pointer">'. 'at '.$dt_time. '</value>';
		}
		if($dt_plit_date < $cur_plit_date){
			return '<value title="'.$dt_retr_date.'" style="cursor: pointer">'. 'on '.$dt_day.' '.$dt_plit_date. '</value>';
		}
		return $dt_retr_date;
	}
	public static function time_past($ref_sec){
		$cur_sec = Config::get('time/timestamp');
		$band = ($cur_sec - $ref_sec)/60;
		$split = explode('.',$band);
		$min = $split[0];
		$sec = $cur_sec - $ref_sec;
		if($band < 1){
			return '<value style="cursor: pointer">'.$sec.'sec ago </value>';
		}elseif($min < 60){
			return '<value style="cursor: pointer">'.$min.'min ago </value>';
		}else{
			$band1 = $min/60;
			$split1 = explode('.',$band1);
			$hour = $split1[0];
			if($hour < 24){
				return '<value style="cursor: pointer">'.$hour.'h ago </value>';
			}else{
				$band2 = $hour/24;
				$split2 = explode('.',$band2); 
				$day = $split2[0];
				if($day < 7){
					return '<value style="cursor: pointer">'.$day.'d ago </value>';
				}else{
					return '<value style="cursor: pointer">'.$day.'d ago </value>';
				}
			}
		}
	}
	
	public static function get_timeago( $ptime )
	{
		$temp = Config::get('time/temp');
		$estimate_time = (double)$temp - (double)$ptime;

		if( $estimate_time < 1 )
		{
			return ' < 1 sec ago';
		}

		$condition = array(
			12 * 30 * 24 * 60 * 60  =>  'year',
			30 * 24 * 60 * 60       =>  'month',
			24 * 60 * 60            =>  'day',
			60 * 60                 =>  'hour',
			60                      =>  'minute',
			1                       =>  'second'
		);

		foreach( $condition as $secs => $str )
		{
			$d = $estimate_time / $secs;

			if( $d >= 1 )
			{
				$r = round( $d );
				return $r . ' ' . $str . ( $r > 1 ? 's' : '' );
			}
		}
	}
	public static function print_F_size($ref_size){
		$size_K = $ref_size/1000;
		if($ref_size < 1000){
			return '<value style="cursor: pointer">'.$ref_size.' B </value>';
		}elseif($ref_size < 1000000){
			return '<value style="cursor: pointer">'.(int)($ref_size/1000).'Kb </value>';
		}elseif($ref_size < 1000000000){
			return '<value style="cursor: pointer">'.(int)($ref_size/1000000).'Mb </value>';
		}elseif($ref_size < 1000000000000){
			return '<value style="cursor: pointer">'.(int)($ref_size/1000000).'Gb </value>';
		}
	}
	public static function getFileIcon($anyfile){
		if($anyfile == 1){
			return 'icon/file_type/img.png';
		}else{
			return 'icon/file_type/file1.png';
		}
	}
	
	
	public static function flashMsg(){
		?>
		<?php 
		$session_exist_success = Session::exists('success');
		if($session_exist_success){
			$session_success = Session::get('success') ;
			if(!empty($session_success)){?>	
					<div class=" alert alert-success" role="alert" onclick="$(this).hide()">
						<div class="row">
							<div class="col-xs-1">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							</div>
							<div class="col-xs-10">
									<?php echo Session::flash('success');?>
								</div>
							<div class="col-xs-1">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								  <span aria-hidden="true">&times;</span>
								</button>
							</div>
						</div>
					</div>
			<?php
			}
		}?>

		<?php 
		$session_exist_errors = Session::exists('errors') ;
		if($session_exist_errors){
			$session_errors = Session::get('errors') ;
			if(!empty($session_errors)){?>	
					<div class=" alert alert-danger" role="alert" onclick="$(this).hide()">
						<div class="row">
							<div class="col-xs-12 col-sm-1">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							</div>
							<div class="col-xs-12 col-sm-10">
								<?php echo Session::flash('errors');?>
							</div>
							<div class="col-xs-12 col-sm-1">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								  <span aria-hidden="true">&times;</span>
								</button>
							</div>
						</div>
					</div>
			<?php
			}
		}?>
		<?php
	}
	
	
	
	public static function sendEmail($user_ID,$from,$to,$subject,$headers,$messageText,$heading){
		$message = '
			<html>
				<body>
					<header style="background-color: #1c77ba; background-size: 100%; padding: 5px; border-radius: 10px; position: relative; margin-left: 10px; margin-right: 10px">
						<span class="position: absolute; left: 200px; bottom: 10px;"><em style=" color: #ddd;">g</em></span>
					</header>
					<div  style="border: 1px solid #dddddd; border-radius: 10px 10px 0px 0px;  padding: 10px; margin-left: 10px; margin-right: 10px">
						
						<section style="border-bottom: 10px solid #1c77ba; padding-bottom: 20px;">
							<header>
								<h2 style="border-bottom: 10px solid #1c77ba; color: #666666;"> '.$subject.' </h2>
							</header>
							<article style="border: 1px solid #dddddd; border-radius: 5px; padding: 5px 10px; background: #ffffff; margin: 5px 10px;">
								<header>
									<h4 style="padding: 2px 0px; margin: 5px 0px; font-size: 15px; font-weight: 600;"> '.$heading.' </h4>
								</header>
								<p>
									'.$messageText.'
								</p>
							</article>
						</section>
						<footer style="background: #eeeeee; font-size: 13px; padding: 5px; color: #666666;">
							<div style="clear: both"></div>
						</footer>
					</div>
				</body>
			</html>
			';
		if(@mail($to,$subject,$message,$headers)){
			return true;
		}else{
			return false;
		}
	}
	
	
	public static function sendEmailToSub($from,$to,$subject,$headers,$messageText,$heading,$logo){
		$message = '
			<html>
				<body style="font-family: helvetica;">
					<header style="background-color: #1c77ba; background-size: 100%; padding: 5px; border-radius: 0px; position: relative; margin-left: 10px; margin-right: 10px">
						<span class="position: absolute; bottom: 10px;"><em style=" color: #eeeeee;"></em></span>
					</header>
					<div style="padding: 10px; margin-left: 10px; margin-right: 10px">
						
						<section style="border-bottom: 8px solid #1c77ba; padding-bottom: 10px;">
							<header style="position: relative"> 
                                '.$heading.'
							</header>
							<article style=" border: 1px solid #dddddd; border-radius: 0px; padding: 5px 10px; background: #ffffff; margin: 10px 20px 5px 20px;">
								<p>
									'.$messageText.'
								</p>
							</article>
						</section>
						<div style="font-size: 12px; padding: 0px; color: #666666; position: relative">
                            <br>
							<!--<center><img src="'.$logo.'" style="width: auto; height: 100px"></center>-->
                            <br>
                            <div style="background: #fff;text-align: left; color: #999; border-top: 1px solid #ddd; padding: 10px 5px">
                                Transform Africa Summit 2018 | enquiries@smartafrica.org 
                            </div>
						</div>
					</div>
				</body>
			</html>
			';
		if(mail($to,$subject,$message,$headers)){
			return true;
		}else{
			return false;
		}
	}
	
	public static function smartMailer($contactDetails,$subject,$message_body,$message_alt){
        
        $from_email = @$contactDetails['from_email'];
        $from_names = @$contactDetails['from_names'];
        $to_email = @$contactDetails['to_email'];
        $cc_email = @$contactDetails['cc_email'];
        $cc_email_1 = @$contactDetails['cc_email_1'];
        $message_attach = @$contactDetails['attach'];
        
        try {
            $mail = new PHPMailer(true); //New instance, with exceptions enabled

            $body   = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
                        <html>
                          <head>
                            <title>Transform Africa Summit 2018</title>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                          </head>
                          '.$message_body.'
                        </html>
                    ';

            $body             = preg_replace('/\\\\/','', $body); //Strip backslashes

            $mail->IsSMTP();                           // tell the class to use SMTP
            $mail->SMTPDebug = 2; 
            $mail->SMTPAuth   = true;                   // enable SMTP authentication
            
            if($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1'){

                $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
                // $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
                $mail->Host = "smtp.gmail.com";
                // $mail->Host = "n3plcpnl0043.prod.ams3.secureserver.net";
                $mail->Port = 465; // or 587
                // $mail->Port = 587; // or 587
                // $mail->Username = "registration@youthconnektafrica.org";
                // $mail->Username = "invitations@youthconnektafrica.org";
                // $mail->Username = "test@test.youthconnektafrica.org";
                // $mail->Username = "youthconnektafrica@gmail.com";
                // $mail->Username = "smartafricasummitteam2@gmail.com";
                // $mail->Username = "smartafricasummitteam@gmail.com";
                $mail->Username = "smartafricateam@gmail.com";
                $mail->Password = "Tas@2018pass";
                // $mail->Password = "Yca@2018pass";
                // $mail->Password = "17_pass";
                // $mail->Password = "InvitationsYCA1";
                // $mail->Password = "registrationYCA17";

            }else{
                $mail->IsSendmail();
            }

            $mail->AddReplyTo($from_email,$from_names);

            $mail->From       = $from_email;
            $mail->FromName   = $from_names;

            $mail->AddAddress($to_email);
            
            if($cc_email){
                $mail->AddCC($cc_email);
            }
            if($cc_email_1){
                $mail->AddCC($cc_email_1);
            }

            $mail->Subject  = $subject;

            if($message_attach != false){
                $mail->AddAttachment($message_attach); // attachment
            }
            
            $mail->AltBody  = $message_alt; // optional, comment out and test
            $mail->WordWrap = 50; // set word wrap

            $mail->MsgHTML($body);

            $mail->IsHTML(true); // send as HTML
            
            $mail->Send();
            
            return true;
        } catch (phpmailerException $e) {
            return $e->errorMessage();
        }
	}
	
	public static function passPrice($categ,$currency){
        
        $str = 'Apr 30 2018';
        $format = 'F d Y';
        $dt = DateTime::createFromFormat($format, $str, timezone_open('Africa/Maputo'));
        $timestamp = $dt->format('U');
        $cur_sec = Config::get('time/seconds');
        if($cur_sec > $timestamp){
            $early_bird = false;
        }else{
            $early_bird = true;
        }
        $amount = 0;
         switch($categ){
            case 'Silver':
			case 'Silver-discounted':

			case 'Individual-Silver':
			case 'Group-Silver':

			case 'Individual-Silver-Discounted':
			case 'Group-Silver-Discounted':
			case 'Exhibitor-Silver-Discounted':
			case 'Sponsor-Silver-Discounted':
			case 'Smart-Africa-Member-Silver-Discounted':
			case 'Face-the-Gorillas-Silver-Discounted':
			case 'Ms-Geek-2018-Silver-Discounted':
                if($currency == "Local"){
                    if($early_bird){
                        $amount = 218750;
                        // $amount = 100;
                    }else{
                        // $amount = 170000;
                        $amount = 218750;
                    }
                }else{
                    if($early_bird){
                        $amount = 250;
                        // $amount = 1;
                    }else{
                        // $amount = 200;
                        $amount = 250;
                    }
                }
            break;
            case 'Gold':
			case 'Gold-discounted':

			case 'Individual-Gold':
			case 'Group-Gold':
			case 'Individual-Gold-Discounted':
			case 'Group-Gold-Discounted':
			case 'Exhibitor-Gold-Discounted':
			case 'Sponsor-Gold-Discounted':
			case 'Face-the-Gorillas-Gold-Discounted':
			case 'Smart-Africa-Member-Gold-Discounted':
			case 'Ms-Geek-2018-Gold-Discounted':
                if($currency == "Local"){
                    if($early_bird){
                        // $amount = 290000;
                        $amount = 393750;
                    }else{
                        // $amount = 425000;
                        $amount = 393750;
                    }
                }else{
                    if($early_bird){
                        $amount = 450;
                    }else{
                        $amount = 450;
                    }
                }
            break;
            case 'Platinum':
			case 'Platinum-discounted':

			case 'Individual-Platinum':
			case 'Group-Platinum':
			case 'Individual-Platinum-Discounted':
			case 'Group-Platinum-Discounted':
			case 'Exhibitor-Platinum-Discounted':
			case 'Sponsor-Platinum-Discounted':
			case 'Smart-Africa-Member-Platinum-Discounted':
			case 'Face-the-Gorillas-Platinum-Discounted':
			case 'Ms-Geek-2018-Platinum-Discounted':
                if($currency == "Local"){
                    if($early_bird){
                        // $amount = 290000;
                        $amount = 831250;
                    }else{
                        // $amount = 425000;
                        $amount = 831250;
                    }
                    // $amount = 768600;
                }else{
                    if($early_bird){
                        $amount = 950;
                    }else{
                        $amount = 950;
                    }
                    // $amount = 900;
                }
            break;
            case 'Individual-Gold':
                if($currency == "Local"){
                    if($early_bird){
                        $amount = 290000;
                    }else{
                        // $amount = 425000;
                        $amount = 290000;
                    }
                }else{
                    if($early_bird){
                        $amount = 350;
                    }else{
                        // $amount = 500;
                        $amount = 350;
                    }
                }
            break;
            case 'Groupsilver':
                if($currency == "Local"){
                    if($early_bird){
                        $amount = 1366400;
                    }else{
                        $amount = 1708000;
                    }
                }else{
                    if($early_bird){
                        $amount = 1600;
                    }else{
                        $amount = 2000;
                    }
                }
            break;
            case 'Groupgold':
                if($currency == "Local"){
                    if($early_bird){
                        $amount = 2732800;
                    }else{
                        $amount = 3416000;
                    }
                }else{
                    if($early_bird){
                        $amount = 3200;
                    }else{
                        $amount = 4000;
                    }
                }
            break;
            case 'Groupplatinum':
                if($currency == "Local"){
                    if($early_bird){
                        $amount = 6490400;
                    }else{
                        $amount = 6490400;
                    }
                }else{
                    if($early_bird){
                        $amount = 7600;
                    }else{
                        $amount = 7600;
                    }
                }
            break;
            case 'Standard':
                if($currency == "Local"){
                    $amount = 3400000;
                }else{
                    $amount = 4000;
                }
            break;
            case 'Premium':
                if($currency == "Local"){
                    $amount = 6375000;
                }else{
                    $amount = 7500;
                }
            break;
        }
        return $amount;
    }
	
	public static function makeCapcha($fieldname){
        $capcha = substr(md5(rand(1000,9999)),0,4);
        ?>
        <div style="height:30px; width: 100%; overflow: hidden; position: relative; background: #fff">
            <span style="top: 3px; width: 100%; text-align: center;position: absolute; font-size: 20px; font-weight: 700; font-family: 'Roboto-Bold'; color: #000"><?=$capcha?></span>
            <img src="<?=DN?>/img/capcha.png" style="opacity: .3; margin-top: -<?=rand(50,120)?>px; margin-left: <?=rand(-200,0)?>px;">
        </div>
        <input type="hidden" name="<?=$fieldname?>" value="<?=$capcha?>" style="width: 0px; height: 0px">
        <?php
    }
	public static function getJobTitles($errorstate,$value='',$categ){
        ?>

        <option value=""> [--Select--] </option>
        <?php
        if($categ == "Media"){
           ?>
            <option value="Media Manager" <?php if($errorstate && $value == 'Media Manager'){ echo 'selected="selected"';}?>>Media Manager</option>

            <option value="Reporter" <?php if($errorstate && $value == 'Reporter'){ echo 'selected="selected"';}?>>Reporter</option>

            <option value="Photographer" <?php if($errorstate && $value == 'Photographer'){ echo 'selected="selected"';}?>>Photographer</option>

            <option value="Writer/Editor" <?php if($errorstate && $value == 'Writer/Editor'){ echo 'selected="selected"';}?>>Writer/Editor</option>

            <option value="Producer/Videographer" <?php if($errorstate && $value == 'Producer/Videographer'){ echo 'selected="selected"';}?>>Producer/Videographer</option> 
            <?php
       } elseif($categ == "Government"){
           ?>
            <option value="Ambassador" <?php if($errorstate && $value == 'Ambassador'){ echo 'selected="selected"';}?>>Ambassador</option>
            <option value="Cabinet Secretary" <?php if($errorstate && $value == 'Cabinet Secretary'){ echo 'selected="selected"';}?>>Cabinet Secretary</option>
            <option value="Chairman" <?php if($errorstate && $value == 'Chairman'){ echo 'selected="selected"';}?>>Chairman</option>
            <option value="Chairperson" <?php if($errorstate && $value == 'Chairperson'){ echo 'selected="selected"';}?>>Chairperson</option>
            <option value="Counsellors" <?php if($errorstate && $value == 'Counsellors'){ echo 'selected="selected"';}?>>Counsellors</option>
            <option value="Country Manager" <?php if($errorstate && $value == 'Country Manager'){ echo 'selected="selected"';}?>>Country Manager</option>
            <option value="Director General" <?php if($errorstate && $value == 'Director General'){ echo 'selected="selected"';}?>>Director General</option>
            <option value="Executive Secretary" <?php if($errorstate && $value == 'Executive Secretar'){ echo 'selected="selected"';}?>>Executive Secretar</option>
            <option value="Governor" <?php if($errorstate && $value == 'Governor'){ echo 'selected="selected"';}?>>Governor</option>
            <option value="High Commissioner" <?php if($errorstate && $value == 'High Commissioner'){ echo 'selected="selected"';}?>>High Commissioner</option>
            <option value="Hon. Consul" <?php if($errorstate && $value == 'Hon. Consul'){ echo 'selected="selected"';}?>>Hon. Consul</option>
            <option value="Mayor" <?php if($errorstate && $value == 'Mayor'){ echo 'selected="selected"';}?>>Mayor</option>
            <option value="Member of Parliament" <?php if($errorstate && $value == 'Member of Parliament'){ echo 'selected="selected"';}?>>Member of Parliament</option>
            <option value="Minister" <?php if($errorstate && $value == 'Minister'){ echo 'selected="selected"';}?>>Minister</option>
            <option value="Minister of state" <?php if($errorstate && $value == 'Minister of state'){ echo 'selected="selected"';}?>>Minister of state</option>
            <option value="National Coordinator" <?php if($errorstate && $value == 'National Coordinator'){ echo 'selected="selected"';}?>>National Coordinator</option>
            <option value="Permanent Secretary" <?php if($errorstate && $value == 'Permanent Secretary'){ echo 'selected="selected"';}?>>Permanent Secretary</option>
            <option value="President" <?php if($errorstate && $value == 'President'){ echo 'selected="selected"';}?>>President</option>
            <option value="Senator" <?php if($errorstate && $value == 'Senator'){ echo 'selected="selected"';}?>>Senator</option>
            <option value="Speaker of an Assembly" <?php if($errorstate && $value == 'Speaker of an Assembly'){ echo 'selected="selected"';}?>>Speaker of an Assembly</option>
            <?php
       } else{?>
            
            <option value="Associate Director" <?php if($errorstate && $value == 'Associate Director'){ echo 'selected="selected"';}?>>Associate Director</option>

            <option value="Board Chairman" <?php if($errorstate && $value == 'Board Chairman'){ echo 'selected="selected"';}?>>Board Chairman</option>

            <option value="Business Analyst" <?php if($errorstate && $value == 'Business Analyst'){ echo 'selected="selected"';}?>>Business Analyst</option>

            <option value="Chairman / Executive Chairman" <?php if($errorstate && $value == 'Chairman / Executive Chairman'){ echo 'selected="selected"';}?>>Chairman / Executive Chairman</option>

            <option value="Chief Executive Officer (CEO)" <?php if($errorstate && $value == 'Chief Executive Officer (CEO)'){ echo 'selected="selected"';}?>>Chief Executive Officer (CEO)</option>

            <option value="Chief Financial Officer (CFO)" <?php if($errorstate && $value == 'Chief Financial Officer (CFO)'){ echo 'selected="selected"';}?>>Chief Financial Officer (CFO)</option>

            <option value="Chief Information Officer (CIO)" <?php if($errorstate && $value == 'Chief Information Officer (CIO'){ echo 'selected="selected"';}?><?php if($errorstate && $value == 'Associate Director'){ echo 'selected="selected"';}?>>Chief Information Officer (CIO)</option>

            <option value="Chief Investment Officer	" <?php if($errorstate && $value == 'Chief Investment Officer'){ echo 'selected="selected"';}?>>Chief Investment Officer</option>

            <option value="Chief Marketing Officer (CMO)" <?php if($errorstate && $value == 'Chief Marketing Officer (CMO)'){ echo 'selected="selected"';}?>>Chief Marketing Officer (CMO)</option>

            <option value="Chief of Staff" <?php if($errorstate && $value == 'Chief of Staff'){ echo 'selected="selected"';}?>>Chief of Staff</option>
            
            <option value="Chief Operating Officer (COO)" <?php if($errorstate && $value == 'Chief Operating Officer (COO)'){ echo 'selected="selected"';}?>>Chief Operating Officer (COO)</option>
            <option value="Chief Technology Officer" <?php if($errorstate && $value == 'Chief Technology Officer'){ echo 'selected="selected"';}?>>Chief Technology Officer</option>
            <option value="Co-Founder" <?php if($errorstate && $value == 'Co-Founder'){ echo 'selected="selected"';}?>>Co-Founder</option>
            <option value="Commissioner"<?php if($errorstate && $value == 'Commissioner'){ echo 'selected="selected"';}?>>Commissioner</option>
            <option value="Consultant / Freelancer" <?php if($errorstate && $value == 'Consultant / Freelancer'){ echo 'selected="selected"';}?>>Consultant / Freelancer</option>
            <option value="Coordinator" <?php if($errorstate && $value == 'Coordinator'){ echo 'selected="selected"';}?>>Coordinator</option>
            <option value="Creative Director" <?php if($errorstate && $value == 'Creative Director'){ echo 'selected="selected"';}?>>Creative Director</option>
            <option value="Deputy CEO" <?php if($errorstate && $value == 'Deputy CEO'){ echo 'selected="selected"';}?>>Deputy CEO</option>
            <option value="Deputy Director" <?php if($errorstate && $value == 'Deputy Director'){ echo 'selected="selected"';}?>>Deputy Director</option>
            <option value="Director" <?php if($errorstate && $value == 'Director'){ echo 'selected="selected"';}?>>Director</option>
            <option value="Director of Operations" <?php if($errorstate && $value == 'Director of Operations'){ echo 'selected="selected"';}?>>Director of Operations</option>
            <option value="Engineer" <?php if($errorstate && $value == 'Engineer'){ echo 'selected="selected"';}?>>Engineer</option>
            <option value="Entrepreneur" <?php if($errorstate && $value == 'Entrepreneur'){ echo 'selected="selected"';}?>>Entrepreneur</option>
            <option value="Executive Director" <?php if($errorstate && $value == 'Executive Director'){ echo 'selected="selected"';}?>>Executive Director</option>
            <option value="Executive President" <?php if($errorstate && $value == 'Executive President'){ echo 'selected="selected"';}?>>Executive President</option>
            <option value="Executive Secretary" <?php if($errorstate && $value == 'Executive Secretary'){ echo 'selected="selected"';}?>>Executive Secretary</option>
            <option value="Financial Advisor / Financial Specialist" <?php if($errorstate && $value == 'Financial Advisor / Financial Specialist'){ echo 'selected="selected"';}?>>Financial Advisor / Financial Specialist</option>
            <option value="Founder" <?php if($errorstate && $value == 'Founder'){ echo 'selected="selected"';}?>>Founder</option>
            <option value="General Manager" <?php if($errorstate && $value == 'General Manager'){ echo 'selected="selected"';}?>>General Manager</option>
            <option value="GIS Specialist" <?php if($errorstate && $value == 'GIS Specialist'){ echo 'selected="selected"';}?>>GIS Specialist</option>
            <option value="ICT Advisor" <?php if($errorstate && $value == 'ICT Advisor'){ echo 'selected="selected"';}?>>ICT Advisor</option>
            <option value="ICT Officer" <?php if($errorstate && $value == 'ICT Officer'){ echo 'selected="selected"';}?>>ICT Officer</option>
            <option value="ICT Specialist" <?php if($errorstate && $value == 'ICT Specialist'){ echo 'selected="selected"';}?>>ICT Specialist</option>
            <option value="ICT Technician" <?php if($errorstate && $value == 'ICT Technician'){ echo 'selected="selected"';}?>>ICT Technician</option>
            <option value="Innovation Manager" <?php if($errorstate && $value == 'Innovation Manager'){ echo 'selected="selected"';}?>>Innovation Manager</option>
            <option value="Innovator" <?php if($errorstate && $value == 'Innovator'){ echo 'selected="selected"';}?>>Innovator</option>
            <option value="Legal Advisor" <?php if($errorstate && $value == 'Legal Advisor'){ echo 'selected="selected"';}?>>Legal Advisor</option>
            <option value="Legal Representative" <?php if($errorstate && $value == 'Legal Representative'){ echo 'selected="selected"';}?>>Legal Representative</option>
            <option value="Manager" <?php if($errorstate && $value == 'Manager'){ echo 'selected="selected"';}?>>Manager</option>
            <option value="Managing Director" <?php if($errorstate && $value == 'Managing Director'){ echo 'selected="selected"';}?>>Managing Director</option>
            <option value="Managing Partner" <?php if($errorstate && $value == 'Managing Partner'){ echo 'selected="selected"';}?>>Managing Partner</option>
            <option value="Marketing Director" <?php if($errorstate && $value == 'Marketing Director'){ echo 'selected="selected"';}?>>Marketing Director</option>
            <option value="Marketing Executive" <?php if($errorstate && $value == 'Marketing Executive'){ echo 'selected="selected"';}?>>Marketing Executive</option>
            <option value="Principal" <?php if($errorstate && $value == 'Principal'){ echo 'selected="selected"';}?>>Principal</option>
            <option value="Professor/Lecturer" <?php if($errorstate && $value == 'Professor/Lecturer'){ echo 'selected="selected"';}?>>Professor/Lecturer</option>
            <option value="Program Analyst" <?php if($errorstate && $value == 'Program Analyst'){ echo 'selected="selected"';}?>>Program Analyst</option>
            <option value="Program Director" <?php if($errorstate && $value == 'Program Director'){ echo 'selected="selected"';}?>>Program Director</option>
            <option value="Public Relations Manager" <?php if($errorstate && $value == 'Public Relations Manager'){ echo 'selected="selected"';}?>>Public Relations Manager</option>
            <option value="Regional Director" <?php if($errorstate && $value == 'Regional Director'){ echo 'selected="selected"';}?>>Regional Director</option>
            <option value="Researcher / Research Assistant" <?php if($errorstate && $value == 'Researcher / Research Assistant'){ echo 'selected="selected"';}?>>Researcher / Research Assistant</option>
            <option value="Sales Executive" <?php if($errorstate && $value == 'Sales Executive'){ echo 'selected="selected"';}?>>Sales Executive</option>
            <option value="Secretary General" <?php if($errorstate && $value == 'Secretary General'){ echo 'selected="selected"';}?>>Secretary General</option>
            <option value="Senior Associate" <?php if($errorstate && $value == 'Senior Associate'){ echo 'selected="selected"';}?>>Senior Associate</option>
            <option value="Senior Executive Vice President" <?php if($errorstate && $value == 'Senior Executive Vice President'){ echo 'selected="selected"';}?>>Senior Executive Vice President</option>
            <option value="Senior Vice President" <?php if($errorstate && $value == 'Senior Vice President'){ echo 'selected="selected"';}?>>Senior Vice President</option>
            <option value="Software Developer" <?php if($errorstate && $value == 'Software Developer'){ echo 'selected="selected"';}?>>Software Developer</option>
            <option value="Student" <?php if($errorstate && $value == 'Student'){ echo 'selected="selected"';}?>>Student</option>
            <option value="Team Leader" <?php if($errorstate && $value == 'Team Leader'){ echo 'selected="selected"';}?>>Team Leader</option>
            <option value="Technical Advisor" <?php if($errorstate && $value == 'Technical Advisor'){ echo 'selected="selected"';}?>>Technical Advisor</option>
            <option value="Technical Director" <?php if($errorstate && $value == 'Technical Director'){ echo 'selected="selected"';}?>>Technical Director</option>
            <option value="Technical Manager" <?php if($errorstate && $value == 'Technical Manager'){ echo 'selected="selected"';}?>>Technical Manager</option>
            <option value="Technology Director" <?php if($errorstate && $value == 'Technology Director'){ echo 'selected="selected"';}?>>Technology Director</option>
            <option value="Vice Chancellor" <?php if($errorstate && $value == 'Vice Chancellor'){ echo 'selected="selected"';}?>>Vice Chancellor</option>
            <option value="Vice President" <?php if($errorstate && $value == 'Vice President'){ echo 'selected="selected"';}?>>Vice President</option>
            <option value="Web Developer" <?php if($errorstate && $value == 'Web Developer'){ echo 'selected="selected"';}?>>Web Developer</option>
               
        <?php
        }  
        ?>
            <option value="Other" <?php if($errorstate && $value == 'Other'){ echo 'selected="selected"';}?>>Other </option>
        <?php
	}
    
    public static function errorPage($number){
        if($number == 404){
			include 'views/errors/404'.P;
        }else{
			include 'views/errors/404'.P;
        }
        echo '<div class="clearfix"></div>';
    }
    
    public static function getStateCol($state){
        switch($state){
            case 'Activate':
            case 'Activated':
                return '#4093D0';
            break; 
            case 'Attend':
            case 'Attended':
                return '#00a65a';
            break;   
            case 'Pending':
                return 'orange';
            break;   
            case 'Deactivate':
            case 'Deactivated':
                return '#999';
            break;    
        }
    }
    
    
    public static function getUserIP(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }
	
    public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode,
                            "latitude" => @$ipdat->geoplugin_latitude,
                            "longitude" => @$ipdat->geoplugin_longitude,
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                    case "latitude":
                        $output = @$ipdat->geoplugin_latitude;
                        break;
                    case "longitude":
                        $output = @$ipdat->geoplugin_longitude;
                        break;
                }
            }
        }
        return $output;
    }
    
    public static function generateStrongPassword($length = 6, $add_dashes = false, $available_sets = 'luds'){
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set){
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if(!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
    
    public static function discount($amount_gross,$discount){
        return $amount_gross-$amount_gross*$discount/100;
    }
	
}
?>