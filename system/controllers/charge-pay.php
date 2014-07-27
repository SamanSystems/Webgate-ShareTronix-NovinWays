<?php

if( !$this->network->id ) {
		$this->redirect('home');
	}
	
	
	if( ! $this->user->is_logged ) {
		$this->redirect('singin');
	}



include_once('helpers/nusoap.php');
$D->error = false;
$D->error_msg = "";
$D->submit = false;
$D->submit_msg = "";
$D->amount = 0;
$D->operator="";
$D->num =0;
//////////////////////////////////////////////////////
$MerchantID = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX'; ///کد دروازه زرین پال//
$no_k = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; /////////کلید novinways//////////////
$no_p = 'XXXXXXXXXXX';////////////رمز novinways//////////
/////////////////////////////////////////////////////////
$ccode= array('mci'=>'*140*# کد شارژ #','mtn'=>'*141* کد شارژ #','talia'=>'*140* کد شارژ #');
$D->tabs = array('main','novin-re','novin-pin','reseller');
if(trim($this->param('tab')) && in_array(trim($this->param('tab')) , $D->tabs)){
$D->tab = trim($this->param('tab'));
}
if(isset($_GET['Authority']) && isset($this->user->sess['CART_AUTH']) && $_GET['Authority'] == $this->user->sess['CART_AUTH'] &&isset($this->user->sess[$_GET['Authority']])){
$trak = isset($result['RefID']) ?$result['RefID']:"";
$sabad = $this->user->sess[$this->user->sess['CART_AUTH']];

if(!isset($sabad['operator']) || !isset($sabad['num']) || !isset($sabad['amount'])&&!isset($sabad['tab']) && !in_array($sabad['tab'],$D->tabs) && $sabad['tab'] !== $D->tab ){
$D->error = true;
$D->error_msg .="خطا در اطلاعات ورودی <br><b> شماره تراکنش را یادداشت کنید : ".$trak.' </b>';

}

if(!$D->error){
$amount = $db2->e($sabad['amount']);
$num = $db2->e($sabad['num']);
$operator = $db2->e($sabad['operator']);
$number = isset($sabad['number']) ? $db2->e($sabad['number']) : '';
$D->tab = $sabad['tab'];



$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
		$client->soap_defencoding = 'UTF-8';
		$result = $client->call('PaymentVerification', array(
															array(
																	'MerchantID'	 => $MerchantID,
																	'Authority' 	 => $this->user->sess['CART_AUTH'],
																	'Amount'	 	 => $amount
																)
															)
		);
		
		







////////////ZA///////////////////


if(($result['Status']) == 100){

unset($this->user->sess[$result['Authority']]);
unset($this->user->sess['CART_AUTH']);
unset($_POST);
$trak = $result['RefID'];
unset($result);
$D->cart_ok = array();
///////////////ZA////////////////




if($D->tab=='main'){
exit;
$q = $db2->query('SELECT * FROM cart_charge WHERE operator="'.$operator.'" AND amount="'.$amount.'" AND status="free" ORDER BY id asc LIMIT '.$num);


while($o = $db2->fetch_object($q)){
$o->trak = $trak;
$D->cart_ok[] = $o; 
$db2->query('INSERT INTO cart_charge_bought SET  user_id="'.$this->user->id.'" ,operator="'.$o->operator.'" ,code="'.$o->code.'" , serial="'.$o->serial.'" ,  amount="'.$o->amount.'" ,  trak="'.$trak.'" , buy_date = "'.time().'"');
$db2->query('UPDATE  cart_charge SET  trak="'.$trak.'" , status="bought" WHERE id="'.$o->id.'" LIMIT 1');
}


$D->submit = true;
$D->submit_msg .= 'کارت های خریداری شده را مشاهده میکنید.<br><b>برای شارژ از کد زیر استفاده کنید<br><p style="direction:ltr;text-align:left;">'.$ccode[$operator].'</p></b>';
do_send_mail($this->user->info->email,'شارژ',$o->operator.'<br>'.$o->code);

}elseif($D->tab=='novin-re'){

$client = new SoapClient('http://novinways.com/services/ChargeBox/wsdl', array('encoding' => 'UTF-8'));
$res = $client->ReCharge(array(
'Auth' => array('WebserviceId' => $no_k, 'WebservicePassword' => $no_p),
'Amount' => $amount,
'Type' => strtoupper($operator),
'Account' => $number,
'ReqId' => $trak
));
if($res->Status == '1000'){
$db2->query('INSERT INTO cart_charge_bought SET  user_id="'.$this->user->id.'" ,operator="'.str_replace('!','',$operator).'" ,code="00000" , serial="00000" ,  amount="'.$amount.'" ,  trak="'.$trak.'" , buy_date = "'.time().'"');
$db2->query('INSERT INTO cart_charge SET   operator="'.str_replace('!','',$operator).'" ,code="00000" , serial="00000" ,  amount="'.$amount.'" ,  trak="'.$trak.'" , status = "bought"');
$D->submit = true;
$D->submit_msg .= 'کد شارژ با تراکنش '.$trak.' برای شماره '.$number.' ارسال شد';
do_send_mail($this->user->info->email,'شارژ','شارژ مستقیم برای '.$number.' انجام شد');
}else{
$D->error = true;
$D->error_msg .= "اشکال در خرید . کد زیر را نگه دارید <br>".$trak; ;
}

}elseif($D->tab=='novin-pin'){

$client = new SoapClient('http://novinways.com/services/ChargeBox/wsdl', array('encoding' => 'UTF-8'));
$res = $client->PinRequest(
array(
'Auth' => array('WebserviceId' => $no_k, 'WebservicePassword' => $no_p),
'Amount' => $amount,
'Type' => strtoupper($operator),
'ReqId' => $trak
));

if($res->Status == '1000'){
$db2->query('INSERT INTO cart_charge_bought SET  user_id="'.$this->user->id.'" ,operator="'.str_replace('!','',$operator).'" ,code="00000" , serial="00000" ,  amount="'.$amount.'" ,  trak="'.$trak.'" , buy_date = "'.time().'"');
$db2->query('INSERT INTO cart_charge SET   operator="'.str_replace('!','',$operator).'" ,code="'.$res->Pin.'" , serial="'.$res->Serial.'" ,  amount="'.$amount.'" ,  trak="'.$trak.'" , status = "bought"');
$id = $db2->insert_id();
$q = $db2->query('SELECT * FROM cart_charge WHERE id="'.$id.'" ');


$o = $db2->fetch_object($q);

$D->cart_ok[0] = $o; 

$D->submit = true;
$D->submit_msg .= 'کارت های خریداری شده را مشاهده میکنید.<br><b>برای شارژ از کد زیر استفاده کنید<br><p style="direction:ltr;text-align:left;">'.$ccode[$o->operator].'</p></b>';

do_send_mail($this->user->info->email,'شارژ',$o->operator.'<br>'.$o->code);

}else{
$D->error = true;
$D->error_msg .= "اشکال در خرید . کد زیر را نگه دارید... <br>".$trak; ;
}



}















}else{
unset($this->user->sess[$result['Authority']]);
unset($this->user->sess['CART_AUTH']);
unset($_POST);
echo $result['Status'];exit;
}











}else{

$D->error = true;
$D->error_msg.="ارسال تقلبی <br> تلاش نکنید زیرا جلسه ها حذف شده اشت";

}
}else{

$D->error = true;
$D->error_msg.="ارسال تقلبی <br> تلاش نکنید زیرا جلسه ها حذف شده اشت";

}
$this->load_template('charge-pay.php');
?>