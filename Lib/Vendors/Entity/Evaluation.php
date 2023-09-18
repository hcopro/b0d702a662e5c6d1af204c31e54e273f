<?php 
	
   /** 
	* Entité Evaluation
	*
	* @author Lansky
	*
	* @since 10/08/21
	*/

	namespace Entity;

	class Evaluation
	{
		private $idEvaluation;
		private $libelle;
		private $dateCreation;
		private $dateModif;
		private $idEntreprisePoste;
		private $category;
		private $idEntreprise;
		
	   /** 
		* Initialisation d'une Evaluation
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
			return $this->idEvaluation;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getDateCreation()
		{
			return $this->writeDate($this->dateCreation);
		}

		public function getDateModif()
		{
			return $this->writeDate($this->dateModif);
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		public function getCategory()
		{
			return unserialize($this->category);
		}

		// Seters
		public function setIdEvaluation($idEvaluation)
		{
			$this->idEvaluation = $idEvaluation;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setDateCreation($dateCreation)
		{
			$this->dateCreation = $dateCreation;
		}

		public function setDateModif($dateModif)
		{
			$this->dateModif = $dateModif;
		}

		public function setCategory($category)
		{
			$this->category = $category;
		}

		public function setIdParent($idParent)
		{
			$this->idParent = $idParent;
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