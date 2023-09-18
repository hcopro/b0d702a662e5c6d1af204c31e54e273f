<?php 
	
	/** 
	 * Entité Avantage
	 *
	 * @author Toky
	 *
	 * @since 21/10/20
	 */

	namespace Entity;

	class Avantage
	{
		private $idAvantage;
		private $idEntreprise;
		private $libelle;
		private $imposable;
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
		public function getIdAvantage()
		{
			return $this->idAvantage;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getLibelle()
		{
			return $this->libelle;
		}

		public function getImposable()
		{
			return $this->imposable;
		}

		public function getRatioImposable()
		{
			return $this->ratioImposable;
		}

	// Seters
		public function setIdAvantage($idAvantage)
		{
			$this->idAvantage = $idAvantage;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setLibelle($libelle)
		{
			$this->libelle = $libelle;
		}

		public function setImposable($imposable)
		{
			$this->imposable = $imposable;
		}

		public function setRatioImposable($ratioImposable)
		{
			$this->ratioImposable = $ratioImposable;
		}
	}