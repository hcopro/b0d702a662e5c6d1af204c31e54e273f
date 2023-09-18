<?php 
	
	/** 
	 * Entité Retard
	 *
	 * @author Lansky 
	 *
	 * @since 11/10/2022
	 */

	namespace Entity;

	class Retard
	{
		private $idRetard;
		private $during;
		private $date;
		private $idEmploye;
		private $isRetrieved;
		private $idPresence;
		
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
	    public function getIdRetard()
	    {
	    	return $this->idRetard;
	    }

	    public function getDuring()
	    {
	    	return $this->during;
	    }
	    
	    public function getDate()
	    {
	    	return $this->date;
	    }

		public function getIsRetrieved()
	    {
	    	return $this->isRetrieved;
	    }
	    
	    public function getIdEmploye()
	    {
	    	return $this->idEmploye;
	    }
		
		public function getIdPresence()
	    {
	    	return $this->idPresence;
	    }
	    
	    // Setters
	    public function setIdRetard($idRetard)
	    {
	    	$this->idRetard = $idRetard;
	    }
	    
	    public function setDuring($during)
	    {
	    	$this->during = $during;
	    }
	    
	    public function setDate($date)
	    {
	    	$this->date = $date;
	    }

	    public function setIsRetrieved($isRetrieved)
	    {
	    	$this->isRetrieved = $isRetrieved;
	    }

	    public function setIdEmploye($idEmploye)
	    {
	    	$this->idEmploye = $idEmploye;
	    }

	    public function setIdPresence($idPresence)
	    {
	    	$this->idPresence = $idPresence;
	    }
	}