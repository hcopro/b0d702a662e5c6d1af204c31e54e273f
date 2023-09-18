<?php 
	
	/** 
	 * Entité StockConge
	 *
	 * @author Toky
	 *
	 * @since 21/08/20
	 */

	namespace Entity;

	class StockConge
	{
		private $idStockConge;
		private $idEmploye;
		private $duree;
		private $annee;
		
		/** 
		 * Initialisation d'un Stock de Congé
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
	    public function getIdStockConge()
	    {
	    	return $this->idStockConge;
	    }

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getDuree()
		{
			return $this->duree;
		}

		public function getAnnee()
		{
			return $this->annee;
		}
	// Seters
		public function setIdStockConge($idStockConge)
		{
			$this->idStockConge = $idStockConge;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setDuree($duree)
		{
			$this->duree = $duree;
		}

		public function setAnnee($annee)
		{
			$this->annee = $annee;
		}
		
	}