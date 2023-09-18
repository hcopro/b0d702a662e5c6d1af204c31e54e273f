<?php 
	
	/** 
	 * Entité EmployePermission
	 *
	 * @author Toky
	 *
	 * @since 23/07/20
	 */

	namespace Entity;

	class EmployePermission
	{
		private $idEmployePermission;
		private $idEmploye;
		private $idEntreprisePermission;
		private $statut;
		private $date;
		private $motifRefus;
		private $duree;
		private $idMessage;
		private $settled;
		
		/** 
		 * Initialisation d'une permission d'un employe
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
		public function getIdEmployePermission()
		{
			return $this->idEmployePermission;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdEntreprisePermission()
		{
			return $this->idEntreprisePermission;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getDate()
		{
			return $this->date;
		}

		public function getMotifRefus()
		{
			return $this->motifRefus;
		}

		public function getDuree()
		{
			return $this->duree;
		}

		public function getIdMessage()
		{
			return $this->idMessage;
		}

		public function getSettled()
		{
			return $this->settled;
		}

	// Seters
		public function setIdEmployePermission($idEmployePermission)
		{
			$this->idEmployePermission = $idEmployePermission;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdEntreprisePermission($idEntreprisePermission)
		{
			$this->idEntreprisePermission = $idEntreprisePermission;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setMotifRefus($motifRefus)
		{
			$this->motifRefus = $motifRefus;
		}

		public function setDuree($duree)
		{
			$this->duree = $duree;
		}

		public function setIdMessage($idMessage)
		{
			$this->idMessage = $idMessage;
		}

		public function setSettled($settled)
		{
			$this->settled = $settled;
		}
		
	}