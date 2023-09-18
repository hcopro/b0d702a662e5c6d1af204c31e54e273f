<?php 
	
	/** 
	 * Entité ParametreHeure
	 *
	 * @author Toky
	 *
	 * @since 21/10/20
	 */

	namespace Entity;

	class ParametreHeure
	{
		private $idParametreHeure;
		private $idEmploye;
		private $heureNormale;
		private $heureSupplementaireActive;
		private $heureSupplementaireImposable;
		private $heureNuitImposable;
		private $travailNuitHabituel;
		private $heureDimancheImposable;
		private $travailDimancheHabituel;
		private $heureFerieImposable;
		
		/** 
		 * Initialisation d'un paramètre d'heure d'un employe
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
		public function getIdParametreHeure()
		{
			return $this->idParametreHeure;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getHeureNormale()
		{
			return $this->heureNormale;
		}

		public function getHeureSupplementaireActive()
		{
			return $this->heureSupplementaireActive;
		}

		public function getHeureSupplementaireImposable()
		{
			return $this->heureSupplementaireImposable;
		}

		public function getHeureNuitImposable()
		{
			return $this->heureNuitImposable;
		}

		public function getTravailNuitHabituel()
		{
			return $this->travailNuitHabituel;
		}

		public function getHeureDimancheImposable()
		{
			return $this->heureDimancheImposable;
		}

		public function getTravailDimancheHabituel()
		{
			return $this->travailDimancheHabituel;
		}

		public function getHeureFerieImposable()
		{
			return $this->heureFerieImposable;
		}

	// Seters
		public function setIdParametreHeure($idParametreHeure)
		{
			$this->idParametreHeure = $idParametreHeure;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setHeureNormale($heureNormale)
		{
			$this->heureNormale = $heureNormale;
		}

		public function setHeureSupplementaireActive($heureSupplementaireActive)
		{
			$this->heureSupplementaireActive = $heureSupplementaireActive;
		}

		public function setHeureSupplementaireImposable($heureSupplementaireImposable)
		{
			$this->heureSupplementaireImposable = $heureSupplementaireImposable;
		}

		public function setHeureNuitImposable($heureNuitImposable)
		{
			$this->heureNuitImposable = $heureNuitImposable;
		}

		public function setTravailNuitHabituel($travailNuitHabituel)
		{
			$this->travailNuitHabituel = $travailNuitHabituel;
		}

		public function setHeureDimancheImposable($heureDimancheImposable)
		{
			$this->heureDimancheImposable = $heureDimancheImposable;
		}

		public function setTravailDimancheHabituel($travailDimancheHabituel)
		{
			$this->travailDimancheHabituel = $travailDimancheHabituel;
		}

		public function setHeureFerieImposable($heureFerieImposable)
		{
			$this->heureFerieImposable = $heureFerieImposable;
		}
	}