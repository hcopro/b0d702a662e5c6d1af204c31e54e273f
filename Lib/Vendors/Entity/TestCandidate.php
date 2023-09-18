<?php 
	
   /**
	* Entité TestCandidate
	*
	* @author Lansky
	*
	* @since 01/04/2022
	*/

	namespace Entity;

	class TestCandidate
	{
		private $idTestCandidate;
		private $libelle;
		private $contents;
		private $idTestCognitive;
		private $idTestPersonality;
		private $idEntreprisePoste;
		
	   /** 
		* Initialisation de la classe TestCandidate
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
		public function getIdTestCandidate()
		{
			return $this->idTestCandidate;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getContents()
		{
			return unserialize($this->contents);
		}

		public function getIdTestCognitive()
		{
			return $this->idTestCognitive;
		}

		public function getIdTestPersonality()
		{
			return $this->idTestPersonality;
		}

		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		
		// Seters
		public function setIdTestCandidate($idTestCandidate)
		{
			$this->idTestCandidate = $idTestCandidate;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setContents($contents)
		{
			$this->contents = $contents;
		}

		public function setIdTestCognitive($idTestCognitive)
		{
			$this->idTestCognitive = $idTestCognitive;
		}

		public function setIdTestPersonality($idTestPersonality)
		{
			$this->idTestPersonality = $idTestPersonality;
		}

		public function setIdEntreprisePoste($idEntreprisePoste)
		{
			$this->idEntreprisePoste = $idEntreprisePoste;
		}
	}
