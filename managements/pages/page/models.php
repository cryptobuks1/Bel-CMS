<?php
/**
 * Bel-CMS [Content management system]
 * @version 1.0.0
 * @link https://bel-cms.be
 * @link https://determe.be
 * @license http://opensource.org/licenses/GPL-3.-copyleft
 * @copyright 2014-2019 Bel-CMS
 * @author as Stive - stive@determe.be
 */

if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}

class ModelsPage
{
	#####################################
	# Infos tables
	#####################################
	# TABLE_PAGE
	# TABLE_PAGE_CONTENT
	#####################################
	public function addNewPage ($data)
	{
		if ($data !== false) {
			// SECURE DATA
			$send['name']    = Common::VarSecure($data['name'], ''); // autorise que du texte
			if (!isset($data['groups'])) {
				$send['groups'] = 0;
			} else {
				$send['groups'] = implode('|', $data['groups']);
			}
			// SQL INSERT
			$sql = New BDD();
			$sql->table('TABLE_PAGE');
			$sql->sqlData($send);
			$sql->insert();
			// SQL RETURN NB INSERT
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => SEND_PAGE_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'warning',
					'text' => SEND_PAGE_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'warning',
				'text' => ERROR_NO_DATA
			);
		}

		return $return;
	}

	public function getPages ()
	{
		$return = array();
		$sql = New BDD();
		$sql->table('TABLE_PAGE');
		$sql->queryAll();
		foreach ($sql->data as $k => $v) {
			$sql->data[$k]->count = self::countPages($v->id);
		}
		$return = $sql->data;
		return $return;
	}

	public function getPage ($id = false)
	{
		$return = null;

		if ($id) {
			$sql = New BDD();
			$sql->table('TABLE_PAGE');
			$where = array(
				'name'  => 'id',
				'value' => $id
			);
			$sql->where($where);
			$sql->queryOne();
			$return = $sql->data;
		}

		return $return;
	}

	public function getPagecontent ($id = false)
	{
		$return = array();

		if ($id && is_numeric($id)) {
			$sql = New BDD();
			$sql->table('TABLE_PAGE_CONTENT');
			$where = array(
				'name'  => 'number',
				'value' => $id
			);
			$sql->where($where);
			$sql->queryAll();
			$return = $sql->data;
		}

		return $return;
	}

	public function getPagecontentId ($id = false)
	{
		$return = array();

		if ($id && is_numeric($id)) {
			$sql = New BDD();
			$sql->table('TABLE_PAGE_CONTENT');
			$where = array(
				'name'  => 'id',
				'value' => $id
			);
			$sql->where($where);
			$sql->queryOne();
			$return = $sql->data;
		}

		return $return;
	}	#####################################
	# Récupère le nombre de page
	#####################################
	public function countPages ($id)
	{
		$id = (int) $id;
		$sql = New BDD();
		$sql->table('TABLE_PAGE_CONTENT');
		$where = array(
			'name' => 'number',
			'value' => $id
		);
		$sql->where($where);
		$sql->count();
		$return = $sql->data;
		return $return;
	}
	public function sendedit ($data)
	{
		if ($data && is_array($data)) {
			// SECURE DATA
			$edit['name']              = Common::VarSecure($data['name'], ''); // autorise que du texte
			$edit['content']           = Common::VarSecure($data['content'], 'html'); // autorise que les balises HTML
			if (!isset($data['groups'])) {
				$edit['groups'] = 0;
			} else {
				$edit['groups'] = implode('|', $data['groups']);
			}			// SQL UPDATE
			$sql = New BDD();
			$sql->table('TABLE_PAGE');
			$id = Common::SecureRequest($data['id']);
			$sql->where(array('name' => 'id', 'value' => $id));
			$sql->sqlData($edit);
			$sql->update();
			// SQL RETURN NB UPDATE
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => EDIT_PAGE_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'warning',
					'text' => EDIT_PAGE_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'warning',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}

	public function sendnewsub ($data)
	{
		if ($data && is_array($data)) {
			$id = (int) $data['id'];
			$count = self::countPages($id) + 1;
			// SECURE DATA
			if (isset($data['wysiwyg']) && $data['wysiwyg'] == 1) {
				$send['content'] = Common::VarSecure($data['content'], 'html'); // autorise que les balises HTML
			} else {
				$send['content'] = Common::VarSecure($data['content_pur'], 'html'); // autorise que les balises HTML
			}
			$send['name']       = Common::VarSecure($data['name'], ''); // autorise que du texte
			$send['pagenumber'] = $count;
			$send['number']     = $id;
			// SQL INSERT
			$sql = New BDD();
			$sql->table('TABLE_PAGE_CONTENT');
			$sql->sqlData($send);
			$sql->insert();
			// SQL RETURN NB INSERT
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => SEND_PAGE_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'warning',
					'text' => SEND_PAGE_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'warning',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}

	public function sendeditsub ($data = false)
	{
		if (is_array($data)) {
			if (isset($data['wysiwyg']) && $data['wysiwyg'] == 1) {
				$edit['content'] = Common::VarSecure($data['content'], 'html'); // autorise que les balises HTML
			} else {
				$edit['content'] = Common::VarSecure($data['content_pur'], 'html'); // autorise que les balises HTML
			}
			// SECURE DATA
			$edit['name']    = Common::VarSecure($data['name'], ''); // autorise que du texte
			$sql = New BDD();
			$sql->table('TABLE_PAGE_CONTENT');
			$id = Common::SecureRequest($data['id']);
			$sql->where(array('name' => 'id', 'value' => $id));
			$sql->sqlData($edit);
			$sql->update();
			// SQL RETURN NB UPDATE
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => EDIT_PAGE_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'warning',
					'text' => EDIT_PAGE_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'warning',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}

	public function deletesub ($data)
	{
		if ($data && is_numeric($data)) {
			// SECURE DATA
			$delete = (int) $data;
			// SQL DELETE
			$sql = New BDD();
			$sql->table('TABLE_PAGE_CONTENT');
			$sql->where(array('name'=>'id','value' => $delete));
			$sql->delete();
			// SQL RETURN NB DELETE
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => DEL_SUBPAGE_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'warning',
					'text' => DEL_SUBPAGE_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'error',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}

	public function deleteAll ($id = false)
	{
		if (is_numeric($id)) {
			// SECURE DATA
			$delete = (int) $id;
			// SQL DELETE
			$sql = New BDD();
			$sql->table('TABLE_PAGE');
			$sql->where(array('name'=>'id','value' => $delete));
			$sql->delete();

			$del = New BDD();
			$del->table('TABLE_PAGE_CONTENT');
			$del->where(array('name'=>'number','value' => $delete));
			$del->delete();
		
			// SQL RETURN NB DELETE
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => DEL_PAGE_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'warning',
					'text' => DEL_SUBPAGE_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'error',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}
	public function sendparameter($data = null)
	{
		if ($data !== false) {
			$data['admin']        = isset($data['admin']) ? $data['admin'] : array(1);
			$data['groups']       = isset($data['groups']) ? $data['groups'] : array(1);
			$upd['active']        = isset($data['active']) ? 1 : 0;
			$upd['access_admin']  = implode("|", $data['admin']);
			$upd['access_groups'] = implode("|", $data['groups']);
			// SQL UPDATE
			$sql = New BDD();
			$sql->table('TABLE_PAGES_CONFIG');
			$sql->where(array('name' => 'name', 'value' => 'page'));
			$sql->sqlData($upd);
			$sql->update();
			if ($sql->rowCount == 1) {
				$return = array(
					'type' => 'success',
					'text' => EDIT_PAGE_PARAM_SUCCESS
				);
			} else {
				$return = array(
					'type' => 'warning',
					'text' => EDIT_PAGE_PARAM_ERROR
				);
			}
		} else {
			$return = array(
				'type' => 'warning',
				'text' => ERROR_NO_DATA
			);
		}
		return $return;
	}

}
