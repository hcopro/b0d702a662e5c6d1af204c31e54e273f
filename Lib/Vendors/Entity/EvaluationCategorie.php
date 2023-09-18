<?php 
	
	/** 
	 * Entité EvaluationCategorie
	 *
	 * @author Lansky
	 *
	 * @since 12/07/21
	 */

	namespace Entity;

	class EvaluationCategorie
	{
		private $idCategorie;
		private $libelle;
		private $code;
		private $description;
		private $idEntreprise;
		/** 
		 * Initialisation d'une IdCategorie
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
		public function getIdCategorie()
		{
			return $this->idCategorie;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getCode()
		{
			return $this->code;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		// Seters
		public function setIdCategorie($idCategorie)
		{
			$this->idCategorie = $idCategorie;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setCode($code)
		{
			$this->code = $code;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}
		
		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}
	}