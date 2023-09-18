<?php 
	
   /** 
	* Entité EvaluationEmploye
	*
	* @author Lansky
	*
	* @since 10/08/21
	*/

	namespace Entity;

	class EvaluationEmploye
	{
		private $idEvaluationEmploye;
		private $libelle;
		private $idEntreprise;
		private $idEvaluation;
		private $idEvaluee;
		private $evaluateur;
		private $dateCreation;
		private $isArchived;
		private $isAnswered;
		private $isDeleted;
		private $remarque;
		private $point;
		private $nombreReponse;
		private $moyenne;
		
	   /** 
		* Initialisation d'une Evaluation employée
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
		public function getIdEvaluationEmploye()
		{
			return $this->idEvaluationEmploye;
		}

		public function getIdEvaluation()
		{
			return $this->idEvaluation;
		}

		public function getIdEvaluee()
		{
			return $this->idEvaluee;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getEvaluateur()
		{
			return unserialize($this->evaluateur);
		}

		public function getIsArchived()
		{
			return $this->isArchived;
		}

		public function getIsAnswered()
		{
			return $this->isAnswered;
		}

		public function getIsDeleted()
		{
			return $this->isDeleted;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getDateCreation()
		{
			return $this->writeDate($this->dateCreation);
		}

		public function getRemarque()
		{
			return $this->remarque;
		}

		public function getPoint()
		{
			return unserialize($this->point);
		}

		public function getMoyenne()
		{
			return $this->moyenne;
		}

		public function getNombreReponse()
		{
			return $this->nombreReponse;
		}

		// Seters
		public function setIdEvaluationEmploye($idEvaluationEmploye)
		{
			$this->idEvaluationEmploye = $idEvaluationEmploye;
		}

		public function setIdEvaluation($idEvaluation)
		{
			$this->idEvaluation = $idEvaluation;
		}

		public function setIdEvaluee($idEvaluee)
		{
			$this->idEvaluee = $idEvaluee;
		}

		public function setEvaluateur($evaluateur)
		{
			$this->evaluateur = $evaluateur;
		}

		public function setIsArchived($isArchived)
		{
			$this->isArchived = $isArchived;
		}

		public function setIsAnswered($isAnswered)
		{
			$this->isAnswered = $isAnswered;
		}

		public function setIsDeleted($isDeleted)
		{
			$this->isDeleted = $isDeleted;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setRemarque($remarque)
		{
			$this->remarque = $remarque;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setMoyenne($moyenne)
		{
			$this->moyenne = $moyenne;
		}

		public function setNombreReponse($nombreReponse)
		{
			$this->nombreReponse = $nombreReponse;
		}

		public function setDateCreation($dateCreation)
		{
			$this->dateCreation = $dateCreation;
		}

		public function setPoint($point)
		{
			$this->point = $point;
		}
		
        /**
         * Convertir un entier en mois
         *
         * @param int $month
         *
         * @return string
         */
        private function getMonthLetter($month)
        {
            $month  = intval($month);
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return $months[$month - 1];
        }

        /**
         * Convertir une date en date complète
         *
         * @param date $date la date à convertir
         *
         * @return string
         */
        private function writeDate($date)
        {
            $tmp = explode('-', $date);
            $temps = '';
            if (count($tmp) == 3) {
            	$tmp[2] = explode(' ', $tmp[2]);
            	$time = explode(':',$tmp[2][1]);
            	if (is_array($time)) {
            		$temps = ' à '. $time[0].'h '.$time[1].'min '.$time[2].'sec';
            	}
                return $tmp[2][0] . ' ' . $this->getMonthLetter($tmp[1]) . ' ' . $tmp[0];    
            } else {
                return $date;
            }
        }
	}