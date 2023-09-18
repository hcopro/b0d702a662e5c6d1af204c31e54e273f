<?php 
	
	/** 
	 * Entité DemandeFormation
	 *
	 * @author Toky
	 *
	 * @since 28/09/20
	 */

	namespace Entity;

	class DemandeFormation
	{
		private $idDemandeFormation;
		private $idEmploye;
		private $idFormationProfessionnelle;
		private $validateur;
		private $statut;
		private $etat;
		
		/** 
		 * Initialisation d'une DemandeFormation
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
		public function getIdDemandeFormation()
		{
			return $this->idDemandeFormation;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdFormationProfessionnelle()
		{
			return $this->idFormationProfessionnelle;
		}

		public function getValidateur()
		{
			return $this->validateur;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getEtat()
		{
			return $this->etat;
		}

	// Seters
		public function setIdDemandeFormation($idDemandeFormation)
		{
			$this->idDemandeFormation = $idDemandeFormation;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdFormationProfessionnelle($idFormationProfessionnelle)
		{
			$this->idFormationProfessionnelle = $idFormationProfessionnelle;
		}

		public function setValidateur($validateur)
		{
			$this->validateur = $validateur;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setEtat($etat)
		{
			$this->etat = $etat;
		}
	}