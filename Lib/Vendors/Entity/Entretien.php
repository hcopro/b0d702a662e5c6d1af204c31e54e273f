<?php 
	
	/** 
	 * Entité Entretien
	 *
	 * @author Voahirana 
	 *
	 * @since 20/10/19
	 */

	namespace Entity;

	class Entretien
	{
		private $idEntretien;
		private $idCandidature; 
		private $lieu; 
		private $date;
		private $heure;  
		private $statut;
		private $nbFois;
		private $idNiveauEntretien;

		/** 
		 * Initialisation d'un Entretien
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
		public function getIdEntretien()
		{
			return $this->idEntretien;
		}

		public function getIdCandidature()
		{
			return $this->idCandidature;
		}

		public function getLieu()
		{
			return $this->lieu;
		}

		public function getDate()
		{
			return $this->date;
		}

		public function getHeure()
		{
			return $this->heure;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getNbFois()
		{
			return $this->nbFois;
		}

		public function getIdNiveauEntretien()
		{
			return $this->idNiveauEntretien;
		}

	// Seters
		public function setIdEntretien($idEntretien)
		{
			$this->idEntretien = $idEntretien;
		}

		public function setIdCandidature($idCandidature)
		{
			$this->idCandidature = $idCandidature;
		}

		public function setLieu($lieu)
		{
			$this->lieu = $lieu;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setHeure($heure)
		{
			$this->heure = $heure;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setNbFois($nbFois)
		{
			$this->nbFois = $nbFois;
		}

		public function setIdNiveauEntretien($idNiveauEntretien)
		{
			$this->idNiveauEntretien = $idNiveauEntretien;
		}

	}