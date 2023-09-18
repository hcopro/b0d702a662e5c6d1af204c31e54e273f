<?php 
	
   /** 
	* Entité TestCognitive
	*
	* @author Lansky
	*
	* @since 01/04/2022
	*/

	namespace Entity;

	class TestCognitive
	{
		private $idTestCognitive;
		private $libelle;
		private $contents;
		private $idEntreprise;
		
	   /** 
		* Initialisation de la classe TestCognitive
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
		public function getIdTestCognitive()
		{
			return $this->idTestCognitive;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getContents()
		{
			return unserialize($this->contents);
		}

		// Seters
		public function setIdTestCognitive($idTestCognitive)
		{
			$this->idTestCognitive = $idTestCognitive;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setContents($contents)
		{
			$this->contents = $contents;
		}
	}