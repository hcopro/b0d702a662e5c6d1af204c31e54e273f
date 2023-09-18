<?php 
	
	/** 
	 * Entité Diplome
	 *
	 * @author Voahirana 
	 *
	 * @since 09/10/19
	 */

	namespace Entity;

	class Diplome
	{
		private $idDiplome;
		private $idCandidat;
		private $idDomaine;
		private $idNiveauEtude;
		private $dateObtention;  
		private $designation; 
		private $ecole; 

		/** 
		 * Initialisation d'un Diplome
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
		public function getIdDiplome()
		{
			return $this->idDiplome;
		}

		public function getIdCandidat()
		{
			return $this->idCandidat;
		}

		public function getIdDomaine()
		{
			return $this->idDomaine;
		}

		public function getIdNiveauEtude()
		{
			return $this->idNiveauEtude;
		}

		public function getDateObtention()
		{
			return $this->dateObtention;
		}

		public function getDesignation()
		{
			return $this->designation;
		}

		public function getEcole()
		{
			return $this->ecole;
		}

	// Seters
		public function setIdDiplome($idDiplome)
		{
			$this->idDiplome = $idDiplome;
		}

		public function setIdCandidat($idCandidat)
		{
			$this->idCandidat = $idCandidat;
		}

		public function setIdDomaine($idDomaine)
		{
			$this->idDomaine = $idDomaine;
		}

		public function setIdNiveauEtude($idNiveauEtude)
		{
			$this->idNiveauEtude = $idNiveauEtude;
		}

		public function setDateObtention($dateObtention)
		{
			$this->dateObtention = $dateObtention;
		}

		public function setDesignation($designation)
		{
			$this->designation = $designation;
		}

		public function setEcole($ecole)
		{
			$this->ecole = $ecole;
		}

	}