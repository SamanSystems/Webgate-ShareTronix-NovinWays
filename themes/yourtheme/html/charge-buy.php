<?php
	$this->load_template('header.php');
?>

<script>

function get_num_charge(op){

var am = d.getElementById('amount_'+op).value;
var ad = "";
var req = ajax_init(false);
	if( ! req ) { return; }
	
	req.onreadystatechange	= function() {
		if( req.readyState != 4  ) { return; }
if(d.getElementById('charge_cart_l')){
		
		$('.charge_cart_l').remove();
		}
		if(req.responseText == 0){
		
		alert('این نوع کارت شارژ موجود نیست');
		d.getElementById('num_'+op).innerHTML = '<option value="" name="num" id="num_'+op+am+'"></option>   ';
		return;
		}
		var oi = "";
		for(var i= 1 ; i<=req.responseText ; i++ ){
		
		oi += '<option value="'+i+'" name="num" id="num_'+op+am+'"> '+i+' عدد </option>   ';
		
		}
		
		d.getElementById('num_'+op).innerHTML = oi;
		
	}
	req.open("POST", siteurl+"ajax/get-cch-num/r:"+Math.round(Math.random()*1000), true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	req.send("op="+encodeURIComponent(op)+'&am='+encodeURIComponent(am));
ad = '<div id="charge_cart_l" class="charge_cart_l" style="opacity:0.4;position:absolute;top:0;background:#000 url(\'<?=$C->SITE_URL.'themes/'.$C->THEME.'/imgs/loading.gif'?>\') no-repeat center;z-index:100000;width:100%;height:100%; "></div>';	
$('body').append(ad);


}

function check_buy(){
var r = "";
var am = "";
var n = "";
var r = $('input[name=operator]:checked').val();

if(r !== "mci" && r !== "mtn" && r !== "talia"  ){
alert('نوع کارت شارژ را با تیک زدن انتخاب کنید');
return false;
}
var am = d.getElementById('amount_'+r).value;
var n = d.getElementById('num_'+r).value;
if( am == 0 ){
alert('قیمتی انتخاب کنید');
return false;
}
if( n == "" ){
alert('تعدادی انتخاب کنید\nشاید کارت موجود نباشد');
return false;
}
var w = "";

switch(r){
case 'mci': w = "همره اول";break;
case 'mtn': w = "ایرانسل";break;
case 'talia': w = "تالیا";break;

}
var am = d.getElementById('amount_'+r).value;
var n = d.getElementById('num_'+r).value;
var q = confirm('کارت شارژ '+w+' - '+am+' تومانی - '+n+' عدد \n برای ادامه اطمینان دارید؟ ');
if(!q){
return false;
}
return true;
}

</script>
<? if($D->error){?>
						<?= errorbox('خطا',$D->error_msg)?>
						<?}?>
							<? if($D->submit){?>
						<?= okbox('انجام شد',$D->submit_msg)?>
						<?}?>
						
						<div class="htabs" style="margin-bottom:6px; margin-top:0px;">
							<strong>امکانات</strong>
							<a href="<?= $C->SITE_URL ?>charge-buy/tab:main" class="<?= $D->tab=='main'?'onhtab':'' ?>"><b>کد شارژ 1</b></a>
							<a href="<?= $C->SITE_URL ?>charge-buy/tab:novin-pin" class="<?= $D->tab=='novin-pin'?'onhtab':'' ?>"><b>کد شارژ 2</b></a>
							<a href="<?= $C->SITE_URL ?>charge-buy/tab:reseller" class="<?= $D->tab=='reseller'?'onhtab':'' ?>"><b>کد شارژ 3</b></a>
							<a href="<?= $C->SITE_URL ?>charge-buy/tab:novin-re" class="<?= $D->tab=='novin-re'?'onhtab':'' ?>"><b>شارژ مستقیم</b></a>
						</div>		
						
						
						
<form action="" method="post" name="charge">
<div style="background:#f4f4f4;border:solid 1px #dddddd;padding:5px;-moz-border-radius:5px;border-radius:5px;-webkit-border-radius:5px" >
<? foreach($D->carts as $ch) { 
$rang = '#fff';
switch($ch){
case 'mtn' : $rang = '#ffe28b'; break;
case 'mci' : $rang = '#97ffaf'; break;
case 'talia' : $rang = '#ffd9b8'; break;
}
?>

<table style="width:99%;border:solid 1px #c5c5c5;background:<?=$rang?> ;-moz-border-radius:5px;border-radius:5px;-webkit-border-radius:5px" id="setform" cellspacing="5">


<tr>
<td><img src="<?= $C->SITE_URL.'themes/'.$C->THEME.'/imgs/charge_img_'.$ch.'.gif'?>" />
<input <?= $D->operator == $ch ? 'checked="checked"' : '' ?> type="radio" name="operator" id="operator_<?=$ch?>" value="<?=$ch?>" />
</td>
<td class="setparam">قیمت</td>
<td>
<select  name="amount_<?=$ch?>" id="amount_<?=$ch?>" class="setselect" style="width:120px;">

<option <?= $D->amount == 0 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="0" ></option>
<option <?= $D->amount == 1000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="1000" >1000 تومانی</option>
<option <?= $D->amount == 2000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="2000" >2000 تومانی</option>
<option <?= $D->amount == 5000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="5000" >5000 تومانی</option>
<option <?= $D->amount == 10000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="10000" >10000 تومانی</option>
<option <?= $D->amount == 20000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="20000" >20000 تومانی</option>

</select>
</td>
<td class="setparam">تعداد</td>
<td>
<select  name="num_<?=$ch?>" id="num_<?=$ch?>" class="setselect" style="width:120px;">





</select>
</td>

<td style="width:100px;" id="how_<?=$ch?>">

</td>


</tr>
</table>
<br>




<? } ?>
<center>
<input onclick="return check_buy()" type="submit" value="خرید شارژ" name="buy" />
</center>
</div>
</form>

<?php
	$this->load_template('footer.php');
?>