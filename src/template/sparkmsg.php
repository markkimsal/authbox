<?php

class Template_Sparkmsg {

	public function template($response) {
		$msgList = $response->sparkmsg;
		if (!is_array($msgList)) return;

		foreach ($msgList as $_idx => $_msg) {
			$css = 'alert-';
			switch( $_msg['type'] ) {
				case 'error':
				case 'msg_err':
					$css .= 'danger';
					break;
				case 'warn':
				case 'warning':
				case 'msg_warn':
					$css .= 'warning';
					break;
				default:
					$css .= 'info';
			}

			echo'
<div class="alert '.$css.' alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
';
		echo $_msg['msg'];
echo '
</div>
';
		unset($msgList[$_idx]);
		}
		$response->sparkmsg = array();
	}
}
