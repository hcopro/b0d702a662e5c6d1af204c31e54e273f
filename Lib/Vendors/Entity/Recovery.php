<?php 
	
	/** 
	 * Entité Recovery
	 *
	 * @author Lansky 
	 *
	 * @since 11/10/2022
	 */

	namespace Entity;

	class Recovery
	{
		private $idRecovery;
		private $during;
		private $debut;
		private $fin;
		private $date;
		private $idTache;
		private $idEmploye;
		private $idRetard;
		private $statut;
		
		/** 
		 * Initialisation d'une Recovery (Temps de récupération)
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
	    public function getIdRecovery()
	    {
	    	return $this->idRecovery;
	    }
	    public function getDuring()
	    {
	    	return $this->during;
	    }
	    
	    public function getDebut()
	    {
	    	return $this->debut;
	    }
	    
	    public function getFin()
	    {
	    	return $this->fin;
	    }
	    
	    public function getDate()
	    {
	    	return $this->date;
	    }

		public function getIdTache()
	    {
	    	return $this->idTache;
	    }

	    public function getIdEmploye()
	    {
	    	return $this->idEmploye;
	    }

	    public function getIdRetard()
	    {
	    	return $this->idRetard;
	    }
	    
	    public function getStatut()
	    {
	    	return $this->statut;
	    }

	    // Setters
	    public function setIdRecovery($idRecovery)
	    {
	    	$this->idRecovery = $idRecovery;
	    }

	    public function setDuring($during)
	    {
	    	$this->during = $during;
	    }

	    public function setDebut($debut)
	    {
	    	$this->debut = $debut;
	    }

	    public function setFin($fin)
	    {
	    	$this->fin = $fin;
	    }
	    
	    public function setDate($date)
	    {
	    	$this->date = $date;
	    }

	    public function setIdTache($idTache)
	    {
	    	$this->idTache = $idTache;
	    }

	    public function setIdEmploye($idEmploye)
	    {
	    	$this->idEmploye = $idEmploye;
	    }

	    public function setIdRetard($idRetard)
	    {
	    	$this->idRetard = $idRetard;
	    }

	    public function setStatut($statut)
	    {
	    	$this->statut = $statut;
	    }
	}