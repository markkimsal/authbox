<?php


trait Setup_Crud {

	public function mainAction($request, $response) {

		$finder = \_makeNew('dataitem', $this->getDataItemName());
		$response->addInto('rows', $finder->findAsArray());
		$colList = $this->makeColumns($response->rows);
		$response->addInto('columns', $colList);
	}

	public function makeColumns($rows) {
		$first = $rows[0];
		$colList = [];
		$attribs = array_keys($first);
		foreach ($attribs as $_attr) {
			$_a = ucwords(str_replace('_', ' ', $_attr));
			$colList[] = $_a;
		}
		return $colList;
	}

	public function getDataItemName() {
		return $this->tableName;
	}

	public function output($response) {
		_connect('output', array($this, '_output'));
		_set('template.main.file', '/crud/view');
	}
}
