<?php 
	
	/** 
	 * Entité TypePermission
	 *
	 * @author Toky
	 *
	 * @since 23/07/20
	 */

	namespace Entity;

	class TypePermission
	{
		private $idTypePermission;
		private $designation;
		private $classique;
		
		/** 
		 * Initialisation d'un type de permission
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
		public function getIdTypePermission()
		{
			return $this->idTypePermission;
		}

		public function getDesignation()
		{
			return $this->designation;
		}

		public function getClassique()
		{
			return $this->classique;
		}

	// Seters
		public function setIdTypePermission($idTypePermission)
		{
			$this->idTypePermission = $idTypePermission;
		}

		public function setDesignation($designation)
		{
			$this->designation = $designation;
		}
		
		public function setClassique($classique)
		{
			$this->classique = $classique;
		}
	}