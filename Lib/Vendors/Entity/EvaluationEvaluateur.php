<?php 
	
   /** 
	* Entité EvaluationEvaluateur
	*
	* @author Lansky
	*
	* @since 31/08/21
	*/

	namespace Entity;

	class EvaluationEvaluateur
	{
		private $idEvaluationEvaluateur;
		private $libelle;
		private $idEvaluateur;
		private $idEvaluee;
		private $donneeEvaluation;
		private $dateRepondre;
		private $isArchived;
		private $isDeleted;
		private $moyenne;
		private $idEntreprise;
		private $idEntreprisePoste;
		
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
		public function getIdEvaluationEvaluateur()
		{
			return $this->idEvaluationEvaluateur;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getIdEvaluateur()
		{
			return $this->idEvaluateur;
		}

		public function getIdEvaluee()
		{
			return $this->idEvaluee;
		}

		public function getIdEvaluationEmploye()
		{
			return $this->idEvaluationEmploye;
		}

		public function getDonneeEvaluation()
		{
			return unserialize($this->donneeEvaluation);
		}

		public function getDateRepondre()
		{
			return $this->writeDate($this->dateRepondre);
		}

		public function getIsArchived()
		{
			return $this->isArchived;
		}

		public function getIsDeleted()
		{
			return $this->isDeleted;
		}

		public function getMoyenne()
		{
			return $this->moyenne;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		// Seters
		public function setIdEvaluationEvaluateur($idEvaluationEvaluateur)
		{
			$this->idEvaluationEvaluateur = $idEvaluationEvaluateur;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setIdEvaluateur($idEvaluateur)
		{
			$this->idEvaluateur = $idEvaluateur;
		}

		public function setIdEvaluee($idEvaluee)
		{
			$this->idEvaluee = $idEvaluee;
		}

		public function setIdEvaluationEmploye($idEvaluationEmploye)
		{
			$this->idEvaluationEmploye = $idEvaluationEmploye;
		}

		public function setDonneeEvaluation($donneeEvaluation)
		{
			$this->donneeEvaluation = $donneeEvaluation;
		}

		public function setDateRepondre($dateRepondre)
		{
			$this->dateRepondre = $dateRepondre;
		}

		public function setIsArchived($isArchived)
		{
			$this->isArchived = $isArchived;
		}

		public function setIsDeleted($isDeleted)
		{
			$this->isDeleted = $isDeleted;
		}

		public function setMoyenne($moyenne)
		{
			$this->moyenne = $moyenne;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setIdEntreprisePoste($idEntreprisePoste)
		{
			$this->idEntreprisePoste = $idEntreprisePoste;
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