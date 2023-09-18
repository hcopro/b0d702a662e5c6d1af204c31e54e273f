<?php 
	
	/** 
	 * Entité Autorized
	 *
	 * @author Lansky 
	 *
	 * @since 28/03/2023
	*/

	namespace Entity;

	class Autorized
	{
		private $idAutorized;
		private $idEmploye;
		private $addMenu;
		
		/** 
		 * Initialisation du Ratard
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

	    /**
	     * Convertir l'identifiant en string pour permettre aux fontions php de faire des comparaisons ou autres
	     *
	     * @return string
	    */
	    public function __toString()
    	{
        	try 
        	{
            	return (string) $this->idEmploye;
        	} 
        	catch (Exception $exception) 
        	{
            	return '';
        	}
    	}

	    // Getters
	    public function getIdAutorized()
	    {
	    	return $this->idAutorized;
	    }

	    public function getAddMenu()
	    {
	    	return unserialize($this->addMenu);
	    }
	    
	    public function getIdEmploye()
	    {
	    	return $this->idEmploye;
	    }
	    
	    // Setters
	    public function setIdAutorized($idAutorized)
	    {
	    	$this->idAutorized = $idAutorized;
	    }
	    
	    public function setAddMenu($addMenu)
	    {
	    	$this->addMenu = $addMenu;
	    }

	    public function setIdEmploye($idEmploye)
	    {
	    	$this->idEmploye = $idEmploye;
	    }
	}