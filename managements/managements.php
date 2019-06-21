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

final class Managements extends Dispatcher
{
	public $render;
	#########################################
	# redirige l'utilisateur si loguer ou pas
	#########################################
	function __construct()
	{
		parent::__construct ();

		self::getLangs();

		if (isset($_SESSION['USER']['HASH_KEY']) && strlen($_SESSION['USER']['HASH_KEY']) == 32) {
			if (isset($_SESSION['USER']['ADMIN']) and $_SESSION['USER']['ADMIN'] === true) {
				require_once MANAGEMENTS.'intern'.DS.'adminpages.php';
				self::base();
			} else {
				self::login();
			}
		} else {
			Common::Redirect('User/Login');
		}
	}
	#########################################
	# Page principal avec le menu
	#########################################
	public function base ()
	{
		$render   = self::getLinksPages ($this->controller);
		$menuPage = self::menuPage();
		require_once MANAGEMENTS.'intern'.DS.'layout.php';
	}
	#########################################
	# Gestion des pages & widgets + le dashboard
	#########################################
	private function getLinksPages ($page = null)
	{
		$render = null;
		ob_start();

		require_once MANAGEMENTS.'intern'.DS.'adminpages.php';

		if ($page == 'dashboard') {
			include MANAGEMENTS.'intern'.DS.'dashboard.php';
			$render = ob_get_contents();
		} else {
			if ($_REQUEST['page'] == true) {
				$scan = Common::ScanDirectory(MANAGEMENTS.'pages');
				if (in_array($page, $scan)) {
					include MANAGEMENTS.'pages'.DS.$page.DS.'controller.php';
					$this->controller = new $this->controller();
					if ($this->controller->active === true) {
						if (method_exists($this->controller, $this->view)) {
							unset($this->links[0], $this->links[1]);
							call_user_func_array(array($this->controller,$this->view),$this->links);
						} else {
							Notification::error('La sous-page demander <strong>'.$this->view.'</strong> n\'est pas disponible dans la page <strong>'.$page.'</strong>', 'Fichier');
						}
					}
					echo $this->controller->render;
				} else {
					Notification::error('Fichier controller de la page : <strong> '.$page.'</strong> non disponible...', 'Fichier');
				}
			} else if ($_REQUEST['widgets'] == true) {

			} else {
				include MANAGEMENTS.'intern'.DS.'dashboard.php';
				$render = ob_get_contents();
			}
		}

		$render = ob_get_contents();

		if (ob_get_length() != 0) {
			ob_end_clean();
		}

		return $render;
	}
	#########################################
	# Page Login
	#########################################
	private function login ()
	{
		if (isset($_REQUEST['umal']) && isset($_REQUEST['passwrd'])) {
			if (!empty($_REQUEST['umal']) && !empty($_REQUEST['passwrd'])) {

				if (Secure::isMail($_REQUEST['umal']) === false) {
					$return['ajax'] = 'Veuillez entrer votre e-mail';
					echo json_encode($return);
					sleep(2);
					return;
				}

				$return = array();

				$sql = New BDD();
				$sql->table('TABLE_USERS');
				$sql->where(array('name' => 'email', 'value' => $_REQUEST['umal']));
				$sql->queryOne();
				$data = $sql->data;

				if (password_verify($_REQUEST['passwrd'], $data->password)) {
					if ($_SESSION['USER']['HASH_KEY'] == $data->hash_key) {
						$_SESSION['USER']['ADMIN'] = true;
						$return['ajax'] = 'login en cours...';
					} else {
						$return['ajax'] = 'Hash_key ne corespond pas au votre ?...';
					}
				} else {
					$return['ajax'] = 'Le password n\'est pas le bon !!!';
				}

				sleep(2);

				echo json_encode($return);
			}
		} else {
			include MANAGEMENTS.'intern/login.php';
		}
	}
	#########################################
	# Menu des pages
	#########################################
	private function menuPage ()
	{
		$pages  = self::getPages ();
		$return = array();

		foreach ($pages as $k => $v) {
			$return[$v.'?management&page=true'] = defined(strtoupper($v)) ? constant(strtoupper($v)) : $v;
		}
		return $return;
	}
	#########################################
	# Menu des Widgets
	#########################################
	private function MenuWidget ()
	{
		$pages  = self::getPages ();
		$return = array();
	}
	#########################################
	# Scan le dossier des pages
	#########################################
	private function getPages ()
	{
		$scan = Common::ScanDirectory(MANAGEMENTS.'pages', true);
		return $scan;
	}
	#########################################
	# Scan le dossier des widgets
	#########################################
	private function getWidgets ()
	{
		$scan = Common::ScanFiles(MANAGEMENTS.'widgets');
		return $scan;
	}
	#########################################
	# récupère tout les fichiers de lang et les inclus
	#########################################
	private function getLangs ()
	{
		$scan = Common::ScanFiles(MANAGEMENTS.'langs');
		foreach ($scan as $k => $v) {
			require_once MANAGEMENTS.'langs'.DS.$v;
		}
	}
}