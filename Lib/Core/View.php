<?php
	
	/**
	 * Définition des vues
	 *
	 * @author Voahirana 
	 *
	 * @since 25/09/19
	 */

	namespace Core; 

	class View
	{
		private $template;

		/** 
		 * Constructeur de View 
		 *
		 * @param string $template Nom de la page à charger
		 *
		 * @return empty
		 */
		function __construct($template)
		{
			$this->template = $template;
		}

		/** 
		 * Envoyer les données vers la page d'une module
		 *
		 * @param string $appName Nom de l'application
		 * @param string $module Nom de module
		 * @param string $data Les données à envoyer
		 *
		 * @return empty
		 */
		public function send($appName, $module, $data, $user)
		{   
			$file = '';
			if (!empty($appName)) {
				if (!empty($module)) {
					if ((isset($_SESSION['compte']) && $_SESSION['compte']['identifiant'] == $user) || $user == "") {
						$file = __DIR__ .'/../../App/'. $appName .'/Modules/'. $module .'/Views/'. $this->template .'.phtml';
					} else {
						$file = __DIR__ .'/../../Errors/404.phtml';
					}									
				} else {
					$file = __DIR__ .'/../../App/'. $appName .'/Templates/'. $this->template .'.phtml';;
				}
			} 
			if (!file_exists($file)) {
				$file = __DIR__ .'/../../Errors/404.phtml';
			}		
			ob_start();
	        include($file); 
	        $pageContent = ob_get_clean();
	        include_once(__DIR__ .'/../../App/' . $appName . '/Templates/index.phtml');		
		}

		/** 
		 * Envoyer les données vers la page d'une module mais sans le template
		 *
		 * @param string $appName Nom de l'application
		 * @param string $module Nom de module
		 * @param string $data Les données à envoyer
		 *
		 * @return empty
		 */
		public function sendWithoutTemplate($appName, $module, $data, $user)
		{   
			$file = '';
			if (!empty($appName)) {
				if (!empty($module)) {
					if ((isset($_SESSION['compte']) && $_SESSION['compte']['identifiant'] == $user) || $user == "") {
						$file = __DIR__ .'/../../App/'. $appName .'/Modules/'. $module .'/Views/'. $this->template .'.phtml';
					} else {
						$file = __DIR__ .'/../../Errors/404.phtml';
					}									
				} else {
					$file = __DIR__ .'/../../App/'. $appName .'/Templates/'. $this->template .'.phtml';;
				}
			} 
			if (!file_exists($file)) {
				$file = __DIR__ .'/../../Errors/404.phtml';
			}		
			ob_start();
	        include($file); 
	        $pageContent = ob_get_clean();
	        include_once(__DIR__ .'/../../App/' . $appName . '/Templates/container.phtml');		
		}

	}