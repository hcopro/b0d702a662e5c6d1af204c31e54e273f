<?php 
	
	/** 
	 * Entité EmployeEvaluation
	 *
	 * @author Lansky
	 *
	 * @since 10/07/21
	 */

	namespace Entity;

	class EmployeEvaluation
	{
		private $idEmplEval;
		private $idEvaluateur;
		private $idTrait;
		private $date;
		private $idItem;
		private $idQuestionnaire;
		private $point;
		private $note;
		
		/** 
		 * Initialisation d'une EmployeEvaluation
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
		public function getIdEvaluation()
		{
			return $this->idEmplEval;
		}

		public function getTrait()
		{
			return $this->idTrait;
		}

		public function getDate()
		{
			return $this->date;
		}

		public function getItem()
		{
			return $this->idItem;
		}

		public function getIdQuestionnaire()
		{
			return $this->idQuestionnaire;
		}

		public function getPoint()
		{
			return $this->point;
		}
		
		public function getNote()
		{
			return $this->note;
		}

	// Seters
		public function setIdEvaluation($idEmplEval)
		{
			$this->idEmplEval = $idEmplEval;
		}

		public function setIdTrait($idTrait)
		{
			$this->idTrait = $idTrait;
		}

		public function setItem($idItem)
		{
			$this->idItem = $idItem;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setIdQuestionnaire($idQuestionnaire)
		{
			$this->idQuestionnaire = $idQuestionnaire;
		}
		
		public function setIdQuestionnaire($point)
		{
			$this->point = $point;
		}

		public function setIdQuestionnaire($note)
		{
			$this->note = $note;
		}
	}