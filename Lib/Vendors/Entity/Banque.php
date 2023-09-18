<?php 
	
	/** 
	 * Entité Banque
	 *
	 * @author Voahirana 
	 *
	 * @since 13/03/20
	 */

	namespace Entity;

	class Banque
	{
		private $idBanque;
		private $codeBanque;
		private $nomBanque;
		private $ville;
		private $codeBic;
		private $sigle;
		
		/** 
		 * Initialisation d'une Banque
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
		public function getIdBanque()
		{
			return $this->idBanque;
		}

		public function getCodeBanque()
		{
			return $this->codeBanque;
		}

		public function getNomBanque()
		{
			return $this->nomBanque;
		}

		public function getVille()
		{
			return $this->ville;
		}

		public function getCodeBic()
		{
			return $this->codeBic;
		}

		public function getSigle()
		{
			return $this->sigle;
		}

	// Seters
		public function setIdBanque($idBanque)
		{
			$this->idBanque = $idBanque;
		}

		public function setCodeBanque($codeBanque)
		{
			$this->codeBanque = $codeBanque;
		}

		public function setNomBanque($nomBanque)
		{
			$this->nomBanque = $nomBanque;
		}

		public function setVille($ville)
		{
			$this->ville = $ville;
		}

		public function setCodeBic($codeBic)
		{
			$this->codeBic = $codeBic;
		}

		public function setSigle($sigle)
		{
			$this->sigle = $sigle;
		}
		
	}