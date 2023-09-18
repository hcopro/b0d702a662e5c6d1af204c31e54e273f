<?php 
	
	/** 
	 * Entité ServicePoste
	 *
	 * @author Voahirana 
	 *
	 * @since 11/03/20
	 */

	namespace Entity;

	class ServicePoste
	{
		private $idServicePoste;
		private $idEntrepriseService;
		private $idEntreprisePoste;
		private $idCategorieProfessionnelle;
		
		/** 
		 * Initialisation d'un ServicePoste
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
		public function getIdServicePoste()
		{
			return $this->idServicePoste;
		}

		public function getIdEntrepriseService()
		{
			return $this->idEntrepriseService;
		}

		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		public function getIdCategorieProfessionnelle()
		{
			return $this->idCategorieProfessionnelle;
		} 

	// Seters
		public function setIdServicePoste($idServicePoste)
		{
			$this->idServicePoste = $idServicePoste;
		}

		public function setIdEntrepriseService($idEntrepriseService)
		{
			$this->idEntrepriseService = $idEntrepriseService;
		}

		public function setIdEntreprisePoste($idEntreprisePoste)
		{
			$this->idEntreprisePoste = $idEntreprisePoste;
		}

		public function setIdCategorieProfessionnelle($idCategorieProfessionnelle)
		{
			$this->idCategorieProfessionnelle = $idCategorieProfessionnelle;
		}
		
	}