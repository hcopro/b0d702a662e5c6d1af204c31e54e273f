<?php 
	
	/** 
	 * Entité IdItem
	 *
	 * @author Lansky
	 *
	 * @since 12/07/21
	 */

	namespace Entity;

	class Item
	{
		private $idItem;
		private $description;
		private $point;
		private $idTrait;
		private $idEntreprise;
		
		/** 
		 * Initialisation d'une IdItem
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
		public function getIdItem()
		{
			return $this->idItem;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getPoint()
		{
			return $this->point;
		}

		public function getIdTrait()
		{
			return $this->idTrait;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}
		


	// Seters
		public function setIdItem($idItem)
		{
			$this->idItem = $idItem;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

		public function setPoint($point)
		{
			$this->point = $point;
		}

		public function setIdTrait($idTrait)
		{
			$this->idTrait = $idTrait;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}
	}