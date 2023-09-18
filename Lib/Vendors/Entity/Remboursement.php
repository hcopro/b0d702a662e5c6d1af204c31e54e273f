<?php 
	
	/** 
	 * Entité Remboursement
	 *
	 * @author Toky
	 *
	 * @since 18/11/20
	 */

	namespace Entity;

	class Remboursement
	{
		private $idRemboursement;
		private $idEmploye;
		private $idAvance;
		private $montant;
		private $statut;
		private $mois;
		private $annee;
		private $idDeduction;

		/** 
		 * Initialisation d'un Remboursement
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
		public function getIdRemboursement()
		{
			return $this->idRemboursement;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdAvance()
		{
			return $this->idAvance;
		}

		public function getMontant()
		{
			return $this->montant;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getMois()
		{
			return $this->mois;
		}

		public function getAnnee()
		{
			return $this->annee;
		}

		public function getIdDeduction()
		{
			return $this->idDeduction;
		}

	// Seters
		public function setIdRemboursement($idRemboursement)
		{
			$this->idRemboursement = $idRemboursement;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdAvance($idAvance)
		{
			$this->idAvance = $idAvance;
		}

		public function setMontant($montant)
		{
			$this->montant = $montant;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setMois($mois)
		{
			$this->mois = $mois;
		}

		public function setAnnee($annee)
		{
			$this->annee = $annee;
		}

		public function setIdDeduction($idDeduction)
		{
			$this->idDeduction = $idDeduction;
		}
	}