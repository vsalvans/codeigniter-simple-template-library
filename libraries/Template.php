<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
	
class Template {

	private $menus = array();
	private $language;
	private $data = array();
	private $CI;
	
	function __construct()
	{
		
		$this->CI =& get_instance();
		$this->CI->config->load('template');
		
		$this->reloadMenus();
		
	}

	function setTemplate($template)
	{
		$this->CI =& get_instance();
		$this->CI->config->load($template);
	
		$this->reloadMenus();
	}
	

	function view($view, $data = NULL)
	{

		$template = $this->CI->config->item('template');
		$templateFolder = $this->CI->config->item('template_folder');
		
		if($data != NULL)  $this->addData($data);
		
		//Validem algunes coses i afegim altres d'utils
		if(!isset($this->data['page_title'])) $this->data['page_title'] = '';
		
		//add user to session, it uses custom functon
		//if($user = $this->CI->session->get_user()) $this->data['current_user'] = $user;

		$messages = $this->CI->session->flashdata('messages');
		if(!empty($messages) && empty($this->data['messages'])) $this->data['messages'] = $messages;
		
		$this->CI->load->view($templateFolder . $template,$this->getData($templateFolder . $view));

	}


	function getData($function)
	{
		
		$data = array();
	
		$data['body_classes'] = str_replace('/',' ',$function);
		
		$data['menus'] = array();
		
		if (isset($this->data['key_menu'])) $current_menu = $this->data['key_menu'];
		else $current_menu = $function;
		
		foreach($this->menus as $key => $menu) {
		
			$data['menus'][$key] = $this->getMenu($key,$current_menu);
		}
		
		$data =  array_merge($data,$this->data);

		$data['main_content'] = $this->CI->load->view($function,$data,true);

		return $data;
	
	}
	
	function getMenu($menu, $current_key)
	{

		$menu_translation = $this->CI->config->item('menu_translation');
		
		$items = $this->menus[$menu];
		
		$output = '<ul class="nav">';
		
		foreach($items as $key => $item) {
			
			$item['link'] =  base_url().$item['link'];
			
			if (isset($item['disabled']) && $item['disabled'] == TRUE ) $menu_item = '<li><span>'.t($item['name'],'menus').'</span></li>';
			else  $menu_item = '<li '.($current_key == $key?'class="active"':'').'><a href="'.$item['link'].'" ><span>'.t($item['name'],$menu_translation).'</span></a></li>';
			
			if (isset($item['access'])) {
				$user = getUser();
				if ($user && ($user->role == $item['access'] || $user->role == 'administrator')) $output .= $menu_item; 
			} else {
				$output .= $menu_item;
			}
			
		}
		
		$output .= '</ul>';
		
		return $output;
	}
	
	function addMenu($menu_name,$items)
	{
		$this->menus[$menu_name] = $items;
	}
	
	function addData($data , $value = NULL)
	{
		if(is_array($data))	$this->data = array_merge($this->data,$data);
		else if($value != NULL) $this->data = array_merge($this->data,array($data => $value));
	}

	function setPageTitle($pageTitle)
	{
		$this->data['page_title'] = $pageTitle;
	}

	function addMessage($message , $type) {
		
		$this->data['messages'][] = array(
			'message' => $message,
			'type' => $type,
		);

		$this->CI->session->set_flashdata('messages',$this->data['messages']);
	}

	private function reloadMenus() {

		$this->menus = array();
		$menus = $this->CI->config->item('menus');

		if ($menus) {
			foreach($menus as $key => $menu) {
				$this->addMenu($key,$menu);
			}
		}
	}
	

}