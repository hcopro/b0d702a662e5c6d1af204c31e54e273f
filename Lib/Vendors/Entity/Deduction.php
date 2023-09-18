<?php 
	
	/** 
	 * Entité Deduction
	 *
	 * @author Toky
	 *
	 * @since 16/11/20
	 */

	namespace Entity;

	class Deduction
	{
		private $idDeduction;
		private $idEmploye;
		private $montant;
		private $mois;
		private $annee;
		private $libelle;
		
		/** 
		 * Initialisation d'une Déduction
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
		public function getIdDeduction()
		{
			return $this->idDeduction;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getMontant()
		{
			return $this->montant;
		}

		public function getMois()
		{
			return $this->mois;
		}

		public function getAnnee()
		{
			return $this->annee;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

	// Seters
		public function setIdDeduction($idDeduction)
		{
			$this->idDeduction = $idDeduction;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setMontant($montant)
		{
			$this->montant = $montant;
		}

		public function setMois($mois)
		{
			$this->mois = $mois;
		}

		public function setAnnee($annee)
		{
			$this->annee = $annee;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}
	}