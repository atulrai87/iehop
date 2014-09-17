<?php
/**
 * Mobile install model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 1 $ $Date: 2013-12-02 14:53:00 +0300 $ $Author: dpopenov $
 * */
class Mobile_install_model extends Model {

	private function _set_paths() {
		$mobile_path = SITE_PHYSICAL_PATH . 'm/';
		$files = array(
			array(
				'path' => $mobile_path . 'index.html',
				'replace' => array(
					'[m_subfolder]' => '/' . SITE_SUBFOLDER . 'm'
				)
			),
			array(
				'path' => $mobile_path . 'scripts/app.js',
				'replace' => array(
					'[api_virtual_path]' => SITE_VIRTUAL_PATH . 'api'
				)
			)
		);
		foreach ($files as $file) {
			$file_contents = file_get_contents($file['path']);
			if ($file_contents) {
				$file_contents = str_replace(array_keys($file['replace']), array_values($file['replace']), $file_contents);
				file_put_contents($file['path'], $file_contents);
			}
		}
	}

	public function _arbitrary_installing() {
		$this->_set_paths();
	}

}