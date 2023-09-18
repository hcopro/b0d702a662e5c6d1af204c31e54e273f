<?php 
	
	/** 
	 * Entité Renouvellement
	 *
	 * @author Toky 
	 *
	 * @since 16/06/20
	 */

	namespace Entity;

	class Renouvellement
	{
		private $idRenouvellement;
		private $dateDebut;
		private $dateFin;
		private $idContratEmploye;
		private $statut ;
		
		/** 
		 * Initialisation d'un Renouvellement
		 *
		 * @param array $data Données à intialiser 
		 *
		 * @return empty
		 */
	    public function __construct($data = array())
	    {
	        if (!empty($data)) {
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
		public function getIdRenouvellement()
		{
			return $this->idRenouvellement;
		}
		public function getDateDebut()
		{
			return $this->dateDebut;
		}
		public function getDateFin()
		{
			return $this->dateFin;
		}
		public function getIdContratEmploye()
		{
			return $this->idContratEmploye;
		}
		public function getStatut()
		{
			return $this->statut;
		}

	// Seters
		public function setIdRenouvellement($idRenouvellement)
		{
			$this->idRenouvellement = $idRenouvellement;
		}
		public function setDateDebut($dateDebut)
		{
			$this->dateDebut = $dateDebut;
		}
		public function setDateFin($dateFin)
		{
			$this->dateFin = $dateFin;
		}
		public function setIdContratEmploye($idContratEmploye)
		{
			$this->idContratEmploye = $idContratEmploye;
		}
		public function setStatut($statut)
		{
			$this->statut = $statut;
		}
	}