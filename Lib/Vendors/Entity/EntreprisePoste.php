<?php 
	
	/** 
	 * Entité EntreprisePoste
	 *
	 * @author Voahirana 
	 *
	 * @since 12/03/20
	 */

	namespace Entity;

	class EntreprisePoste
	{
		private $idEntreprisePoste;
		private $idEntreprise;
		private $poste;
		private $formationRequise;
		private $idNiveauEtude;
		private $capaciteNiveauEtude;
		private $relationInterne;
		private $relationExterne;
		private $anneeExperienceInterne;
		private $experienceInterne;
		private $capaciteExperienceInterne;
		private $anneeExperienceExterne;
		private $experienceExterne;
		private $capaciteExperienceExterne;
		private $raison;
		private $evolution;
		private $continuite;
		private $savoirs;
		private $savoirFaire;
		private $savoirEtre;
		private $niveau;
		private $statut;
		
		/** 
		 * Initialisation d'un EntreprisePoste
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
	     * Convertir l'identifiant en string pour permettre aux fontions php de faire des comparaisons ou autres
	     *
	     * @return string
	     */
	    public function __toString()
    	{
        	try 
        	{
            	return (string) $this->idEntreprisePoste;
        	} 
        	catch (Exception $exception) 
        	{
            	return '';
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
		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getPoste()
		{
			return $this->poste;
		}

		public function getFormationRequise()
		{
			return $this->formationRequise;
		}

		public function getIdNiveauEtude()
		{
			return $this->idNiveauEtude;
		}

		public function getCapaciteNiveauEtude()
		{
			return $this->capaciteNiveauEtude;
		}

		public function getRelationInterne()
		{
			return $this->relationInterne;
		}

		public function getRelationExterne()
		{
			return $this->relationExterne;
		}

		public function getAnneeExperienceInterne()
		{
			return $this->anneeExperienceInterne;
		}

		public function getExperienceInterne()
		{
			return $this->experienceInterne;
		}

		public function getCapaciteExperienceInterne()
		{
			return $this->capaciteExperienceInterne;
		}

		public function getAnneeExperienceExterne()
		{
			return $this->anneeExperienceExterne;
		}

		public function getExperienceExterne()
		{
			return $this->experienceExterne;
		}

		public function getCapaciteExperienceExterne()
		{
			return $this->capaciteExperienceExterne;
		}

		public function getRaison()
		{
			return $this->raison;
		}

		public function getEvolution()
		{
			return $this->evolution;
		}

		public function getContinuite()
		{
			return $this->continuite;
		}

		public function getSavoirs()
		{
			return $this->savoirs;
		}

		public function getSavoirFaire()
		{
			return $this->savoirFaire;
		}

		public function getSavoirEtre()
		{
			return $this->savoirEtre;
		}

		public function getNiveau()
		{
			return $this->niveau;
		}

		public function getStatut()
		{
			return $this->statut;
		}

	// Seters
		public function setIdEntreprisePoste($idEntreprisePoste)
		{
			$this->idEntreprisePoste = $idEntreprisePoste;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setPoste($poste)
		{
			$this->poste = $poste;
		}

		public function setFormationRequise($formationRequise)
		{
			$this->formationRequise = $formationRequise;
		}

		public function setIdNiveauEtude($idNiveauEtude)
		{
			$this->idNiveauEtude = $idNiveauEtude;
		}

		public function setCapaciteNiveauEtude($capaciteNiveauEtude)
		{
			$this->capaciteNiveauEtude = $capaciteNiveauEtude;
		}

		public function setRelationInterne($relationInterne)
		{
			$this->relationInterne = $relationInterne;
		}

		public function setRelationExterne($relationExterne)
		{
			$this->relationExterne = $relationExterne;
		}

		public function setAnneeExperienceInterne($anneeExperienceInterne)
		{
			$this->anneeExperienceInterne = $anneeExperienceInterne;
		}

		public function setExperienceInterne($experienceInterne)
		{
			$this->experienceInterne = $experienceInterne;
		}

		public function setCapaciteExperienceInterne($capaciteExperienceInterne)
		{
			$this->capaciteExperienceInterne = $capaciteExperienceInterne;
		}

		public function setAnneeExperienceExterne($anneeExperienceExterne)
		{
			$this->anneeExperienceExterne = $anneeExperienceExterne;
		}

		public function setExperienceExterne($experienceExterne)
		{
			$this->experienceExterne = $experienceExterne;
		}

		public function setCapaciteExperienceExterne($capaciteExperienceExterne)
		{
			$this->capaciteExperienceExterne = $capaciteExperienceExterne;
		}

		public function setRaison($raison)
		{
			$this->raison = $raison;
		}

		public function setEvolution($evolution)
		{
			$this->evolution = $evolution;
		}

		public function setContinuite($continuite)
		{
			$this->continuite = $continuite;
		}

		public function setSavoirs($savoirs)
		{
			$this->savoirs = $savoirs;
		}

		public function setSavoirFaire($savoirFaire)
		{
			$this->savoirFaire = $savoirFaire;
		}

		public function setSavoirEtre($savoirEtre)
		{
			$this->savoirEtre = $savoirEtre;
		}

		public function setNiveau($niveau)
		{
			$this->niveau = $niveau;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}
		
	}