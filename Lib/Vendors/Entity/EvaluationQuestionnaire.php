<?php 
	
	/** 
	 * Entité EvaluationQuestionnaire
	 *
	 * @author Lansky
	 *
	 * @since 09/07/21
	 */

	namespace Entity;

	class EvaluationQuestionnaire
	{
		private $idQuestion;
		private $libelle;
		private $code;
		private $interpretation;
		private $idCategorie;
		private $pointNiveau;
		private $idEntreprise;
		
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
		public function getIdQuestion()
		{
			return $this->idQuestion;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getCode()
		{
			return $this->code;
		}

		public function getInterpretation()
		{
			return unserialize($this->interpretation);
		}

		public function getPointNiveau()
		{
			return unserialize($this->pointNiveau);
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdCategorie()
		{
			return $this->idCategorie;
		}

		// Seters
		public function setIdQuestion($idQuestion)
		{
			$this->idQuestion = $idQuestion;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setCode($code)
		{
			$this->code = $code;
		}

		public function setInterpretation($interpretation)
		{
			$this->interpretation = $interpretation;
		}

		public function setPointNiveau($pointNiveau)
		{
			$this->pointNiveau = $pointNiveau;
		}
		
		public function setIdCategorie($idCategorie)
		{
			$this->idCategorie = $idCategorie;
		}
		
		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}
	}