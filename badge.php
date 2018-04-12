<?php

  require_once('core/init.php'); 
  require_once('app/controller.php'); 
  include('php-barcode.php');
  require('fpdf.php');
  
  // -------------------------------------------------- //
  //                      USEFUL
  // -------------------------------------------------- //
  
  class eFPDF extends FPDF{
    // Page header
    // function Header()
    // {
    //     $this->Image('img/tas-logo.png',30,40,150);
    //     // Arial bold 15
    //     $this->SetFont('Arial','B',15);
        
    //     $this->SetFont('Arial','',10);
    //     $this->Cell(0,15,'Smart Africa Secretariant',0,0,'R');
    //     $this->Cell(0,40,'Makuza Peace Plaza 9th Floor, Block C',0,0,'R');
    //     $this->Cell(0,65,'10 KN4 Avenue, Kigali, Rwanda',0,0,'R');
    //     $this->Cell(0,95,' www.transformafricasummit.org',0,0,'R');
    //     $this->Cell(0,125,'Tel: (+250) 732-301-014',0,0,'R');
    //     $this->Cell(0,150,'Email: enquiries@smartafrica.org',0,0,'R');
    //     // Move to the right
    //     $this->Cell(80);
    //     // Line break
    //     $this->Ln(90);
    //     $this->SetDrawColor(244,120,34);
    //     $this->Cell(0,0,'',1,1,'L');
    //     $this->Ln(30);
    // }
    function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
    {
        $font_angle+=90+$txt_angle;
        $txt_angle*=M_PI/180;
        $font_angle*=M_PI/180;
    
        $txt_dx=cos($txt_angle);
        $txt_dy=sin($txt_angle);
        $font_dx=cos($font_angle);
        $font_dy=sin($font_angle);
    
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        if ($this->ColorFlag)
            $s='q '.$this->TextColor.' '.$s.' Q';
        $this->_out($s);
    }
//             Page footer
    function Footer(){
        // Position at 1.5 cm from bottom
        // $this->Image('/img/holder_email.jpg',0,720,600);
        
        $this->SetY(-40);
        $this->SetFont('Arial','',40);
        // Page number
        $this->Cell(0,10,'Platinum',0,0,'C');
    }
  }
        
  // -------------------------------------------------- //
  //                  PROPERTIES
  // -------------------------------------------------- //
  
  $fontSize = 10;
  $marge    = 10;   // between barcode and hri in pixel
  $x        = 300;  // barcode center
  $y        = 650;  // barcode center
  $height   = 90;   // barcode height in 1D ; module size in 2D
  $width    = 5;    // barcode height in 1D ; not use in 2D
  $angle    = 0;   // rotation in degrees

      $host_ID=(Input::get('id','get'));
      $hostTable = new Participant();
      $hostTable->selectQuery("SELECT * FROM `events_participant` WHERE `ID`=? LIMIT 1",array($host_ID));
      $host_data = $hostTable->first();

  // $profilePhoto ='http://localhost/qrcode/php/sample%20php/img/toure.jpg';
  // $profilePhoto =DNADMIN.'/data_user/participants/gravatar.jpg';
  $profilePhoto =DNADMIN.'/data_user/participants/'.$host_data->code.'.jpg';
  // $profilePhoto =DNADMIN.'/data_user/participants/TAS-GOV-000087.jpg';
  // $logo = 'http://localhost/qrcode/php/sample%20php/img/rdb.jpg';
  $logo =DNADMIN.'/icon/Youth-Connekt-Logo-01.jpg';
  $passColor = DNADMIN.'/icon/green.jpg';
  // $backgroudPhoto=DNADMIN.'/icon/TAS-Badge-08.jpg';
  $backgroudPhoto=DNADMIN.'/icon/yk-card-01.jpg';
  // $participantName='John Doe';
  $participantName=$host_data->firstname.' '.$host_data->lastname.' '.$host_data->othername;
  
  // $code     = '123456789012'; // barcode, of course ;)
  // $code     = '000000000087'; // barcode, of course ;)

  $cur_digit = strlen($host_ID);
  $total_digit = 12;
  $code_string = $host_ID;

  if($cur_digit < $total_digit){
      while(strlen($code_string) < $total_digit){
          $code_string = '0'.$code_string;
      }
  }
  $code     = ($code_string); // barcode, of course ;)
  // $code     = ($host_ID); // barcode, of course ;)

  $type     = 'ean13';
  // $type     = 'pdf417';
  // $type     = 'int25';
  $black    = '000000'; // color in hexa
  
  // -------------------------------------------------- //
  //            ALLOCATE FPDF RESSOURCE
  // -------------------------------------------------- //
    
  $pdf = new eFPDF('P', 'pt');

        // $pdf = new eFPDF('P','pt','a4');

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true,120);
        // $pdf->Image('img/toure.jpg',0,0,300);

        $pdf->Image($backgroudPhoto, 0, 0, 595.28, 841.89);
        $pdf->Image($passColor, 0, 0, 595.28, 20);
        $pdf->Image($passColor, 0, 761.89, 595.28, 80);
        $pdf->Image($logo,150,40,300);

        $pdf->SetY(560); 
        $pdf->SetFont('Arial','',40);


        $pdf->Image($profilePhoto,150,185,300);
        $pdf->Cell(0,0,$participantName,0,0,'C');


        // $pdf->Image('img/toure.jpg',195,400,-300);
  // $pdf->AddPage();
  
  // -------------------------------------------------- //
  //                      BARCODE
  // -------------------------------------------------- //
  
  $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
  
  // -------------------------------------------------- //
  //                      HRI
  // -------------------------------------------------- //
  
  $pdf->SetFont('Arial','B',$fontSize);
  $pdf->SetTextColor(0, 0, 0);
  $len = $pdf->GetStringWidth($data['hri']);
  Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
  // $pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);


  $pdf->Output();
?>