<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link https://bel-cms.be
 * @link https://stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014-2019 Bel-CMS
 * @author Stive - determe@stive.eu
 */

if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}

final class BelCMSConfig extends Dispatcher
{
	public $return;

	function __construct ()
	{
		parent::__construct();
		$return = array();

		$sql = New BDD;
		$sql->table('TABLE_CONFIG');
		$sql->fields(array('name', 'value'));
		$sql->queryAll();
		$config = $sql->data;
		unset($sql);
		foreach ($config as $k => $v) {
			$return[mb_strtoupper($v->name)] = (string) $v->value;
		}
		Common::Constant($return);
	}

	public static function GetConfigPage ($page = null)
	{
		$return = null;

		if ($page != null) {
			$page = strtolower(trim(strtolower($page)));
			$sql = New BDD;
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->where(array('name' => 'name', 'value' => $page));
			$sql->queryOne();
			$return = $sql->data;
			$return->access_groups = explode('|', $return->access_groups);
			$return->access_admin  = explode('|', $return->access_admin);
			if (!empty($return->config)) {
				$return->config = Common::transformOpt($return->config);
			} else {
				$return->config = (object) array();
			}
		}

		return $return;
	}

	public static function getGroups ()
	{
		$return = (object) array();

		$sql = New BDD;
		$sql->table('TABLE_GROUPS');
		$sql->fields(array('name', 'id_group'));
		$sql->queryAll();

		foreach ($sql->data as $k => $v) {
			$a = defined(strtoupper($v->name)) ? constant(strtoupper($v->name)) : ucfirst(strtolower($v->name));
			$return->$a = $v->id_group;
		}

		return $return;
	}
}