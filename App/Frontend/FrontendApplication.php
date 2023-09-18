<?php
	
	/**
	 * Desc: Définition de l'application Frontend
	 * @author Voahirana
	 * @since 25/09/19
	 */

	namespace App\Frontend;

	use \Core\Application;
	use \Core\View;

	class FrontendApplication extends Application
	{	
		protected $appName = "Frontend";
		protected $request;

		/**
		 * Desc: Constructeur de FrontendApplication
		 * @param array $request Desc: URL de l'application
		 * @return empty
		 */
		function __construct($request) 
		{
			$this->request = $request;
		}

		/**
		 * Desc: Demmarage de l'application
		 * @param empty
		 * @return empty
		 */
		public function run() 
		{
			$module = ucfirst($this->getModule());
			$user   = $this->getUser();
			if (!empty($module)) {
				$action     		= $this->getAction();
				$controller 		= '\App\\'. $this->appName .'\Modules\\'. $module .'\\'. $this->getController();
				
				$currentController 	= new $controller();
				$method				= $currentController->execute($action);
				$parameters 		= $this->getParameters();
				$data 				= $currentController->$method($parameters);
			} else {
				$action = $this->request['page'];
				$data 	= null;
			}	 

			$view = new View($action); 
			$view->send($this->appName, $module, $data, $user);
		}
	}

?>