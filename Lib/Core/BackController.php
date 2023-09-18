<?php 
	
	/** 
	 * Classe abstraite de tous les controlleurs
	 *
	 * @author Voahirana 
	 *
	 * @since 25/09/19
	 */

	namespace Core;

	abstract class BackController
	{
		/** 
		 * Appeller une méthode à exécuter dans le controlleur
		 *
		 * @param string $action Nom d'une methode à exécuter
		 *
		 * @return string
		 */
		public function execute($action)
		{
			$method = 'execute'.ucfirst($action);
			return $method;
		}
	}
	