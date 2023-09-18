<?php 
	
	/** 
	 * Entité Candidature
	 *
	 * @author Voahirana 
	 *
	 * @since 21/10/19
	 */

	namespace Entity;

	class Candidature
	{
		private $idCandidature;
		private $idOffre; 
		private $idCandidat; 
		private $dateCandidature;
		private $statut;

		/** 
		 * Initialisation d'un Candidature
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
		public function getIdCandidature()
		{
			return $this->idCandidature;
		}

		public function getIdOffre()
		{
			return $this->idOffre;
		}

		public function getIdCandidat()
		{
			return $this->idCandidat;
		}

		public function getDateCandidature()
		{
			return $this->dateCandidature;
		}

		public function getStatut()
		{
			return $this->statut;
		}

	// Seters
		public function setIdCandidature($idCandidature)
		{
			$this->idCandidature = $idCandidature;
		}

		public function setIdOffre($idOffre)
		{
			$this->idOffre = $idOffre;
		}

		public function setIdCandidat($idCandidat)
		{
			$this->idCandidat = $idCandidat;
		}

		public function setDateCandidature($dateCandidature)
		{
			$this->dateCandidature = $dateCandidature;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

	}