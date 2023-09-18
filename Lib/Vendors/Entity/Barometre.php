<?php 
	
   /**
	* Entité Barometre
	*
	* @author Lansky
	*
	* @since 11/01/2022
	*/

	namespace Entity;

	class Barometre
	{
		private $idBarometre;
		private $libelle;
		private $isArchived;
		private $contents;
		private $idEntreprise;
		
	   /** 
		* Initialisation de la classe Barometre
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
		public function getIdBarometre()
		{
			return $this->idBarometre;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getIsArchived()
		{
			return $this->isArchived;
		}

		public function getContents()
		{
			return unserialize($this->contents);
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		// Seters
		public function setIdBarometre($idBarometre)
		{
			$this->idBarometre = $idBarometre;
		}
		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}
		public function setIsArchived($isArchived)
		{
			$this->isArchived = $isArchived;
		}

		public function setContents($contents)
		{
			$this->contents = $contents;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}
	}