<?php 
	
	/** 
	 * Entité NotificationEmployePermission
	 *
	 * @author Toky
	 *
	 * @since 27/07/20
	 */

	namespace Entity;

	class NotificationEmployePermission
	{
		private $idNotificationEmployePermission;
		private $idEmployePermission;
		private $idEmploye;
		private $statut;
		private $statutDemande;
		private $date;
		
		/** 
		 * Initialisation d'une notification de permission d'un employe
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
	    public function getIdNotificationEmployePermission()
	    {
	    	return $this->idNotificationEmployePermission;
	    }

		public function getIdEmployePermission()
		{
			return $this->idEmployePermission;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getStatutDemande()
		{
			return $this->statutDemande;
		}

		public function getDate()
		{
			return $this->date;
		}

	// Seters
		public function setIdNotificationEmployePermission($idNotificationEmployePermission)
		{
			$this->idNotificationEmployePermission = $idNotificationEmployePermission;
		}

		public function setIdEmployePermission($idEmployePermission)
		{
			$this->idEmployePermission = $idEmployePermission;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setStatutDemande($statutDemande)
		{
			$this->statutDemande = $statutDemande;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}
		
	}