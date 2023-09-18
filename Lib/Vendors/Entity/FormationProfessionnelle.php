<?php 
	
	/** 
	 * Entité FormationProfessionnelle
	 *
	 * @author Toky
	 *
	 * @since 15/09/20
	 */

	namespace Entity;

	class FormationProfessionnelle
	{
		private $idFormationProfessionnelle;
		private $idOffreFormateur;
		private $debut;
		private $fin;
		
		/** 
		 * Initialisation d'une FormationProfessionnelle
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
		public function getIdFormationProfessionnelle()
		{
			return $this->idFormationProfessionnelle;
		}

		public function getIdOffreFormateur()
		{
			return $this->idOffreFormateur;
		}

		public function getDebut()
		{
			return $this->debut;
		}

		public function getFin()
		{
			return $this->fin;
		}

	// Seters
		public function setIdFormationProfessionnelle($idFormationProfessionnelle)
		{
			$this->idFormationProfessionnelle = $idFormationProfessionnelle;
		}

		public function setIdOffreFormateur($idOffreFormateur)
		{
			$this->idOffreFormateur = $idOffreFormateur;
		}

		public function setDebut($debut)
		{
			$this->debut = $debut;
		}

		public function setFin($fin)
		{
			$this->fin = $fin;
		}
	}