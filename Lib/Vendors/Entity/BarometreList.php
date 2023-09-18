<?php 
	
   /**
	* Entité BarometreList
	*
	* @author Lansky
	*
	* @since 10/01/2022
	*/

	namespace Entity;

	class BarometreList
	{
		private $idBarometreList;
		private $isArchived;
		private $isAnswered;
		private $contents;
		private $date;
		private $dateReply;
		private $idEmploye;
		private $idEntreprise;
		private $idBarometre;
		
	   /** 
		* Initialisation de la classe BarometreList
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
		public function getIdBarometre()
		{
			return $this->idBarometre;
		}
		
		public function getIdBarometreList()
		{
			return $this->idBarometreList;
		}

		public function getIsArchived()
		{
			return $this->isArchived;
		}

		public function getIsAnswered()
		{
			return $this->isAnswered;
		}

		public function getContents()
		{
			return unserialize($this->contents);
		}

		public function getDate()
		{
			return $this->writeDate($this->date);
		}

		public function getDateReply()
		{
			return $this->writeDate($this->dateReply);
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		// Seters
		public function setIdBarometre($idBarometre)
		{
			$this->idBarometre = $idBarometre;
		}
		public function setIdBarometreList($idBarometreList)
		{
			$this->idBarometreList = $idBarometreList;
		}
		public function setIsArchived($isArchived)
		{
			$this->isArchived = $isArchived;
		}
		public function setIsAnswered($isAnswered)
		{
			$this->isAnswered = $isAnswered;
		}

		public function setContents($contents)
		{
			$this->contents = $contents;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setDateReply($dateReply)
		{
			$this->dateReply = $this->convertToDate($dateReply);
		}
		
		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		/**
         * Convertir un entier en mois
         *
         * @param int $month
         *
         * @return string
         */
        private function getMonthLetter($month)
        {
            $month  = intval($month);
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return $months[$month - 1];
        }

        /**
         * Convertir un mois en entier
         *
         * @param string $date
         *
         * @return date
         */
        private function convertToDate($date)
        {
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            if ($date) {
            	if (is_int(strpos($date,"-"))) {
            	 	$separator = "-";
            	}  else if (is_int(strpos($date," "))) {
            	 	$separator = " ";
            	} else if (is_int(strpos($date,"/"))){
            	 	$separator = "/";
            	}
            	$mois = explode($separator, $date);
	            if (is_int(array_search($mois[1], $months))) {
	            	$date = explode(' ', $date);
	            	$month = array_search($date[1], $months) + 1;
	            	$month = $month < 10 ? '0' . $month : $month;
	            	$date = $date[2] . '-' . $month . '-' . $date[0];
	            }
            }
            return $date;
        }
        // private function convertToDate($date)
        // {
        //     $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        //     if ($date) {
	       //      if (is_int(array_search(explode(' ', $date)[1], $months))) {
	       //      	$date = explode(' ', $date);
	       //      	$month = array_search($date[1], $months) + 1;
	       //      	$month = $month < 10 ? '0' . $month : $month;
	       //      	$date = $date[2] . '-' . $month . '-' . $date[0];
	       //      }
        //     }
        //     return $date;
        // }

        /**
         * Convertir une date en date complète
         *
         * @param date $date la date à convertir
         *
         * @return string
         */
        private function writeDate($date)
        {
        	$pos 	= strpos($date, ' ');
        	$time 	= substr($date, $pos + 1);
        	$tmpStr = substr($date, 0, $pos);
            $tmp 	= explode('-', $tmpStr);
            $temps 	= '';
            if (count($tmp) == 3) {
            	if (is_array($time)) {
            		$temps = ' à '. $time[0].'h '.$time[1].'min '.$time[2].'sec';
            	}
                return $tmp[2] . ' ' . $this->getMonthLetter($tmp[1]) . ' ' . $tmp[0];    
            } else {
                return $date;
            }
        }
	}