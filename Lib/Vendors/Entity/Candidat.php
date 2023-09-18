<?php 
	
	/** 
	 * Entité Candidat
	 *
	 * @author Voahirana 
	 *
	 * @since 25/09/19
	*/

	namespace Entity;

	class Candidat
	{
		private $idCandidat;
		private $idCompte; 
		private $photo; 
		private $civilite;
		private $nom;  
		private $prenom; 
		private $dateNaiss; 
		private $adresse;
		private $ville;
		private $contact; 
		private $email;
		private $description; 
		private $personnalite; 
		private $publique;
		private $photoCouverture;

		/** 
		 * Initialisation d'un Candidat
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
		public function getIdCandidat()
		{
			return $this->idCandidat;
		}

		public function getIdCompte()
		{
			return $this->idCompte;
		}

		public function getPhoto()
		{
			return $this->photo;
		}

		public function getCivilite()
		{
			return $this->civilite;
		}

		public function getNom()
		{
			return $this->nom;
		}

		public function getPrenom()
		{
			return $this->prenom;
		}

		public function getAdresse()
		{
			return $this->adresse;
		}

		public function getVille()
		{
			return $this->ville;
		}

		public function getdateNaiss()
		{
			return $this->dateNaiss;
		}

		public function getContact()
		{
			return $this->contact;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getPersonnalite()
		{
			return $this->personnalite;
		}

		public function getPublique()
		{
			return $this->publique;
		}

		public function getPhotoCouverture()
		{
			return $this->photoCouverture;
		}

		// Seters
		public function setIdCandidat($idCandidat)
		{
			$this->idCandidat = $idCandidat;
		}

		public function setIdCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setPhoto($photo)
		{
			$this->photo = $photo;
		}

		public function setCivilite($civilite)
		{
			$this->civilite = $civilite;
		}

		public function setNom($nom)
		{
			$this->nom = $nom;
		}

		public function setPrenom($prenom)
		{
			$this->prenom = $prenom;
		}

		public function setdateNaiss($dateNaiss)
		{
			$this->dateNaiss = $dateNaiss;
		}

		public function setAdresse($adresse)
		{
			$this->adresse = $adresse;
		}

		public function setVille($ville)
		{
			$this->ville = $ville;
		}

		public function setContact($contact)
		{
			$this->contact = $contact;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

		public function setPersonnalite($personnalite)
		{
			$this->personnalite = $personnalite;
		}

		public function setPublique($publique)
		{
			$this->publique = $publique;
		}

		public function setPhotoCouverture($photoCouverture)
		{
			$this->photoCouverture = $photoCouverture;
		}
	}