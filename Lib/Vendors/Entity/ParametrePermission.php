<?php 
	
	/** 
	 * Entité ParametrePermission
	 *
	 * @author Toky
	 *
	 * @since 24/07/20
	 */

	namespace Entity;

	class ParametrePermission
	{
		private $idParametrePermission;
		private $idEntreprise;
		private $dureeMaxPermission;
		private $dureeMaxRepos;
		
		/** 
		 * Initialisation d'un paramètre de permission
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
		public function getIdParametrePermission()
		{
			return $this->idParametrePermission;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getDureeMaxPermission()
		{
			return $this->dureeMaxPermission;
		}

		public function getDureeMaxRepos()
		{
			return $this->dureeMaxRepos;
		}

	// Seters
		public function setIdParametrePermission($idParametrePermission)
		{
			$this->idParametrePermission = $idParametrePermission;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setDureeMaxPermission($dureeMaxPermission)
		{
			$this->dureeMaxPermission = $dureeMaxPermission;
		}

		public function setDureeMaxRepos($dureeMaxRepos)
		{
			$this->dureeMaxRepos = $dureeMaxRepos;
		}
		
	}