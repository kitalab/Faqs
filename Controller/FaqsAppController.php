<?php
/**
 * FaqsApp Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * FaqsApp Controller
 *
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @package NetCommons\Faqs\Controller
 */
class FaqsAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Security',
		'NetCommons.NetCommonsFrame',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$results = $this->camelizeKeyRecursive(['current' => $this->current]);
		$this->set($results);
	}

/**
 * initFaq
 *
 * @param array $contains Optional result sets
 * @return bool True on success, False on failure
 */
	public function initFaq($contains = []) {
		if (! $faq = $this->Faq->getFaq($this->viewVars['blockId'], $this->viewVars['roomId'])) {
			$this->throwBadRequest();
			return false;
		}
		$faq = $this->camelizeKeyRecursive($faq);
		$this->set($faq);

		if (in_array('faqSetting', $contains, true)) {
			if (! $faqSetting = $this->FaqSetting->getFaqSetting($faq['faq']['key'])) {
				$faqSetting = $this->FaqSetting->create(
					array('id' => null)
				);
			}
			$faqSetting = $this->camelizeKeyRecursive($faqSetting);
			$this->set($faqSetting);
		}

		$this->set('userId', (int)$this->Auth->user('id'));

		return true;
	}

/**
 * initTabs
 *
 * @param string $mainActiveTab Main active tab
 * @param string $blockActiveTab Block active tab
 * @return void
 */
	public function initTabs($mainActiveTab, $blockActiveTab) {
		//タブの設定
		$settingTabs = array(
			'tabs' => array(
				'block_index' => array(
					'plugin' => $this->params['plugin'],
					'controller' => 'blocks',
					'action' => 'index',
					$this->viewVars['frameId'],
				),
			),
			'active' => $mainActiveTab
		);
		$this->set('settingTabs', $settingTabs);

		$blockSettingTabs = array(
			'tabs' => array(
				'block_settings' => array(
					'plugin' => $this->params['plugin'],
					'controller' => 'blocks',
					'action' => $this->params['action'],
					$this->viewVars['frameId'],
					$this->viewVars['blockId']
				),
				'role_permissions' => array(
					'plugin' => $this->params['plugin'],
					'controller' => 'block_role_permissions',
					'action' => 'edit',
					$this->viewVars['frameId'],
					$this->viewVars['blockId']
				),
			),
			'active' => $blockActiveTab
		);
		$this->set('blockSettingTabs', $blockSettingTabs);
	}

}
