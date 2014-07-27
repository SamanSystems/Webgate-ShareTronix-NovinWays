<?php

	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	
	
	$D->submit = false;
	$D->submit_msg = "";
$D->tabs = array("add","edit","carts","search");
$D->pg = 1;
$D->pg = intval($this->param("pg")) ? $this->param("pg") : 1;	
$D->carts_tabs = array("all","bought","free");	
$D->search_tabs = array("by_code","by_serial","by_operator","by_amount","by_trak","by_user");	
$D->tab = "add";
$D->operator = "all";


$D->tab = trim($this->param("tab")) ? $this->param("tab") : "add";	
if(!in_array($D->tab,$D->tabs)){
$this->redirect('admin/charge');
}	
$D->sub_tab = "";
if($D->tab == "edit"){
$D->sub_tab = trim($this->param("sub-tab")) ? $this->param("sub-tab") : "bought";
if(!in_array($D->sub_tab,$D->carts_tabs)){
$this->redirect('admin/charge');
}	
}
$D->operator = "";
if($D->tab == "carts"){
$D->sub_tab = trim($this->param("sub-tab")) ? $this->param("sub-tab") : "all";
$D->operator = trim($this->param("operator")) ? $this->param("operator") : "all";
if(!in_array($D->sub_tab,$D->carts_tabs)){
$this->redirect('admin/charge');
}	

$D->carts = $this->network->get_cart_charges($D->operator,$D->sub_tab,$D->pg,$D->num_results,$D->num_pages);
$D->paging_url = $C->SITE_URL.'admin/charge/tab:'.$D->tab.'/sub-tab:'.$D->sub_tab.'/operator:'.$D->operator.'/pg:';


}	
if($D->tab == "search"){
$D->sub_tab = trim($this->param("sub-tab")) ? $this->param("sub-tab") : "bought";
if(!in_array($D->sub_tab,$D->carts_tabs)){
$this->redirect('admin/charge');
}	
}		
/////////////////////////////////////////////////////
if($D->tab == "add"){
$ok = false;
 $i = 0;
if(isset($_POST['add'])){
for($i=0 ; count($_POST['sh']) > $i ; $i++){
$D->error = false;
if(isset($_POST['add'])){
if(!$_POST['code'][$i] || !(int)$_POST['code'][$i] ){
$D->error = true;
$D->error_msg .= "کد شارژ وارد نشده است یا صحیح نیست . برای ردیف ". ($i+1).'<br>';

}
if(($_POST['code'][$i] && $this->network->get_cch_by_code($_POST['code'][$i],$_POST['operator'][$i])) ){
$D->error = true;
$D->error_msg .= "کارت شارژ تکراری برای کد  ". ($_POST['code'][$i]).'<br>';
unset($_POST['code'][$i]);
}

if(!$D->error){
$db2->query('INSERT INTO cart_charge SET code="'.$db2->e($_POST['code'][$i]).'" , serial="'.$db2->e($_POST['serial'][$i]).'" , operator="'.$db2->e($_POST['operator'][$i]).'" , amount="'.$db2->e($_POST['amount'][$i]).'" ,status="free"  ');

$ok = true;

}




}

}
if($ok){
$D->submit = true;
$D->submit_msg = $i." کارت اضافه شد";
}
}
}

if($D->tab == "search"){
$D->carts_searched = array();
$D->by_code = $this->param("by_code") ? $db2->e($this->param("by_code")) : '';
$D->by_serial =$this->param("by_serial") ? $db2->e($this->param("by_serial")) : '';
$D->by_operator = $this->param("by_operator") ? $db2->e($this->param("by_operator")) : '';
$D->by_amount = $this->param("by_amount") ? $db2->e($this->param("by_amount")) : '';
$D->by_trak = $this->param("by_trak") ? $db2->e($this->param("by_trak")) : '';
$D->by_user = $this->param("by_user") ? $db2->e($this->param("by_user")) : '';
$i = false;
$w = "";
if($D->by_code){

$w .= " code='".$D->by_code."' ";
$i = true;
}

if($D->by_serial){

$w .= ($i ? ' AND ' : '')." serial='".$D->by_serial."' ";
$i = true;
}

if($D->by_operator){

$w .= ($i ? ' AND ' : '')." operator='".$D->by_operator."' ";
$i = true;
}

if($D->by_amount){

$w .= ($i ? ' AND ' : '')." amount='".$D->by_amount."' ";
$i = true;
}

if($D->by_trak){

$w .= ($i ? ' AND ' : '')." trak='".$D->by_trak."' ";
$i = true;
}
if($D->by_user){
$w = ($i ? $w : '1');
$i = true;
}

if($i){

$q = $db2->query('SELECT * FROM cart_charge WHERE '.$w.' ORDER BY id DESC ');


while($o = $db2->fetch_object($q)){
if($D->by_user){
if($db2->fetch_field('SELECT COUNT(id) FROM cart_charge_bought WHERE code="'.$o->code.'" AND operator="'.$o->operator.'" AND user_id="'.$this->network->get_user_by_username($D->by_user)->id .'" ') == 0){
continue;
}
}
$D->carts_searched[] = $o;

}
if(!empty($D->carts_searched)>0){
$D->submit = true;
$D->submit_msg .= count($D->carts_searched)." نتیجه پیدا شد.";
}else{
$D->error = true;
$D->error_msg .= "جستجوی بدون نتیجه ... !";
}

}






if(isset($_POST['search'])){
$by_code = $_POST["by_code"] ? 'by_code:'.$db2->e($_POST["by_code"]).'/' : '';
$by_serial =$_POST["by_serial"] ? 'by_serial:'.$db2->e($_POST["by_serial"]).'/' : '';
$by_operator = $_POST["by_operator"] ? 'by_operator:'.$db2->e($_POST["by_operator"]).'/' : '';
$by_amount = $_POST["by_amount"] ? 'by_amount:'.$db2->e($_POST["by_amount"]).'/' : '';
$by_trak = $_POST["by_trak"] ? 'by_trak:'.$db2->e($_POST["by_trak"]).'/' : '';
$by_user = $_POST["by_user"] ? 'by_user:'.$db2->e($_POST["by_user"]).'/' : '';



$this->redirect('admin/charge/tab:search/'.$by_code.$by_serial.$by_operator.$by_amount.$by_trak.$by_user);


}



}

if($D->tab == "edit"){
$D->o = false;
if(!intval($this->param('id')) || !($o = $this->network->get_cch_by_id($this->param('id')))){
$D->error = true;
$D->error_msg .= "مورد پیدا نشد...";

}

if(!$D->error){
$D->o = $o;

if(isset($_POST['edit'])){
$id = intval($_POST['id']);

$db2->query('UPDATE cart_charge SET code="'.$db2->e($_POST['code']).'" , serial="'.$db2->e($_POST['serial']).'" , operator="'.$db2->e($_POST['operator']).'" , amount="'.$db2->e($_POST['amount']).'" ,status="'.$db2->e($_POST['status']).'" WHERE id="'.$db2->e($_POST['id']).'" LIMIT 1 ');
$D->o = $this->network->get_cch_by_id($o->id);
$D->submit = true;
$D->submit_msg .= "کارت شارژ ویرایش شد"; 
}



}




}


	
$this->load_template('admin_charge.php');
	
?>