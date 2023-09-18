<?php 
	
	/** 
	 * Entité Avance
	 *
	 * @author Toky
	 *
	 * @since 18/11/20
	 */

	namespace Entity;

	class Avance
	{
		private $idAvance;
		private $idEmploye;
		private $montant;
		private $statut;
		private $date;
		private $motif;
		private $etat;
		private $motifRefus;
		private $typeDemande;
		private $typeAvance;
		private $idMessage;
		private $moisRemboursement;
		private $anneeRemboursement;
		
		/** 
		 * Initialisation d'une Avance
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
		public function getIdAvance()
		{
			return $this->idAvance;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getMontant()
		{
			return $this->montant;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getDate()
		{
			return $this->date;
		}

		public function getMotif()
		{
			return $this->motif;
		}

		public function getEtat()
		{
			return $this->etat;
		}

		public function getMotifRefus()
		{
			return $this->motifRefus;
		}

		public function getTypeAvance()
		{
			return $this->typeAvance;
		}

		public function getTypeDemande()
		{
			return $this->typeDemande;
		}

		public function getIdMessage()
		{
			return $this->idMessage;
		}

		public function getMoisRemboursement()
		{
			return $this->moisRemboursement;
		}

		public function getAnneeRemboursement()
		{
			return $this->anneeRemboursement;
		}


	// Seters
		public function setIdAvance($idAvance)
		{
			$this->idAvance = $idAvance;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setMontant($montant)
		{
			$this->montant = $montant;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setMotif($motif)
		{
			$this->motif = $motif;
		}

		public function setEtat($etat)
		{
			$this->etat = $etat;
		}

		public function setMotifRefus($motifRefus)
		{
			$this->motifRefus = $motifRefus;
		}

		public function setTypeDemande($typeDemande)
		{
			$this->typeDemande = $typeDemande;
		}

		public function setTypeAvance($typeAvance)
		{
			$this->typeAvance = $typeAvance;
		}

		public function setIdMessage($idMessage)
		{
			$this->idMessage = $idMessage;
		}

		public function setMoisRemboursement($moisRemboursement)
		{
			$this->moisRemboursement = $moisRemboursement;
		}

		public function setAnneeRemboursement($anneeRemboursement)
		{
			$this->anneeRemboursement = $anneeRemboursement;
		}
	}