<?php

	/**
	 * Définir les comportements de l'application Backend ou Frontend 
	 *
	 * @author Voahirana
	 *
	 * @since 25/09/19
	 */

	namespace Core; 

	use \Core\Routes;

	abstract class Application 
	{
		private $routes;

		/**
		 * Executer l'application 
		 * 
		 * @return empty
		 */
		abstract protected function run();

		/**
		 * Récupérer les routes 
		 * 
		 * @return array
		 */
		public function getRoutes()
		{
			$this->routes = new Routes();
			return $this->routes->getListRoutes($this->appName);
		}

		/**
		 * Récupérer l'utilisateur d'un route
		 *
		 * @return string|empty
		 */
		public function getUser()
		{
			$url 	= $this->request['page'];
			$user = '';
			if (key_exists($url, $this->getRoutes())) {
				$user = $this->getRoutes()[$url]['user'];
			}
			return $user;
		}

		/**
		 * Récupérer le module par rapport a l'URL
		 *
		 * @return string|empty
		 */
		public function getModule()
		{
			$url 	= $this->request['page'];
			$module = '';
			if (key_exists($url, $this->getRoutes())) {
				$module = $this->getRoutes()[$url]['module'];
			}
			return $module;
		}

		/**
		 * Récupérer l'action par rapport a l'URL 
		 *
		 * @return string|empty
		 */
		public function getAction()
		{
			$url 	= $this->request['page'];
			$action = '';
			if (key_exists($url, $this->getRoutes())) {
				$action = $this->getRoutes()[$url]['action'];
			} 
			return $action;
		}

		/**
		 * Récupérer les paramètres
		 * 
		 * @return array|null
		 */
		public function getParameters()
		{
			unset($this->request['page']);
	        foreach ($this->request as $key => $value) {
	        	$parameters[$key] = $value;
	        }
	        if ($_POST) {
	            foreach ($_POST as $key => $value) {
	                $parameters[$key] = $value;
	            }
	        }
	        if (!isset($parameters)) {
	            $parameters = null;
	        }
	        return $parameters;
		}

		/**
		 * Récupérer le controlleur 
		 * 
		 * @return array|empty
		 */
		public function getController()
		{	
			$module 	= $this->getModule();
			$controller = '';
			if (!empty($module)) {
				$controller = ucfirst($module). 'Controller';
			}
			return $controller;
		}
	}