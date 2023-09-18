<?php 
	
	/** 
	 * Entité EntreprisePermission
	 *
	 * @author Toky
	 *
	 * @since 23/07/20
	 */

	namespace Entity;

	class EntreprisePermission
	{
		private $idEntreprisePermission;
		private $idEntreprise;
		private $idTypePermission;
		private $duree;
		
		/** 
		 * Initialisation d'une permission d'une entretreprise
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
		public function getIdEntreprisePermission()
		{
			return $this->idEntreprisePermission;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdTypePermission()
		{
			return $this->idTypePermission;
		}

		public function getDuree()
		{
			return $this->duree;
		}

	// Seters
		public function setIdEntreprisePermission($idEntreprisePermission)
		{
			$this->idEntreprisePermission = $idEntreprisePermission;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setIdTypePermission($idTypePermission)
		{
			$this->idTypePermission = $idTypePermission;
		}

		public function setDuree($duree)
		{
			$this->duree = $duree;
		}
		
	}