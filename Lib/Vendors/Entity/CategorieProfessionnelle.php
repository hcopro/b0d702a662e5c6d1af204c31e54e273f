<?php 
	
	/** 
	 * Entité Catégorie Professionnelle
	 *
	 * @author Toky 
	 *
	 * @since 06/07/2020
	 */

	namespace Entity;

	class CategorieProfessionnelle
	{
		private $idCategorieProfessionnelle;
		private $designation;
		private $indiceEmbauche;
		private $indiceAnciennete;
		
		/** 
		 * Initialisation d'une CategorieProfessionnelle
		 *
		 * @param array $data Données à intialiser 
		 *
		 * @return empty
		 */
	    public function __construct($data = array())
	    {
	        if (!empty($data)) {
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
		public function getIdCategorieProfessionnelle()
		{
			return $this->idCategorieProfessionnelle;
		}
		public function getDesignation()
		{
			return $this->designation;
		}
		public function getIndiceEmbauche()
		{
			return $this->indiceEmbauche;
		}
		public function getIndiceAnciennete()
		{
			return $this->indiceAnciennete;
		}

	// Seters
		public function setIdCategorieProfessionnelle($idCategorieProfessionnelle)
		{
			$this->idCategorieProfessionnelle = $idCategorieProfessionnelle;
		}
		public function setDesignation($designation)
		{
			$this->designation = $designation;
		}
		public function setIndiceEmbauche($indiceEmbauche)
		{
			$this->indiceEmbauche = $indiceEmbauche;
		}
		public function setIndiceAnciennete($indiceAnciennete)
		{
			$this->indiceAnciennete = $indiceAnciennete;
		}
	}