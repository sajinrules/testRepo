<?php 
/**
 * Import configuration wizard
 * 
 * @author Max Tsiplyakov <makstsiplyakov@gmail.com>
 */

class PMUI_Admin_Import extends PMUI_Controller_Admin {		
	
	public function _step_ready() {

		$default = PMUI_Plugin::get_default_import_options();

		$this->data['id'] = $id = $this->input->get('id');

		$this->data['import'] = $import = new PMXI_Import_Record();			
		if ( ! $id or $import->getById($id)->isEmpty()) { // specified import is not found		
			$DefaultOptions = (isset(PMXI_Plugin::$session->options) ? PMXI_Plugin::$session->options : array()) + $default;				
			$post = $this->input->post( $DefaultOptions );	
		}
		else 
			$post = $this->input->post(
				$this->data['import']->options
				+ $default			
			);				

		$this->data['is_loaded_template'] = ( ! empty(PMXI_Plugin::$session->is_loaded_template)) ? PMXI_Plugin::$session->is_loaded_template : false;

		$load_options = $this->input->post('load_template');

		if ($load_options) { // init form with template selected
			
			$template = new PMXI_Template_Record();
			if ( ! $template->getById($this->data['is_loaded_template'])->isEmpty()) {	
				$post = (!empty($template->options) ? $template->options : array()) + $default;				
			}
			
		} elseif ($load_options == -1){
			
			$post = $DefaultOptions;
							
		}
				
		$this->data['post'] =& $post;	

	}

	public function index( $post = array() ) {	

		$this->_step_ready();
		
		$this->render();

	}	
	
	public function options( $isWizard = false ){

		$this->_step_ready();

		$this->data['isWizard'] = $isWizard;	

		// Get All meta keys in the system
		$this->data['existing_meta_keys'] = array();
		$meta_keys = new PMXI_Model_List();
		$meta_keys->setTable(PMXI_Plugin::getInstance()->getWPPrefix() . 'usermeta');
		$meta_keys->setColumns('umeta_id', 'meta_key')->getBy(NULL, "umeta_id", NULL, NULL, "meta_key");	
		$hide_fields = array('first_name', 'last_name', 'nickname', 'description', PMXI_Plugin::getInstance()->getWPPrefix() . 'capabilities');
		if ( ! empty($meta_keys) and $meta_keys->count() ){
			foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields) or strpos($meta_key['meta_key'], '_wp') === 0) continue;
				$this->data['existing_meta_keys'][] = $meta_key['meta_key'];
			}
		}			

		$this->render();
	}
}
