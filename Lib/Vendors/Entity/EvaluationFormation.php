<?php 
	
	/** 
	 * Entité EvaluationFormation
	 *
	 * @author Toky
	 *
	 * @since 15/09/20
	 */

	namespace Entity;

	class EvaluationFormation
	{
		private $idEvaluationFormation;
		private $idFormationProfessionnelle;
		private $idEmployeFormation;
		private $note;
		private $remarque;
		private $evaluateur;
		
		/** 
		 * Initialisation d'une EvaluationFormation
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
		public function getIdEvaluationFormation()
		{
			return $this->idEvaluationFormation;
		}

		public function getEvaluateur()
		{
			return $this->evaluateur;
		}

		public function getIdFormationProfessionnelle()
		{
			return $this->idFormationProfessionnelle;
		}

		public function getIdEmployeFormation()
		{
			return $this->idEmployeFormation;
		}

		public function getNote()
		{
			return $this->note;
		}

		public function getRemarque()
		{
			return $this->remarque;
		}

	// Seters
		public function setIdEvaluationFormation($idEvaluationFormation)
		{
			$this->idEvaluationFormation = $idEvaluationFormation;
		}

		public function setEvaluateur($evaluateur)
		{
			$this->evaluateur = $evaluateur;
		}

		public function setIdFormationProfessionnelle($idFormationProfessionnelle)
		{
			$this->idFormationProfessionnelle = $idFormationProfessionnelle;
		}

		public function setIdEmployeFormation($idEmployeFormation)
		{
			$this->idEmployeFormation = $idEmployeFormation;
		}

		public function setNote($note)
		{
			$this->note = $note;
		}

		public function setRemarque($remarque)
		{
			$this->remarque = $remarque;
		}
	}