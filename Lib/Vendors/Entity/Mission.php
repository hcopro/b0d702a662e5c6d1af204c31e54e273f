<?php 
	
	/** 
	 * Entité Mission
	 *
	 * @author Voahirana 
	 *
	 * @since 02/04/19
	 */

	namespace Entity;

	class Mission
	{
		private $idMission;
		private $idEntreprisePoste;
		private $description;
		private $niveau;

		/** 
		 * Initialisation d'une mission
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
		public function getIdMission()
		{
			return $this->idMission;
		}

		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getNiveau()
		{
			return $this->niveau;
		}

	// Seters
		public function setIdMission($idMission)
		{
			$this->idMission = $idMission;
		}

		public function setIdEntreprisePoste($idEntreprisePoste)
		{
			$this->idEntreprisePoste = $idEntreprisePoste;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

		public function setNiveau($niveau)
		{
			$this->niveau = $niveau;
		}

	}