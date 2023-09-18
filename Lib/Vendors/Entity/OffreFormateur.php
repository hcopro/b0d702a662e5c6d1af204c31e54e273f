<?php 
	
	/** 
	 * Entité OffreFormateur
	 *
	 * @author Toky
	 *
	 * @since 15/09/20
	 */

	namespace Entity;

	class OffreFormateur
	{
		private $idOffreFormateur;
		private $idFormateur;
		private $idThemeFormation;
		private $cout;
		private $statut;
		
		/** 
		 * Initialisation d'un OffreFormateur
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
		public function getIdOffreFormateur()
		{
			return $this->idOffreFormateur;
		}

		public function getIdFormateur()
		{
			return $this->idFormateur;
		}

		public function getIdThemeFormation()
		{
			return $this->idThemeFormation;
		}

		public function getCout()
		{
			return $this->cout;
		}

		public function getStatut()
		{
			return $this->statut;
		}

	// Seters
		public function setIdOffreFormateur($idOffreFormateur)
		{
			$this->idOffreFormateur = $idOffreFormateur;
		}

		public function setIdFormateur($idFormateur)
		{
			$this->idFormateur = $idFormateur;
		}

		public function setIdThemeFormation($idThemeFormation)
		{
			$this->idThemeFormation = $idThemeFormation;
		}

		public function setCout($cout)
		{
			$this->cout = $cout;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut; 
		}
	}