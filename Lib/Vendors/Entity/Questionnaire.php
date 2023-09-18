<?php 
	
	/** 
	 * Entité Questionnaire
	 *
	 * @author Lansky
	 *
	 * @since 09/07/21
	 */

	namespace Entity;

	class Questionnaire
	{
		private $idQuestionnaire;
		private $libelle;
		private $idCategorie;
		
		/** 
		 * Initialisation d'une Questionnaire
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
		public function getIdQuestionnaire()
		{
			return $this->idQuestionnaire;
		}

		public function getQuestion()
		{
			return $this->libelle;
		}

		public function getidCategorie()
		{
			return $this->idCategorie;
		}


	// Seters
		public function setIdQuestionnaire($idQuestionnaire)
		{
			$this->idQuestionnaire = $idQuestionnaire;
		}

		public function setQuestion($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setidCategorie($idCategorie)
		{
			$this->idCategorie = $idCategorie;
		}
	}