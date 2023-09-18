<?php 
	
	/** 
	 * Entité CompteBanque
	 *
	 * @author Voahirana 
	 *
	 * @since 16/03/20
	 */

	namespace Entity;

	class CompteBanque
	{
		private $idCompteBanque;
		private $idEmploye;
		private $idBanque;
		private $numeroCompte;
		
		/** 
		 * Initialisation d'un compte banque
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
		public function getIdCompteBanque()
		{
			return $this->idCompteBanque;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdBanque()
		{
			return $this->idBanque;
		}

		public function getNumeroCompte()
		{
			return $this->numeroCompte;
		}

	// Seters
		public function setIdCompteBanque($idCompteBanque)
		{
			$this->idCompteBanque = $idCompteBanque;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdBanque($idBanque)
		{
			$this->idBanque = $idBanque;
		}

		public function setNumeroCompte($numeroCompte)
		{
			$this->numeroCompte = $numeroCompte;
		}
		
	}