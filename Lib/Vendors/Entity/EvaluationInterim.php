<?php 
	
	/** 
	 * Entité EvaluationInterim
	 *
	 * @author Toky
	 *
	 * @since 10/09/20
	 */

	namespace Entity;

	class EvaluationInterim
	{
		private $idEvaluationInterim;
		private $note;
		private $remarque;
		private $evaluateur;
		private $idInterim;
		
		/** 
		 * Initialisation d'une évaluation d'un intérimaire
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
		public function getIdEvaluationInterim()
		{
			return $this->idEvaluationInterim;
		}

		public function getNote()
		{
			return $this->note;
		}

		public function getRemarque()
		{
			return $this->remarque;
		}

		public function getEvaluateur()
		{
			return $this->evaluateur;
		}

		public function getIdInterim()
		{
			return $this->idInterim;
		}

	// Seters
		public function setIdEvaluationInterim($idEvaluationInterim)
		{
			$this->idEvaluationInterim = $idEvaluationInterim;
		}

		public function setNote($note)
		{
			if ($note > 10) {
				/* la note max est 10 */
				$this->note = 10;
			} elseif ($note < 0) {
				/* la note min est 0 */
				$this->note = 0;
			} else {
				$this->note = $note;
			}
		}

		public function setRemarque($remarque)
		{
			$this->remarque = $remarque;
		}

		public function setEvaluateur($evaluateur)
		{
			$this->evaluateur = $evaluateur;
		}

		public function setIdInterim($idInterim)
		{
			$this->idInterim = $idInterim;
		}
	}