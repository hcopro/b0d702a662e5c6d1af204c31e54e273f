<?php 
	
   /** 
	* Entité TestClassification
	*
	* @author Lansky
	*
	* @since 01/04/2022
	*/

	namespace Entity ;

	class TestClassification
	{
		private $idTestClassification;
		private $libelle;
		private $idEntreprise;
		
	   /** 
		* Initialisation de la classe TestClassification
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
		public function getIdTestClassification()
		{
			return $this->idTestClassification;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		// Seters
		public function setIdTestClassification($idTestClassification)
		{
			$this->idTestClassification = $idTestClassification;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}
	}