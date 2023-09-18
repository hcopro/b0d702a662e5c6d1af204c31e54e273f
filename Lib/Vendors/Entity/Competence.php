<?php 
	
    /**
	 * Entité Competence
	 *
	 * @author Billy
	 *
	 * @since 15/12/2022
	*/

	namespace Entity;

	class Competence
	{
		private $idCompetence;
		private $competence;
		private $idCandidat;
		
	    /** 
		 * Initialisation 
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
		public function getIdCompetence()
		{
			return $this->idCompetence;
		}

		public function getCompetence()
		{
			return $this->competence;
		}

		public function getIdCompetence()
		{
			return $this->idCompetence;
		}

		// Seters
		public function setIdCompetence($idCompetence)
		{
			$this->idCompetence = $idCompetence;
		}
		public function setCompetence($competence)
		{
			$this->competence = $competence;
		}
	}