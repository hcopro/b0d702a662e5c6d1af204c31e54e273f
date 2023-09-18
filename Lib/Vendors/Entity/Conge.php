<?php 
	
	/** 
	 * Entité Conge
	 *
	 * @author Toky
	 *
	 * @since 20/08/20
	 */

	namespace Entity;

	class Conge
	{
		private $idConge;
		private $idEmploye;
		private $debut;
		private $heureDebut;
		private $statut;
		private $etat;
		private $fin;
		private $during;
		private $beginto;
		private $heureFin;
		private $raison;
		private $motifRefus;
		private $allow;
		
		/** 
		 * Initialisation d'un Conge
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
	    public function getIdConge()
	    {
	    	return $this->idConge;
	    }

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getDebut()
		{
			return $this->debut;
		}

		public function getHeureDebut()
		{
			return $this->heureDebut;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getEtat()
		{
			return $this->etat;
		}

		public function getFin()
		{
			return $this->fin;
		}

		public function getDuring()
		{
			return $this->during;
		}

		public function getBeginto()
		{
			return $this->beginto;
		}

		public function getHeureFin()
		{
			return $this->heureFin;
		}

		public function getRaison()
		{
			return $this->raison;
		}

		public function getMotifRefus()
		{
			return $this->motifRefus;
		}

		public function getAllow()
		{
			return $this->allow;
		}

	// Seters
		public function setIdConge($idConge)
		{
			$this->idConge = $idConge;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setDebut($debut)
		{
			$this->debut = $debut;
		}

		public function setHeureDebut($heureDebut)
		{
			$this->heureDebut = $heureDebut;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setEtat($etat)
		{
			$this->etat = $etat;
		}

		public function setFin($fin)
		{
			$this->fin = $fin;
		}

		public function setDuring($during)
		{
			$this->during = $during;
		}

		public function setBeginto($beginto)
		{
			$this->beginto = $beginto;
		}

		public function setHeureFin($heureFin)
		{
			$this->heureFin = $heureFin;
		}

		public function setRaison($raison)
		{
			$this->raison = $raison;
		}

		public function setMotifRefus($motifRefus)
		{
			$this->motifRefus = $motifRefus;
 		}

		public function setAllow($allow)
		{
			$this->allow = $allow;
 		}
	}