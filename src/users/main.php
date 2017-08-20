<?php

class Users_Main {

	use \Setup_Crud;
	public $tableName = 'user_login';


	public function makeColumns($rows) {
		$first = $rows[0];
		$colList = [];
		$attribs = array_keys($first);
		foreach ($attribs as $_attr) {
			$_a = ucwords(str_replace('_', ' ', $_attr));
			$cls = '';
			if ($_a == 'Updated On')        $cls = 'tcl-lg';
			if ($_a == 'Password')          $cls = 'tcl-lg';
			if ($_a == 'Reg Ip Addr')       $cls = 'tcl-lg';
			if ($_a == 'Login Referrer')    $cls = 'tcl-lg';
			if ($_a == 'Login Ip Addr')     $cls = 'tcl-lg';
			if ($_a == 'Id Provider Token') $cls = 'tcl-lg';
			if ($_a == 'Reg Referrer')      $cls = 'tcl-lg';
			if ($_a == 'Validation Token')  $cls = 'tcl-lg';
			if ($_a == 'Login Date')        $cls = 'tcl-md';
			if ($_a == 'Reg Date')          $cls = 'tcl-md';
			$colList[] = array('name'=>$_a, 'class'=>$cls);
		}
		return $colList;

	}
}
