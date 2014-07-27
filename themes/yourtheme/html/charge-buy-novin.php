<?php
	$this->load_template('header.php');
?>

<script>


function check_buy(){
var r = "";
var am = "";
var n = "";
var r = $('input[name=operator]:checked').val();

if(r !== "mci" && r !== "mtn" && r !== "talia" && r !== "rightel" && r !== "!mtn"  ){
alert('نوع کارت شارژ را با تیک زدن انتخاب کنید');
return false;
}

var am = d.getElementById('amount_'+r).value;

var q = confirm('کارت شارژ '+w+' - '+am+' تومانی  \n برای ادامه اطمینان دارید؟ ');
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
							
							<a href="<?= $C->SITE_URL ?>charge-buy/tab:novin-pin" class="<?= $D->tab=='novin-pin'?'onhtab':'' ?>"><b>کد شارژ 1</b></a>
							<a href="<?= $C->SITE_URL ?>charge-buy/tab:reseller" class="<?= $D->tab=='reseller'?'onhtab':'' ?>"><b>کد شارژ 2</b></a>
							<a href="<?= $C->SITE_URL ?>charge-buy/tab:novin-re" class="<?= $D->tab=='novin-re'?'onhtab':'' ?>"><b>شارژ مستقیم</b></a>
						</div>	
						
<form action="<?= $C->SITE_URL.'charge-buy/tab:'.$D->tab?>" method="post" name="charge">
<div style="background:#f4f4f4;border:solid 1px #dddddd;padding:5px;-moz-border-radius:5px;border-radius:5px;-webkit-border-radius:5px" >

<? foreach($D->carts as $ch) { if( $D->tab=='novin-re' && ($ch=="talia" || $ch=="rightel" )){continue;}
$rang = '#fff';
switch($ch){
case 'mtn' : $rang = '#ffe28b'; break;
case 'mci' : $rang = '#97ffaf'; break;
case 'talia' : $rang = '#ffd9b8'; break;
case 'rightel' : $rang = '#e9c5f0'; break;
case '!mtn' : $rang = '#ffe28b'; break;
}
?>

<table style="width:99%;border:solid 1px #c5c5c5;background:<?=$rang?> ;-moz-border-radius:5px;border-radius:5px;-webkit-border-radius:5px" id="setform" cellspacing="5">


<tr>
<td><img src="<?= $C->SITE_URL.'themes/'.$C->THEME.'/imgs/charge_img_'.$ch.'.gif'?>" />

<? if($D->tab == 'novin-re' && $ch == '!mtn'){ ?>
<b style="color:red">شگفت انگیز</b><br>
<?} ?>
<input <?= $D->operator == $ch ? 'checked="checked"' : '' ?> type="radio" name="operator" id="operator_<?=$ch?>" value="<?=$ch?>" />


</td>
<td class="setparam">قیمت</td>
<td>
<select  name="amount_<?=$ch?>" id="amount_<?=$ch?>" class="setselect" style="width:120px;">

<option <?= $D->amount == 0 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="0" ></option>
<option <?= $ch=='rightel' || $ch=='talia' ? 'disabled': '' ?> <?= $D->amount == 1000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="1000" >1000 تومانی</option>
<option <?= $D->amount == 2000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="2000" >2000 تومانی</option>
<option <?= $D->amount == 5000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="5000" >5000 تومانی</option>
<option <?= $D->amount == 10000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="10000" >10000 تومانی</option>
<option <?= $D->amount == 20000 ? 'selected="selected"' : '' ?> onclick="get_num_charge('<?=$ch?>')" value="20000" >20000 تومانی</option>

</select>
</td>



<td style="width:100px;" id="how_<?=$ch?>">

</td>


</tr>
</table>
<br>




<? } ?>
<? if($D->tab == 'novin-re'){ ?>
<center>
شماره موبایل جهت شارژ<input  type="text" value="<?=$D->number?>" name="number" />
</center>
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