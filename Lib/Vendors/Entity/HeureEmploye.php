<?php 
	
	/** 
	 * Entité HeureEmploye
	 *
	 * @author Toky
	 *
	 * @since 21/10/20
	 */

	namespace Entity;

	class HeureEmploye
	{
		private $idHeureEmploye;
		private $idEmploye;
		private $mois;
		private $heureNormale;
		private $heureSupplementaire;
		private $heureNuit;
		private $heureDimanche;
		private $heureFerie;
		private $jourConge;
		
		/** 
		 * Initialisation des heures d'un employe
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
		public function getIdHeureEmploye()
		{
			return $this->idHeureEmploye;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getMois()
		{
			return $this->mois;
		}

		public function getHeureNormale()
		{
			return $this->heureNormale;
		}

		public function getHeureSupplementaire()
		{
			return $this->heureSupplementaire;
		}

		public function getHeureNuit()
		{
			return $this->heureNuit;
		}

		public function getHeureFerie()
		{
			return $this->heureFerie;
		}

		public function getHeureDimanche()
		{
			return $this->heureDimanche;
		}

		public function getJourConge()
		{
			return $this->jourConge;
		}

	// Seters
		public function setIdHeureEmploye($idHeureEmploye)
		{
			$this->idHeureEmploye = $idHeureEmploye;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setMois($mois)
		{
			$this->mois = $mois;
		}

		public function setHeureNormale($heureNormale)
		{
			$this->heureNormale = $heureNormale;
		}

		public function setHeureSupplementaire($HeureSupplementaire)
		{
			$this->heureSupplementaire = $heureSupplementaire;
		}

		public function setHeureNuit($heureNuit)
		{
			$this->heureNuit = $heureNuit;
		}

		public function setHeureFerie($heureFerie)
		{
			$this->heureFerie = $heureFerie;
		}

		public function setHeureDimanche($heureDimanche)
		{
			$this->heureDimanche = $heureDimanche;
		}

		public function setJourConge($jourConge)
		{
			$this->jourConge = $jourConge;
		}
	}