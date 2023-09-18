<?php 
	
	/** 
	 * Entité Experience
	 *
	 * @author Voahirana 
	 *
	 * @since 09/10/19
	*/

	namespace Entity;

	class Experience
	{
		private $idExperience;
		private $idCandidat;
		// private $idSousDomaine;
		private $dateDebut;  
		private $dateFin; 
		private $poste; 
		private $entreprise;
		private $description;

		/** 
		 * Initialisation d'une Experience
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
		public function getIdExperience()
		{
			return $this->idExperience;
		}

		public function getIdCandidat()
		{
			return $this->idCandidat;
		}

		// public function getIdSousDomaine()
		// {
		// 	return $this->idSousDomaine;
		// }

		public function getDateDebut()
		{
			return $this->dateDebut;
		}

		public function getDateFin()
		{
			return $this->dateFin;
		}

		public function getPoste()
		{
			return $this->poste;
		}

		public function getEntreprise()
		{
			return $this->entreprise;
		}

		public function getDescription()
		{
			return $this->description;
		}

		// Seters
		public function setIdExperience($idExperience)
		{
			$this->idExperience = $idExperience;
		}

		public function setIdCandidat($idCandidat)
		{
			$this->idCandidat = $idCandidat;
		}

		// public function setIdSousDomaine($idSousDomaine)
		// {
		// 	$this->idSousDomaine = $idSousDomaine;
		// }

		public function setDateDebut($dateDebut)
		{
			$this->dateDebut = $dateDebut;
		}

		public function setDateFin($dateFin)
		{
			$this->dateFin = $dateFin;
		}

		public function setPoste($poste)
		{
			$this->poste = $poste;
		}

		public function setEntreprise($entreprise)
		{
			$this->entreprise = $entreprise;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

	}