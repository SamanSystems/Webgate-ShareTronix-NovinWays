<?php
	if (!$this->network->id) {
		echo 'ERROR';
		return;
	}
	if (!$this->user->is_logged) {
		echo 'ERROR';
		return;
	}
	
	
	$n = $this->network->get_cch_for_buy($_POST['op'],$_POST['am']);
	
echo $n;
	
	return;

?>