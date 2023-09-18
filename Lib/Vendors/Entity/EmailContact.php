<?php 
	
	/** 
	 * Entité EmailContact
	 *
	 * @author Voahirana 
	 *
	 * @since 18/11/19
	 */

	namespace Entity;

	class EmailContact
	{
		private $idEmailContact;
		private $email;
		private $type;
		
		/** 
		 * Initialisation d'un email de contact
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
		public function getIdEmailContact()
		{
			return $this->idEmailContact;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function getType()
		{
			return $this->type;
		}

	// Seters
		public function setIdEmailContact($idEmailContact)
		{
			$this->idEmailContact = $idEmailContact;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}

		public function setType($type)
		{
			$this->type = $type;
		}
		
	}