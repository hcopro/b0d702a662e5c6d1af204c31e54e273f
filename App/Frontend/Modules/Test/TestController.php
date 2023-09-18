<?php

	/**
	 * Controlleur du Module Test dans FrontEnd
	 *
	 * @author Tokiniaina
	 *
	 * @since 08/06/2020 
	 */

	namespace App\Frontend\Modules\Test ;

	use \Core\BackController;
	
    use \Model\ManagerTest;


	class TestController extends BackController
	{

		/**
		*
		* récupérer l'homme
		*
		* @return array
		*
		*/
		private function getHomme()
		{
			$homme = array(
				'nom' => 'RAKOTO',
				'prenom' => 'Jean',
				'age' => 40
			);
			return $homme ;
		} 

		/**
		*
		* exécuter le test voulu
		*
		* @return valeur de retour
		*
		*/
		public function executeTester()
		{
			return $this->getHomme();
		}

		/**
		*
		*MAJ homme
		*
		*@return array
		*
		*/
		public function executeModifierHomme($parameters)
		{
			/*$parameters = array(
				"nom"=>"RABE",
				"prenom"=>"Naivo",
				"age"=>50
			);*/
			$homme = $this->getHomme();
			if(isset($parameters)){
				$homme["nom"] = $parameters["nom"];
				$homme["prenom"] = $parameters["prenom"];
				$homme["age"] = $parameters["age"];
			}
			return $homme ;
		}


	}
