#!/usr/bin/php
<?php
		require_once(__DIR__ . '/../../Web/SplClassLoader.php');

		$entityLoader = new SplClassLoader('Entity', __DIR__ . '/../../Lib/Vendors');
		$entityLoader->register();
		$modelLoader = new SplClassLoader('Model', __DIR__ . '/../../Lib/Vendors');
		$modelLoader->register();
		$coreLoader = new SplClassLoader('Core', __DIR__ . '/../../Lib');
		$coreLoader->register();

		use \Core\DbManager;
		use \Model\ManagerEntreprise;
		use \Model\ManagerCompte;
		use \Model\ManagerConfiguration;
		use \Model\ManagerEmploye;
		use \Model\ManagerContratEmploye;
		use \Model\ManagerContrat;
		use \Model\ManagerRenouvellement;

		const COMPTE_ACTIVE    = "active";
		const COMPTE_DESACTIVE = "desactive";
		const MAIL_SYSTEM      = "hco@yopmail.com";
		const CDI              = "CDI";
		const VALIDATED        = 2;
		const EXPIRED          = 3;


		alerter($argv[1]);

		/**
		 * Alerter les entreprises si nécessaire
		 *
		 * @return empty
		 */
		function alerter($idEntreprise)
		{
			$manager       = new ManagerEntreprise();
			$entreprise    = $manager->chercher(['idEntreprise' => $idEntreprise]);
			$manager       = new ManagerConfiguration();
		    $configuration = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
			$employes = getEmployes($entreprise);
			foreach ($employes as $employe) {
				$manager        = new ManagerContratEmploye();
				$contratEmployes = $manager->lister([
					'idEmploye' => $employe->getIdEmploye(),
					'statut'    => VALIDATED
				]);
				foreach ($contratEmployes as $contratEmploye) {
					if (checkContrat($entreprise, $contratEmploye)) {
						$message = "<html><body>
	                                   <div class='container'>
	                                       <label>Bonjour " . strtoupper($entreprise->getNom()) . ",</label>
	                                       <br><br>
	                                       <label>Nous vous informons que le contrat de " . $employe->getCivilite() . " <strong>" .
	                                       ucwords($employe->getNom()) . " " . ucwords($employe->getPrenom()) . "</strong>
	                                       <br>  au sein de votre société expire le <strong>" . writeDate($contratEmploye->getDateFin()) . ".</strong></label><br><br>
	                                       <label>Cordialement, </label><br><br>
	                                       <label> Le système sirh de HCO </label>
	                                   </div>
	                               </body></html>";
	                    sendMail($configuration->getEmailAlerte(), MAIL_SYSTEM, "RAPPEL SUR L'EXPIRATION D'UN CONTRAT", $message);
					}
				}
			}
		}

		/**
		 * Prendre toutes les entreprises ayant un compte activé
		 *
		 * @return array
		 */
		function getEntreprises()
		{
			$manager        = new ManagerEntreprise();
			$allEntreprises = $manager->lister();
			$entreprises    = array();
			foreach ($allEntreprises as $entreprise) {
				$manager = new ManagerCompte();
				$compte  = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
				if ($compte->getStatut() == COMPTE_ACTIVE) {
					$entreprises[] = $entreprise; 
				}
			}
			return $entreprises;
		}

		/**
		 * Prendre tous les employés d'une entreprise
		 *
		 * @param object $entreprise L'entreprise où travaillent les employés
		 *
		 * @return array
		 */
		function getEmployes($entreprise)
		{
			$manager  = new ManagerEmploye();
			$employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
			return $employes;
		}

		/**
	     * Envoyer un email
	     * 
	     * @param string $to l'adresse email du destinataire
	     * @param string $from l'adresse email de l'envoyeur
	     * @param string $subject l'objet du message
	     * @param string $message le message
	     *
	     * @return empty
	     */
	    function sendMail($to, $from, $subject, $message)
	    {
	    	$headers = "Content-type: text/html" . "\r\n" . "From: " . $from;
	      	mail($to, $subject, $message, $headers);
	    }

	    /**
	     * Vérifier si le Contrat si il est nécessaire d'alerter l'entreprise
	     *
	     * @param object $entreprise l'entreprise concernée
	     * @param object $contratEmploye le contrat à vérifier
	     *
	     * @return bool
	     */
	    function checkContrat($entreprise, $contratEmploye)
	    {
	    	$manager      = new ManagerContrat();
	    	$typeContrat  = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
	    	if ($typeContrat->getDesignation() != CDI && $contratEmploye->getStatut() == VALIDATED) {
		    	$manager       = new ManagerConfiguration();
		    	$configuration = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
		    	if ($configuration->getNombreAlerte() >= 1) {
		    		if (getFutureDate(date('Y-m-d'), $configuration->getPremiereAlerte()) == $contratEmploye->getDateFin()) {
		    			return true;
		    		}
		    	} 
		    	if ($configuration->getNombreAlerte() >= 2) {
		    		if (getFutureDate(date('Y-m-d'), $configuration->getDeuxiemeAlerte()) == $contratEmploye->getDateFin()) {
		    			return true;
		    		}
		    	} 
		    	if ($configuration->getNombreAlerte() == 3) {
		    		if (getFutureDate(date('Y-m-d'), $configuration->getTroisiemeAlerte()) == $contratEmploye->getDateFin()) {
		    			return true;
		    		}
		    	}
		    	if (strtotime($contratEmploye->getDateFin()) <= strtotime(date('Y-m-d'))) {
		    		$manager = new ManagerContratEmploye();
		    		$manager->modifier([
		    			'idContratEmploye' => $contratEmploye->getIdContratEmploye(),
		    			'statut'           => EXPIRED
		    		]);
		    	}
		    }
		    return false;
	    }

	    /**
         * Convertir une date en date complète
         *
         * @param date $date la date à convertir
         *
         * @return string
         */
        function writeDate($date)
        {
            $tmp = explode('-', $date);
            return $tmp[2] . ' ' . getMonthLetter($tmp[1]) . ' ' . $tmp[0];
        }

        /**
         * Convertir un entier en mois
         *
         * @param int $month
         *
         * @return string
         */
        function getMonthLetter($month)
        {
            $month = intval($month);
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return $months[$month - 1];
        }

        /**
         * Obtenir une date à partir d'une date de départ et une durée en jour
         *
         * @param date $date date de référence
         * @param int  $duree nombre de mois
         *
         * @return date
         */
        function getFutureDate($date , $duree)
        {
            $dateTimestamp = strtotime($date);
            return date('Y-m-d', strtotime('+' . $duree . ' month', $dateTimestamp));
        }

		/**
		 * Ecrire dans un fichier 
		 *
		 * @param string $fichier le nom de fichier
		 * @param string $contenu le contenu du fichier
		 *
		 * @return empty
		 */ 
		function write($fichier, $contenu)
		{
			$file = fopen($fichier, 'w');
			fwrite($file, $contenu);
			fclose($file);
		}
?>