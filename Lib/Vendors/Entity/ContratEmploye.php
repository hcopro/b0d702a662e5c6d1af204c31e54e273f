<?php 
	
	/** 
	 * Entité ContratEmploye
	 *
	 * @author Voahirana 
	 *
	 * @since 09/04/20
	 */

	namespace Entity;

	class ContratEmploye
	{
		private $idContratEmploye;
		private $idEmploye; 
		private $idServicePoste; 
		private $type; 
		private $dateDebut;
		private $dateFin;  
		private $statut;
		private $precedent;
		private $suivant;
		private $essai;
		private $principal;
		

		/** 
		 * Initialisation d'un ContratEmploye
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
		public function getIdContratEmploye()
		{
			return $this->idContratEmploye;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdServicePoste()
		{
			return $this->idServicePoste;
		}

		public function getType()
		{
			return $this->type;
		}

		public function getDateDebut()
		{
			return $this->dateDebut;
		}

		public function getDateFin()
		{
			return $this->dateFin;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getPrecedent()
		{
			return $this->precedent;
		}

		public function getSuivant()
		{
			return $this->suivant;
		}

		public function getEssai()
		{
			return $this->essai;
		}

		public function getPrincipal()
		{
			return $this->principal;
		}


	// Seters
		public function setIdContratEmploye($idContratEmploye)
		{
			$this->idContratEmploye = $idContratEmploye;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdServicePoste($idServicePoste)
		{
			$this->idServicePoste = $idServicePoste;
		}

		public function setType($type)
		{
			$this->type = $type;
		}

		public function setDateDebut($dateDebut)
		{
			$this->dateDebut = $dateDebut;
		}

		public function setDateFin($dateFin)
		{
			$this->dateFin = $dateFin;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setSuivant($suivant)
		{
			$this->suivant = $suivant;
		}

		public function setPrecedent($precedent)
		{
			$this->precedent = $precedent;
		}

		public function setEssai($essai)
		{
			$this->essai = $essai;
		}

		public function setPrincipal($principal)
		{
			$this->principal = $principal;
		}

	}