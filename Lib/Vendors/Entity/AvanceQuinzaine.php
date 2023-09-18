<?php 
	
	/** 
	 * Entité AvanceQuinzaine
	 *
	 * @author Toky
	 *
	 * @since 24/11/20
	 */

	namespace Entity;

	class AvanceQuinzaine
	{
		private $idAvanceQuinzaine;
		private $idEmploye;
		private $montant;
		private $statut;
		private $date;
		private $typeAvance;
		private $typeDemande;
		private $motif;
		private $idDeduction;
		private $idMessage;
		
		/** 
		 * Initialisation d'une AvanceQuinzaine
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
		public function getIdAvanceQuinzaine()
		{
			return $this->idAvanceQuinzaine;
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

		public function getTypeAvance()
		{
			return $this->typeAvance;
		}

		public function getTypeDemande()
		{
			return $this->typeDemande;
		}

		public function getMotif()
		{
			return $this->motif;
		}

		public function getIdDeduction()
		{
			return $this->idDeduction;
		}

		public function getIdMessage()
		{
			return $this->idMessage;
		}

	// Seters
		public function setIdAvanceQuinzaine($idAvanceQuinzaine)
		{
			$this->idAvanceQuinzaine = $idAvanceQuinzaine;
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

		public function setTypeAvance($typeAvance)
		{
			$this->typeAvance = $typeAvance;
		}

		public function setTypeDemande($typeDemande)
		{
			$this->typeDemande = $typeDemande;
		}

		public function setMotif($motif)
		{
			$this->motif = $motif;
		}

		public function setIdDeduction($idDeduction)
		{
			$this->idDeduction = $idDeduction;
		}

		public function setIdMessage($idMessage)
		{
			$this->idMessage = $idMessage;
		}
	}