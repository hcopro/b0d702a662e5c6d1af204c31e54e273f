<?php 
	
	/** 
	 * Entité AvantageEmploye
	 *
	 * @author Toky
	 *
	 * @since 21/10/20
	 */

	namespace Entity;

	class AvantageEmploye
	{
		private $idAvantageEmploye;
		private $idEmploye;
		private $montant;
		private $imposable;
		private $idAvantage;
		private $mois;
		private $annee;
		private $ratioImposable;
		
		/** 
		 * Initialisation d'un avantage
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
		public function getIdAvantageEmploye()
		{
			return $this->idAvantageEmploye;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getMontant()
		{
			return $this->montant;
		}

		public function getImposable()
		{
			return $this->imposable;
		}

		public function getIdAvantage()
		{
			return $this->idAvantage;
		}

		public function getMois()
		{
			return $this->mois;
		}

		public function getAnnee()
		{
			return $this->annee;
		}

		public function getRatioImposable()
		{
			return $this->ratioImposable;
		}

	// Seters
		public function setIdAvantageEmploye($idAvantageEmploye)
		{
			$this->idAvantageEmploye = $idAvantageEmploye;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setMontant($montant)
		{
			$this->montant = $montant;
		}

		public function setImposable($imposable)
		{
			$this->imposable = $imposable;
		}

		public function setIdAvantage($idAvantage)
		{
			$this->idAvantage = $idAvantage;
		}

		public function setMois($mois)
		{
			$this->mois = $mois;
		}

		public function setAnnee($annee)
		{
			$this->annee = $annee;
		}

		public function setRatioImposable($ratioImposable)
		{
			$this->ratioImposable = $ratioImposable;
		}
	}