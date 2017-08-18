<?php

class Template_Smartyifexists extends Smarty_Resource_Custom {


	/**
	 * Fetch a template and its modification time from database
	 *
	 * @param string $name template name
	 * @param string $source template source
	 * @param integer $mtime template modification timestamp (epoch)
	 * @return void
	 */
	protected function fetch($name, &$source, &$mtime)
	{
		$source = @file_get_contents($name);
	}
 
	/**
	 * Fetch a template's modification time from database
	 *
	 * @note implementing this method is optional. Only implement it if modification times can be accessed faster than loading the comple template source.
	 * @param string $name template name
	 * @return integer timestamp (epoch) the template was modified
	 */
	protected function fetchTimestamp($name) {
		$mtime = @filemtime($name);
		return $mtime || time();
	}
}
