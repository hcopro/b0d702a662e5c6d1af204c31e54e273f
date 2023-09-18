<?php 
	
	/** 
	 * Entité Superadmin
	 *
	 * @author Voahirana 
	 *
	 * @since 25/09/19
	 */

	namespace Entity;

	class Superadmin
	{
		private $idSuperadmin;
		private $idCompte;
		private $photo;
		private $nom;  
		private $role; 
		private $contact; 
		private $email;

		/** 
		 * Initialisation d'un Superadmin
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
		public function getIdSuperadmin()
		{
			return $this->idSuperadmin;
		}

		public function getIdCompte()
		{
			return $this->idCompte;
		}

		public function getPhoto()
		{
			return $this->photo;
		}

		public function getNom()
		{
			return $this->nom;
		}

		public function getRole()
		{
			return $this->role;
		}

		public function getContact()
		{
			return $this->contact;
		}

		public function getEmail()
		{
			return $this->email;
		}

	// Seters
		public function setIdSuperadmin($idSuperadmin)
		{
			$this->idSuperadmin = $idSuperadmin;
		}

		public function setIdCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setPhoto($photo)
		{
			$this->photo = $photo;
		}

		public function setNom($nom)
		{
			$this->nom = $nom;
		}

		public function setRole($role)
		{
			$this->role = $role;
		}

		public function setContact($contact)
		{
			$this->contact = $contact;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}

	}