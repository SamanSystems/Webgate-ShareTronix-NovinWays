<?php

if( !$this->network->id ) {
		$this->redirect('home');
	}
	
	
	if( ! $this->user->is_logged ) {
		$this->redirect('singin');
	}


include_once('helpers/nusoap.php');
$D->carts = array('mci','mtn','talia');
$D->error = false;
$D->error_msg = "";
$D->submit = false;
$D->submit_msg = "";
$D->amount = 0;
$D->number="";
$D->operator="";
$D->num =0;
$D->tabs = array('main','novin-re','novin-pin','reseller');
//////////////////////////////////////////////////////
$MerchantID = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX'; ///کد دروازه زرین پال//
$no_k = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; /////////کلید novinways//////////////
$no_p = 'XXXXXXXXXXX';////////////رمز novinways//////////
/////////////////////////////////////////////////////////

$D->tab= 'novin-re';

if(trim($this->param('tab')) && in_array(trim($this->param('tab')) , $D->tabs)){
$D->tab = trim($this->param('tab'));
}

if($D->tab == 'main'){
$this->redirect('charge-buy');
exit;
$D->carts = $op_ar = array('mci','mtn','talia');
if(isset($_POST['buy'])){
$this->user->sess['cart_array'] = array();
$D->carts = $op_ar = array('mci','mtn','talia');
$am_ar = array(1000,2000,5000,10000,20000);

if(!isset($_POST['operator']) || !in_array($_POST['operator'],$op_ar) || !isset($_POST['amount_'.$_POST['operator']]) || !in_array($_POST['amount_'.$_POST['operator']],$am_ar) || !isset($_POST['num_'.$_POST['operator']]) || !($_POST['num_'.$_POST['operator']] > 1) ){
$D->error = true;
$D->error_msg .= "اطلاعات ناقص است.";
}

if($this->network->get_cch_for_buy($_POST['operator'],intval($_POST['amount_'.$_POST['operator']]) == 0 )){
$D->error = true;
$D->error_msg .= "این تعداد کارت موجود نیست.";
}


if(!$D->error){
 
$D->operator = $_POST['operator'];
$D->num = intval($_POST['num_'.$_POST['operator']]);
$D->amount = intval( $_POST['amount_'.$_POST['operator']]  ) * $D->num ;
$cart_array = array('operator'=>$D->operator,'amount'=>$D->amount,'num'=>$D->num,'tab'=>'main');
$this->user->sess['cart_array'] = $cart_array ;
 	
	$Description = 'خرید شارژ - '.$C->SITE_TITLE;  // Required
	$Email = $this->user->info->email; // Optional
	$Mobile =''; // Optional
	$CallbackURL = $C->SITE_URL.'charge-pay/tab:main'; // Required
	
	
	// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
	$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
	$client->soap_defencoding = 'UTF-8';
    $result = $client->call('PaymentRequest', array(
													array(
															'MerchantID' 	=> $MerchantID,
															'Amount' 		=> $D->amount,
															'Description' 	=> $Description,
															'Email' 		=> $Email,
															'Mobile' 		=> $Mobile,
															'CallbackURL' 	=> $CallbackURL
														)
													)
	);

	if($result['Status'] == 100)
	{
	$this->user->sess['CART_AUTH'] = $result['Authority'];
$this->user->sess[$result['Authority']] = $this->user->sess['cart_array'];
unset($this->user->sess['cart_array']);
		$this->redirect('https://www.zarinpal.com/pg/StartPay/'.$result['Authority']);
		
	} else {
	unset($this->user->sess[$result['Authority']]);
	unset($this->user->sess['cart_array']);
	unset($this->user->sess['CART_AUTH']);
		echo'ERR: '.$result['Status'];
		exit;
	}
	
	



}


}
$this->load_template('charge-buy.php');

}elseif($D->tab == 'novin-re'){

$D->carts = $op_ar = array('mci','mtn','!mtn');

if(isset($_POST['buy'])){

$D->carts = $op_ar = array('mci','mtn','!mtn');
$am_ar = array(1000,2000,5000,10000,20000);
$n_e = array('-11'=>'اطلاعات ارسالی ناقص است','-22'=>'احراز هویت نماینده اشتباه است','-33'=>'اعتبار نماینده کم است','-44'=>'مبلغ درخواستی بالاست','1000'=>'موفقیت آمیز بود');


if(!isset($_POST['operator']) || !isset($_POST['number']) || substr(trim($_POST['number']),0,1) <> 0 || strlen($_POST['number']) <> 11 || !in_array($_POST['operator'],$op_ar) || !isset($_POST['amount_'.$_POST['operator']]) || !in_array($_POST['amount_'.$_POST['operator']],$am_ar) ){
$D->error = true;
$D->error_msg .= "اطلاعات ناقص است.";
}
$client = new SoapClient('http://novinways.com/services/ChargeBox/wsdl', array('encoding' => 'UTF-8'));
$res = $client->CheckCredit (array(
'Auth' =>array('WebserviceId' => $no_k,'WebservicePassword' => $no_p)
)
);

if($res->Status=='-11' || $res->Status=='-22' ){
$D->error = true;
$D->error_msg .= $n_e[$res->Status];
}

if($res->Credit < intval( $_POST['amount_'.$_POST['operator']]  )  ){
$D->error = true;
$D->error_msg .= $n_e['-33'];
}

if(!$D->error){
 
$D->operator = $_POST['operator'];
$D->number = $_POST['number'];

$D->amount = intval( $_POST['amount_'.$_POST['operator']]  )  ;
$cart_array = array('num'=>1 , 'number'=>$D->number,'operator'=>$D->operator,'amount'=>$D->amount,'tab'=>'novin-re');
$this->user->sess['cart_array'] = $cart_array ;

	$Description = 'خرید شارژ - '.$C->SITE_TITLE;  // Required
	$Email = $this->user->info->email; // Optional
	$Mobile =''; // Optional
	$CallbackURL = $C->SITE_URL.'charge-pay/tab:novin-re/'; // Required
	
	
	// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
	$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
	$client->soap_defencoding = 'UTF-8';
    $result = $client->call('PaymentRequest', array(
													array(
															'MerchantID' 	=> $MerchantID,
															'Amount' 		=> $D->amount,
															'Description' 	=> $Description,
															'Email' 		=> $Email,
															'Mobile' 		=> $Mobile,
															'CallbackURL' 	=> $CallbackURL
														)
													)
	);

	if($result['Status'] == 100)
	{
	$this->user->sess['CART_AUTH'] = $result['Authority'];
$this->user->sess[$result['Authority']] = $this->user->sess['cart_array'];
unset($this->user->sess['cart_array']);
		$this->redirect('https://www.zarinpal.com/pg/StartPay/'.$result['Authority']);
		
	} else {
	unset($this->user->sess[$result['Authority']]);
	unset($this->user->sess['cart_array']);
	unset($this->user->sess['CART_AUTH']);
		echo'ERR: '.$result['Status'];
		exit;
	}




}

	
	


}


$this->load_template('charge-buy-novin.php');





}elseif($D->tab == 'novin-pin'){

$D->carts = $op_ar = array('mci','mtn','talia','rightel');
if(isset($_POST['buy'])){

$D->carts = $op_ar = array('mci','mtn','talia','rightel');
$am_ar = array(1000,2000,5000,10000,20000);
$n_e = array('-11'=>'اطلاعات ارسالی ناقص است','-22'=>'احراز هویت نماینده اشتباه است','-33'=>'اعتبار نماینده کم است','-44'=>'مبلغ درخواستی بالاست','1000'=>'موفقیت آمیز بود');

if(!isset($_POST['operator']) || !in_array($_POST['operator'],$op_ar) || !isset($_POST['amount_'.$_POST['operator']]) || !in_array($_POST['amount_'.$_POST['operator']],$am_ar) ){
$D->error = true;
$D->error_msg .= "اطلاعات ناقص است.";
}
$client = new SoapClient('http://novinways.com/services/ChargeBox/wsdl', array('encoding' => 'UTF-8'));
$res = $client->CheckCredit (array(
'Auth' =>array('WebserviceId' => $no_k,'WebservicePassword' => $no_p)
)
);

if($res->Status=='-11' || $res->Status=='-22' ){
$D->error = true;
$D->error_msg .= $n_e[$res->Status];
}

if($res->Credit < intval( $_POST['amount_'.$_POST['operator']]  )  ){
//$D->error = true;
$D->error_msg .= $n_e['-33'];
}

if(!$D->error){
 
$D->operator = $_POST['operator'];

$D->amount = intval( $_POST['amount_'.$_POST['operator']]  )  ;
$cart_array = array('num'=>1,'operator'=>$D->operator,'amount'=>$D->amount,'tab'=>'novin-pin');
$this->user->sess['cart_array'] = $cart_array ;

	$Description = 'خرید شارژ - '.$C->SITE_TITLE;  // Required
	$Email = $this->user->info->email; // Optional
	$Mobile =''; // Optional
	$CallbackURL = $C->SITE_URL.'charge-pay/tab:novin-pin/'; // Required
	
	
	// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
	$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
	$client->soap_defencoding = 'UTF-8';
    $result = $client->call('PaymentRequest', array(
													array(
															'MerchantID' 	=> $MerchantID,
															'Amount' 		=> $D->amount,
															'Description' 	=> $Description,
															'Email' 		=> $Email,
															'Mobile' 		=> $Mobile,
															'CallbackURL' 	=> $CallbackURL
														)
													)
	);

	if($result['Status'] == 100)
	{
	$this->user->sess['CART_AUTH'] = $result['Authority'];
$this->user->sess[$result['Authority']] = $this->user->sess['cart_array'];
unset($this->user->sess['cart_array']);
		$this->redirect('https://www.zarinpal.com/pg/StartPay/'.$result['Authority']);
		
	} else {
	unset($this->user->sess[$result['Authority']]);
	unset($this->user->sess['cart_array']);
	unset($this->user->sess['CART_AUTH']);
		echo'ERR: '.$result['Status'];
		exit;
	}




}

	
	


}


$this->load_template('charge-buy-novin.php');





}elseif($D->tab == 'reseller'){


$this->load_template('charge-buy-reseller.php');

}











?>