<?php 
	
	/** 
	 * Entité Service
	 *
	 * @author Voahirana 
	 *
	 * @since 11/03/20
	 */

	namespace Entity;

	class Service
	{
		private $idService;
		private $nomService;
		
		/** 
		 * Initialisation d'un Service
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
		public function getIdService()
		{
			return $this->idService;
		}

		public function getNomService()
		{
			return $this->nomService;
		}

	// Seters
		public function setIdService($idService)
		{
			$this->idService = $idService;
		}

		public function setNomService($nomService)
		{
			$this->nomService = $nomService;
		}
		
	}