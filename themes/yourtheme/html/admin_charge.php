<?php
	
	$this->load_template('header.php');
	
?>
<link rel="stylesheet" type="text/css" media="screen" href="<?= $C->SITE_URL?>themes/<?=$C->THEME?>/css/css-table.css" />
<script type="text/javascript" src="<?= $C->SITE_URL?>themes/<?=$C->THEME?>/js/style-table.js"></script>
					<div id="settings">
						<div id="settings_left">
							<?php $this->load_template('admin_leftmenu.php') ?>
						</div>
						<div id="settings_right">
							
							<div class="htabs" style="margin-bottom:6px; margin-top:0px;">
							<strong>فروشگاه شارژ</strong>
							<a href="<?= $C->SITE_URL ?>admin/charge/tab:add" class="<?= $D->tab=='add'?'onhtab':'' ?>"><b>افزودن کارت</b></a>
							<a href="<?= $C->SITE_URL ?>admin/charge/tab:carts" class="<?= $D->tab=='carts'?'onhtab':'' ?>"><b>کارت ها</b></a>
							<a href="<?= $C->SITE_URL ?>admin/charge/tab:search" class="<?= $D->tab=='search'?'onhtab':'' ?>"><b>جستجو</b></a>

						</div>
						<? if($D->error){?>
						<?= errorbox('خطا',$D->error_msg)?>
						<?}?>
							<? if($D->submit){?>
						<?= okbox('انجام شد',$D->submit_msg)?>
						<?}?>
							<div class="greygrad" style="margin-top:5px;">
								<div class="greygrad2">
									<div class="greygrad3">
						
						<? if($D->tab=="add"){ ?>
						<script type="text/javascript">
			inv_lines	= 0;
			function invform_line_add() {
				var tb	= d.getElementById("invite_table");
				if( !tb ){ return; }
				var tr	= tb.getElementsByTagName("TR");
				if( !tr || tr.length == 0 ) { return; }
				tr	= tr[0];
				tr	= tr.cloneNode(true);
				var i;
				var inp	= tr.getElementsByTagName("INPUT");
				for(i=0; i<inp.length; i++) {
					inp[i].value	= "";
				}
				inp	= tr.getElementsByTagName("SELECT");
				for(i=0; i<inp.length; i++) {
					inp[i].selectedIndex	= 0;
				}
				tb.appendChild(tr);
				inv_lines	++;
			}
			function invform_line_del(confirm_msg) {
				if( inv_lines <= 1 ) { return; }
				var tb	= d.getElementById("invite_table");
				if( !tb ){ return; }
				var tr	= tb.getElementsByTagName("TR");
				if( !tr || tr.length == 0 ) { return; }
				var i, inp;
				for(i=tr.length-1; i>=0; i--) {
					inp	= tr[i].getElementsByTagName("INPUT");
					if(inp.length==2 && inp[0].value=="" && inp[1].value=="") {
						tb.removeChild(tr[i]);
						inv_lines	--;
						return;
					}
				}
				if(confirm_msg && !confirm(confirm_msg)) {
					return;
				}
				tb.removeChild(tr[tr.length-1]);
				inv_lines	--;
			}
		</script>
							<form method="post" action="">
							<table id="setform" cellspacing="5">
								<tr>
									
									<th><b style="font-size:14px;">افزودن کارت شارژ</b></th>
									<th><b style="font-size:14px;">کد شارژ</b></th>
									<th><b style="font-size:14px;">سریال</b></th>
									<th><b style="font-size:14px;">اپراتور</b></th>
									<th><b style="font-size:14px;">قیمت</b></th>
								</tr>
								<tbody id="invite_table" class="invtbl_emldmns_<?= count($C->EMAIL_DOMAINS) ?>">
								
								<script type="text/javascript">
									inv_lines ++; 
								</script>
								<tr>
								<td>اطلاعات کارت<input type="hidden" name="sh[]" value="" class="setinp" style="width:100px; padding:3px;" maxlength="100" /></td>
				
									<td><input type="text" name="code[]" value="" class="setinp" style="width:100px; padding:3px;" maxlength="100" /></td>
							
									<td><input type="text" name="serial[]" value="" class="setinp" style="width:100px; padding:3px;" maxlength="100" /></td>
								
									<td>
									<select name="operator[]" class="setselect" style="width:100px;">
									<option  style="color:#75c843" value="mci">همراه اول</option>
									<option style="color:#f8cd22"  value="mtn">ایرانسل</option>
									<option style="color:red" value="talia">تالیا</option>
									</select>
									</td>
						
									<td>
									<select name="amount[]" class="setselect" style="width:100px;">
									<option value="1000">1000 تومان</option>
									<option value="2000">2000 تومان</option>
									<option value="5000">5000 تومان</option>
									<option value="10000">10000 تومان</option>
									<option value="20000">20000 تومان</option>
									</select>
									</td>
								</tr>
								
								</tbody>
								
								<tr>
									
									<th><b style="font-size:14px;">افزودن کارت شارژ</b></th>
									<th><b style="font-size:14px;">کد شارژ</b></th>
									<th><b style="font-size:14px;">سریال</b></th>
									<th><b style="font-size:14px;">اپراتور</b></th>
									<th><b style="font-size:14px;">قیمت</b></th>
								</tr>
								<tr>
									
									
									<td>
										<a href="javascript:;" onclick="invform_line_add();" onfocus="this.blur();" class="addaline">افزودن</a>
										<a href="javascript:;" onclick="invform_line_del();" onfocus="this.blur();" class="remaline">کاستن</a>
									</td>
								
									<td><input type="submit" name="add" value="ذخیره" style="padding:4px; font-weight:bold;"/></td>
								</tr>
								
								
								
							</table>
						</form>
						
						
						
						
						<?}elseif($D->tab == "carts"){?>
						
			<table id="travel" >

	
    
    <thead>    

        
        <tr>
           
            <th scope="col">مرتب سازی فروخته شده ها</th>
            <th scope="col">مرتب سازی اپراتور</th>
          
            
        </tr>        
    </thead>
    
 
    
    <tbody>	
<tr >
<td  scope="col">	
<select onchange="window.location.href=this.options[this.selectedIndex].value" name="s_op_c" class="setselect" style="width:100px;">
		<option <?= $D->sub_tab == "all" ? 'selected="selected" ' : '' ?> value="<?=  $C->SITE_URL.'admin/charge/tab:carts/sub-tab:all/operator:'.$D->operator?>">همه</option>
		<option <?= $D->sub_tab == "bought" ? 'selected="selected" ' : '' ?> value="<?=  $C->SITE_URL.'admin/charge/tab:carts/sub-tab:bought/operator:'.$D->operator?>">فروخته شده</option>
		<option <?= $D->sub_tab == "free" ? 'selected="selected" ' : '' ?> value="<?=  $C->SITE_URL.'admin/charge/tab:carts/sub-tab:free/operator:'.$D->operator?>">فروش نرفته</option>
		</select>
</td>
<td  scope="col">	
<select onchange="window.location.href=this.options[this.selectedIndex].value" name="s_op_e" class="setselect" style="width:100px;">
		<option <?= $D->operator == "all" ? 'selected="selected" ' : '' ?>  value="<?=  $C->SITE_URL.'admin/charge/tab:carts/sub-tab:'.$D->sub_tab.'/operator:all'?>">همه</option>
		<option <?= $D->operator == "mci" ? 'selected="selected" ' : '' ?> style="color:#75c843" value="<?=  $C->SITE_URL.'admin/charge/tab:carts/sub-tab:'.$D->sub_tab.'/operator:mci'?>">همراه اول</option>
		<option <?= $D->operator == "mtn" ? 'selected="selected" ' : '' ?> style="color:#f8cd22" value="<?=  $C->SITE_URL.'admin/charge/tab:carts/sub-tab:'.$D->sub_tab.'/operator:mtn'?>">ایرانسل</option>
		<option <?= $D->operator == "talia" ? 'selected="selected" ' : '' ?> style="color:red" value="<?=  $C->SITE_URL.'admin/charge/tab:carts/sub-tab:'.$D->sub_tab.'/operator:talia'?>">تالیا</option>
</select>
</td>
</tr>						
	 <tr>
           
            <th scope="col">مرتب سازی فروخته شده ها</th>
            <th scope="col">مرتب سازی اپراتور</th>
          
          
            
        </tr>      					
						
						
			   </tbody>		   </table>				
						
						<table id="travel" >

	
    
    <thead>    

        
        <tr>
            <th scope="col"><input onclick="toggle(this);" type="checkbox" name="selecct_all" id="selecct_all" value="1"/></th>
            <th scope="col">کد یکتا</th>
            <th scope="col">کد شارژ</th>
            <th scope="col">سریال</th>
            <th scope="col">اپراتور</th>
            <th scope="col">قیمت</th>
            <th scope="col">خرید شده</th>
            <th scope="col">ویرایش</th>
            <th scope="col">توسط</th>
            <th scope="col">تاریخ خرید</th>
            
        </tr>        
    </thead>

    <tbody>
	
    	<script>

function toggle(source) {
  checkboxes = document.getElementsByName('delete[]');

  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>	
		
		
		
    	<? if($D->carts){?>
		
		
		<? foreach($D->carts as $v){ ?>
		
	
		
			<form method="post" action="">
		
		<tr >
		  <td  scope="col"><input type="checkbox"  name="delete[]" id="instance" value="<?= $v->id?>"/></td>
		  <td    scope="col"><?= $v->id?></td>
            <td    scope="col"><?= $v->code ?></td>
            <td    scope="col"><?= $v->serial ?></td>
            <td    scope="col"><?= $this->network->get_operator_charge($v->operator) ?></td>
            <td    scope="col"><?= $v->amount  ?></td>
            <td   scope="col"><?= $v->status == 'free' ?   '<b style="color:green">آزاد</b>' : '<b style="color:red">فروش رفته</b>' ?></td>
            <td   scope="col"><a target="_blank" href="<?=$C->SITE_URL.'admin/charge/tab:edit/id:'.$v->id?> " ><img src="<?= $C->SITE_URL.'themes/'.$C->THEME.'/imgs/pctrls_edit.gif'?>" style="border:0px" /></a> </td>
            <td   scope="col"><?=  ($u = $this->network->get_cart_bought($v->operator,$v->code)) ? '<a href="'.userlink($this->network->get_user_by_id($u->user_id)->username).'" target="_blank">'.$this->network->get_user_by_id($u->user_id)->username.'</a>' : '---' ?></td>
            <td   scope="col"><?=  ($u = $this->network->get_cart_bought($v->operator,$v->code)) ? '<small>'.pdate('y:m:d',$u->buy_date).'<br>'.pdate('h:s:i',$u->buy_date).'</small>' : '---' ?></td>

			</tr>
		<?} }else{?>
		      <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
		<?}?>
        <tr >
		<th><input type="submit" name="delete_sbm" value="حذف" /> </th>
		  <th scope="col">کد یکتا</th>
            <th scope="col">کد شارژ</th>
            <th scope="col">سریال</th>
            <th scope="col">اپراتور</th>
            <th scope="col">قیمت</th>
            <th scope="col">خرید شده</th>
            <th scope="col">ویرایش</th>
            <th scope="col">توسط</th>
            <th scope="col">تاریخ خرید</th>
		</tr>
     </form>
    </tbody>

</table>
<? $this->load_template('paging_posts.php'); ?>
					<?}elseif($D->tab == "search"){?>
					
					
					<form action="<?=$C->SITE_URL.'admin/charge/tab:search'?>" method="post" >
								<table id="travel" >

	
    
    <thead>    

        
        <tr>
           
            <th scope="col">کد شارژ</th>
            <th scope="col">سریال شارژ</th>
            <th scope="col">اپراتور</th>
            <th scope="col">قیمت</th>
            <th scope="col">نام کاربر</th>
            <th scope="col">شماره تراکنش</th>
          
            
        </tr>        
    </thead>
    
 
    
    <tbody>	
<tr >
<td  scope="col">	
<input type="text" value="<?=$D->by_code?>" name="by_code"/>
</td>
<td  scope="col">	
<input type="text" value="<?=$D->by_serial?>" name="by_serial"/>
</td>

<td  scope="col">	
<select  class="setselect" name="by_operator" style="width:60px;">
		<option <?= $D->by_operator == "" ? 'selected="selected" ' : '' ?>  value="">همه</option>
		<option <?= $D->by_operator == "mci" ? 'selected="selected" ' : '' ?> style="color:#75c843" value="mci">همراه اول</option>
		<option <?= $D->by_operator == "mtn" ? 'selected="selected" ' : '' ?>style="color:#f8cd22" value="mtn">ایرانسل</option>
		<option <?= $D->by_operator == "talia" ? 'selected="selected" ' : '' ?> style="color:red" value="talia">تالیا</option>
</select>
</td>
<td  scope="col">	
<select  class="setselect" name="by_amount" style="width:100px;">
		<option <?= $D->by_amount == "" ? 'selected="selected" ' : '' ?>  value=""></option>
		<option <?= $D->by_amount == "1000" ? 'selected="selected" ' : '' ?>  value="1000">1000 تومانی</option>
		<option <?= $D->by_amount == "2000" ? 'selected="selected" ' : '' ?>  value="2000">2000 تومانی</option>
		<option <?= $D->by_amount == "5000" ? 'selected="selected" ' : '' ?>  value="5000">5000 تومانی</option>
		<option <?= $D->by_amount == "10000" ? 'selected="selected" ' : '' ?> value="10000">10000 تومانی</option>
		<option <?= $D->by_amount == "20000" ? 'selected="selected" ' : '' ?> value="20000">20000 تومانی</option>
</select>
</td>
<td  scope="col">	
<input type="text" style="width:60px" value="<?=$D->by_user?>" name="by_user"/>
</td>
<td  scope="col">	
<input type="text" value="<?=$D->by_trak?>" name="by_trak"/>
</td>
</tr>						
	 <tr>
              
            <th scope="col">کد شارژ</th>
            <th scope="col">سریال شارژ</th>
            <th scope="col">اپراتور</th>
            <th scope="col">قیمت</th>
            <th scope="col">نام کاربر</th>
            <th scope="col">شماره تراکنش</th>
          
            
        </tr>      					
						
						
			   </tbody>		   
			   </table>	

<center>
<input type="submit" name="search" value="جستجو" />
</center>			   
			   </form>
					<br><hr>
											<table id="travel" >

	
    
    <thead>    

        
        <tr>
            <th scope="col"><input onclick="toggle(this);" type="checkbox" name="selecct_all" id="selecct_all" value="1"/></th>
            <th scope="col">کد یکتا</th>
            <th scope="col">کد شارژ</th>
            <th scope="col">سریال</th>
            <th scope="col">اپراتور</th>
            <th scope="col">قیمت</th>
            <th scope="col">خرید شده</th>
			 <th scope="col">ویرایش</th>
            <th scope="col">توسط</th>
            <th scope="col">تاریخ</th>
            <th scope="col">تراکنش</th>
            
        </tr>        
    </thead>

    <tbody>
	
    	<script>

function toggle(source) {
  checkboxes = document.getElementsByName('delete[]');

  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>	
		
		
		
    	<? if($D->carts_searched){?>
		
		
		<? foreach($D->carts_searched as $v){ ?>
		
	
		
			<form method="post" action="">
		
		<tr >
		  <td  scope="col"><input type="checkbox"  name="delete[]" id="instance" value="<?= $v->id?>"/></td>
		  <td    scope="col"><?= $v->id?></td>
            <td    scope="col"><?= $v->code ?></td>
            <td    scope="col"><?= $v->serial ?></td>
            <td    scope="col"><?= $this->network->get_operator_charge($v->operator) ?></td>
            <td    scope="col"><?= $v->amount  ?></td>
            <td   scope="col"><?= $v->status == 'free' ?   '<b style="color:green">آزاد</b>' : '<b style="color:red">فروش رفته</b>' ?></td>
           <td   scope="col"><a target="_blank" href="<?=$C->SITE_URL.'admin/charge/tab:edit/id:'.$v->id?> " ><img src="<?= $C->SITE_URL.'themes/'.$C->THEME.'/imgs/pctrls_edit.gif'?>" style="border:0px" /></a> </td>
		  <td   scope="col"><?=  ($u = $this->network->get_cart_bought($v->operator,$v->code)) ? '<a href="'.userlink($this->network->get_user_by_id($u->user_id)->username).'" target="_blank">'.$this->network->get_user_by_id($u->user_id)->username.'</a>' : '---' ?></td>
           <td   scope="col"><?=  ($u = $this->network->get_cart_bought($v->operator,$v->code)) ? '<small>'.pdate('y:m:d',$u->buy_date).'<br>'.pdate('h:s:i',$u->buy_date).'</small>' : '---' ?></td>
           <td   scope="col"><?=  ($u = $this->network->get_cart_bought($v->operator,$v->code)) ? '<small>'.$u->trak.'</small>' : '---' ?></td>


			</tr>
		<?} }else{?>
		      <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
			  <td   scope="col">بدون نتیجه...!</td>
		<?}?>
        <tr >
		<th><input type="submit" name="delete_sbm" value="حذف" /> </th>
		  <th scope="col">کد یکتا</th>
            <th scope="col">کد شارژ</th>
            <th scope="col">سریال</th>
            <th scope="col">اپراتور</th>
            <th scope="col">قیمت</th>
            <th scope="col">خرید شده</th>
            <th scope="col">ویرایش</th>
            <th scope="col">توسط</th>
			          <th scope="col">تاریخ</th>
            <th scope="col">تراکنش</th>
		</tr>
     </form>
    </tbody>

</table>

					
					<? }elseif($D->tab == "edit"){?> 
					
					<? if($D->o) { ?>
					
					
					
										<form method="post" action="">
										<input type="hidden" name="id" value="<?= $D->o->id ?>" /> 
							<table id="setform" cellspacing="5">
								<tr>
									
									<th><b style="font-size:14px;">افزودن کارت شارژ</b></th>
									<th><b style="font-size:14px;">کد شارژ</b></th>
									<th><b style="font-size:14px;">سریال</b></th>
									<th><b style="font-size:14px;">اپراتور</b></th>
									<th><b style="font-size:14px;">قیمت</b></th>
									<th><b style="font-size:14px;">وضعیت</b></th>
								</tr>
								<tbody id="invite_table" class="invtbl_emldmns_<?= count($C->EMAIL_DOMAINS) ?>">
							
								<tr>
								<td>اطلاعات کارت<input type="hidden" name="sh" value="" class="setinp" style="width:100px; padding:3px;" maxlength="100" /></td>
				
									<td><input type="text" name="code" value="<?= $D->o->code?>" class="setinp" style="width:100px; padding:3px;" maxlength="100" /></td>
							
									<td><input type="text" name="serial" value="<?= $D->o->serial?>" class="setinp" style="width:100px; padding:3px;" maxlength="100" /></td>
								
									<td>
									<select name="operator" class="setselect" style="width:100px;">
									<option style="color:#75c843"  <?= $D->o->operator =="mci" ? 'selected="selected"' : ''?> value="mci">همراه اول</option>
									<option style="color:#f8cd22" <?= $D->o->operator =="mtn" ? 'selected="selected"' : ''?>value="mtn">ایرانسل</option>
									<option style="color:red" <?= $D->o->operator =="talia" ? 'selected="selected"' : ''?> value="talia">تالیا</option>
									</select>
									</td>
						
									<td>
									<select name="amount" class="setselect" style="width:100px;">
									<option <?= $D->o->amount == 1000 ? 'selected="selected"' : ''?> value="1000">1000 تومان</option>
									<option <?= $D->o->amount == 2000 ? 'selected="selected"' : ''?> value="2000">2000 تومان</option>
									<option <?= $D->o->amount == 5000 ? 'selected="selected"' : ''?>  value="5000">5000 تومان</option>
									<option <?= $D->o->amount == 10000 ? 'selected="selected"' : ''?> value="10000">10000 تومان</option>
									<option <?= $D->o->amount == 20000 ? 'selected="selected"' : ''?> value="20000">20000 تومان</option>
									</select>
									</td>
										<td>
									<select name="status" class="setselect" style="width:100px;">
									<option <?= $D->o->status == 'free' ? 'selected="selected"' : ''?> value="free">آزاد</option>
									<option <?= $D->o->status == 'bought' ? 'selected="selected"' : ''?> value="bought">فروش رفته</option>
									
									</select>
									</td>
								</tr>
								
								</tbody>
								
								<tr>
									
									<th><b style="font-size:14px;">افزودن کارت شارژ</b></th>
									<th><b style="font-size:14px;">کد شارژ</b></th>
									<th><b style="font-size:14px;">سریال</b></th>
									<th><b style="font-size:14px;">اپراتور</b></th>
									<th><b style="font-size:14px;">قیمت</b></th>
									<th><b style="font-size:14px;">وضعیت</b></th>
								</tr>
								<tr>
									
									
								
								
									<td><input type="submit" name="edit" value="ذخیره" style="padding:4px; font-weight:bold;"/></td>
								</tr>
								
								
								
							</table>
						</form>
					
					
					
					
					
					
					
					
					
					
					
					<?}?>
					
					<?}?>
									</div>
								</div>
							</div>
							
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>