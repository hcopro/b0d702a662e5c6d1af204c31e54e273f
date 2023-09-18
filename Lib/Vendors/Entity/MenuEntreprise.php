<?php 
	
   /** 
	* Entité MenuEntreprise
	*
	* @author Lansky
	*
	* @since 02/12/21
	*/

	namespace Entity;

	class MenuEntreprise
	{
		private $idMenu;
		private $libelle;
		private $containt;
		private $idEntreprise;
		
	   /** 
		* Initialisation d'un MenuEntreprise
		*
		* @param array $data Données à intialiser 
		*
		* @return empty
		*/
	    public function __construct($data = array())
	    {
	        if(!empty($data)) {
	        	$this->hydrate($data);
	        }	            
	    }

	   /** 
		* Remplir la structure d'un objet 
		*
		* @param array $data Données à remplir
		*
		* @return empty
		*/
	    public function hydrate($data)
	    {
	        foreach ($data as $attribut => $data) {
	            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
	            if (is_callable(array($this, $method))) {
	                $this->$method($data);
	            }
	        }
	    }

	   /** 
		* Convertir un objet en tableau 
		*
		* @return array
		*/
	    public function toArray()
	    {
	    	return get_object_vars($this);
	    }

		// Getters
		public function getIdMenu()
		{
			return $this->idMenu;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getContaint()
		{
			// CECI EST LA SOLUTION SI ERREUR DE UNSERIALIZE APPARAIT
  		//       $data = preg_replace_callback(
			//     '!s:(\d+):"(.*?)";!', 
			//     function($m) { 
			//         return 's:'.strlen($m[2]).':"'.$m[2].'";'; 
			//     }, 
			//     $this->containt);

			// var_dump(unserialize($data));
			return unserialize($this->containt);
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		// Seters
		public function setIdMenu($idMenu)
		{
			$this->idMenu = $idMenu;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setContaint($containt)
		{
			$this->containt = $containt;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}
	}