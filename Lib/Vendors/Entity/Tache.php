<?php 
	
	/** 
	 * Entité Tache
	 *
	 * @author Toky 
	 *
	 * @since 16/07/20
	 */

	namespace Entity;

	class Tache
	{
		private $idTache;
		private $idEmploye;
		private $titre;
		private $description;
		private $statut;
		private $estimated;
		private $dateDebut;
		private $activities;
		private $attributor;
		private $workedTime;
		private $idMission;
		
		/** 
		 * Initialisation d'une Tache
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
	    public function getIdTache()
	    {
	    	return $this->idTache;
	    }

	    public function getIdEmploye()
	    {
	    	return $this->idEmploye;
	    }

	    public function getTitre()
	    {
	    	return $this->titre;
	    }

	    public function getDescription()
	    {
	    	return $this->description;
	    }

	    public function getStatut()
	    {
	    	return $this->statut;
	    }

	    public function getEstimated()
	    {
	    	return $this->estimated;
	    }

	    public function getDateDebut()
	    {
	    	return $this->dateDebut;
	    }

	    public function getActivities()
	    {
	    	return $this->activities;
	    }

	    public function getAttributor()
	    {
	    	return $this->attributor;
	    }

	    public function getWorkedTime()
	    {
	    	return $this->workedTime;
	    }

	    public function getIdMission()
	    {
	    	return $this->idMission;
	    }

	    // Setters
	    public function setIdTache($idTache)
	    {
	    	$this->idTache = $idTache;
	    }

	    public function setIdEmploye($idEmploye)
	    {
	    	$this->idEmploye = $idEmploye;
	    }

	    public function setTitre($titre)
	    {
	    	$this->titre = $titre;
	    }

	    public function setDescription($description)
	    {
	    	$this->description = $description;
	    }

	    public function setStatut($statut)
	    {
	    	$this->statut = $statut;
	    }

	    public function setEstimated($estimated)
	    {
	    	$this->estimated = $estimated;
	    }

	    public function setDateDebut($dateDebut)
	    {
	    	$this->dateDebut = $dateDebut;
	    }

	    public function setActivities($activities)
	    {
	    	$this->activities = $activities;
	    }

	    public function setAttributor($attributor)
	    {
	    	$this->attributor = $attributor;
	    }

	    public function setWorkedTime($workedTime)
	    {
	    	$this->workedTime = $workedTime;
	    }

	    public function setIdMission($idMission)
	    {
	    	$this->idMission = $idMission;
	    }
	}