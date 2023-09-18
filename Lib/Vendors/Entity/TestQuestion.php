<?php 
	
   /** 
	* Entité TestQuestion
	*
	* @author Lansky
	*
	* @since 2022/01/05
	*/

	namespace Entity ;

	class TestQuestion
	{
		private $idTestQuestion;
		private $question;
		private $choiseAnswer;
		private $note;
		private $realAnswer;
		private $idTestClassification;
		
	   /** 
		* Initialisation de la classe TestQuestion
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
		public function getIdTestQuestion()
		{
			return $this->idTestQuestion;
		}

		public function getQuestion()
		{
			return $this->question;
		}
		public function getChoiseAnswer()
		{
			return $this->choiseAnswer;
		}
		public function getNote()
		{
			return $this->note;
		}
		public function getRealAnswer()
		{
			return $this->realAnswer;
		}

		public function getIdTestClassification()
		{
			return $this->idTestClassification;
		}

		// Seters
		public function setIdTestQuestion($idTestQuestion)
		{
			$this->idTestQuestion = $idTestQuestion;
		}

		public function setQuestion($question)
		{
			$this->question = $question;
		}

		public function sestChoiseAnswer($choiseAnswer)
		{
			$this->choiseAnswer = $choiseAnswer;
		}

		public function setNote($note)
		{
			$this->note = $note;
		}

		public function setRealAnswer($realAnswer)
		{
			$this->realAnswer = $realAnswer;
		}

		public function setIdTestClassification($idTestClassification)
		{
			$this->idTestClassification = $idTestClassification;
		}
	}