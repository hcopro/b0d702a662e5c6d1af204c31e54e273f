<?php
	define('CURTIME', microtime(true)); // something time consuming here

	//  À copier quelquepart où on veut voir le temps d'execution du code php et consommation de la ressource causé l'apparition du 503 erreur
	// 	  echo "<pre>";
	//    var_dump(CURTIME);
	//    ............... ici le code
	//    $timeConsumed = round(microtime(true) - CURTIME, 3) * 1000; // get time difference in milliseconds
	//    var_dump($timeConsumed);
	//    exit;
	
	session_start();
	
	require_once("Web/SplClassLoader.php");
	define('KEY', 'xbCWwfWh');
	define('HOST', 'https://'. $_SERVER['HTTP_HOST'] . '/');
	// define('PREVIOUS_URL', $_SERVER['HTTP_REFERER']);
	// define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/Web/');
	// define('TMP_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Tmp/');
	// define('CRON_DIR', $_SERVER['DOCUMENT_ROOT'] . '/App/Crons/');
	/**@changelog 12/10/2022 [FIX] (Lansky) Changer le chemin du document root private_html en public_html */
   	$docRoot = preg_replace('#private_html#', 'public_html', $_SERVER['DOCUMENT_ROOT']);
	define('DOC_ROOT', $docRoot . '/Web/');
	define('TMP_DIR', $docRoot . '/Tmp/');
	define('CRON_DIR', $docRoot . '/App/Crons/');
	define('TYPE', serialize(array('contact', 'information')));
	define ("STATUT_OFFRE", serialize(array ("envoye", "accepte", "refuse")));	
	define ("STATUT_ENTRETIEN", serialize(array ("en attente", "valide", "rejete")));
	define ("COUNTRIES", serialize(array (	array("code" => "AF", "country" => "Afghanistan"),
											array("code" => "ZA", "country" => "Afrique du Sud"),
											array("code" => "AL", "country" => "Albanie"),
											array("code" => "DZ", "country" => "Algérie"),
											array("code" => "DE", "country" => "Allemagne"),
											array("code" => "AD", "country" => "Andorre"),
											array("code" => "AO", "country" => "Angola"),
											array("code" => "AI", "country" => "Anguilla"),
											array("code" => "AQ", "country" => "Antarctique"),
											array("code" => "AG", "country" => "Antigua-et-Barbuda"),
											array("code" => "SA", "country" => "Arabie Saoudite"),
											array("code" => "AR", "country" => "Argentine"),
											array("code" => "AM", "country" => "Arménie"),
											array("code" => "AW", "country" => "Aruba"),
											array("code" => "AU", "country" => "Australie"),
											array("code" => "AT", "country" => "Autriche"),
											array("code" => "AZ", "country" => "Azerbaïdjan"),
											array("code" => "BS", "country" => "Bahamas"),
											array("code" => "BH", "country" => "Bahreïn"),
											array("code" => "BD", "country" => "Bangladesh"),
											array("code" => "BB", "country" => "Barbade"),
											array("code" => "BE", "country" => "Belgique"),
											array("code" => "BZ", "country" => "Belize"),
											array("code" => "BJ", "country" => "Bénin"),
											array("code" => "BM", "country" => "Bermudes"),
											array("code" => "BT", "country" => "Bhoutan"),
											array("code" => "BY", "country" => "Biélorussie"),
											array("code" => "MM", "country" => "Birmanie"),
											array("code" => "BO", "country" => "Bolivie"),
											array("code" => "BA", "country" => "Bosnie-Herzégovine"),
											array("code" => "BW", "country" => "Botswana"),
											array("code" => "BR", "country" => "Brésil"),
											array("code" => "VG", "country" => "British Virgin Islands"),
											array("code" => "BN", "country" => "Brunei Darussalam"),
											array("code" => "BG", "country" => "Bulgarie"),
											array("code" => "BF", "country" => "Burkina Faso"),
											array("code" => "BI", "country" => "Burindi"),
											array("code" => "KH", "country" => "Cambodge"),
											array("code" => "CM", "country" => "Cameroun"),
											array("code" => "CA", "country" => "Canada"),
											array("code" => "CV", "country" => "Cap-Vert"),
											array("code" => "CL", "country" => "Chili"),
											array("code" => "CN", "country" => "Chine"),
											array("code" => "CY", "country" => "Chypre"),
											array("code" => "CO", "country" => "Colombie"),
											array("code" => "KM", "country" => "Comores"),	
											array("code" => "KP", "country" => "Corée du Nord"),
											array("code" => "KR", "country" => "Corée du Sud"),
											array("code" => "CR", "country" => "Costa Rica"),
											array("code" => "CI", "country" => "Côte d'Ivoire"),
											array("code" => "HR", "country" => "Croatie"),
											array("code" => "CU", "country" => "Cuba"),
											array("code" => "CW", "country" => "Curaçao"),
											array("code" => "DK", "country" => "Danemark"),
											array("code" => "DJ", "country" => "Djibouti"),
											array("code" => "DM", "country" => "Dominique"),
											array("code" => "EG", "country" => "Egypte"),
											array("code" => "SV", "country" => "El Salvador"),
											array("code" => "AE", "country" => "Emirats Arabes Unis"),
											array("code" => "EC", "country" => "Equateur"),
											array("code" => "ER", "country" => "Erythrée"),
											array("code" => "ES", "country" => "Espagne"),
											array("code" => "EE", "country" => "Estonie"),
											array("code" => "SZ", "country" => "Eswatini"),
											array("code" => "US", "country" => "Etats-Unis"),
											array("code" => "ET", "country" => "Ethiopie"),
											array("code" => "FJ", "country" => "Fidji"),
											array("code" => "FI", "country" => "Finlande"),
											array("code" => "FR", "country" => "France"),
											array("code" => "GA", "country" => "Gabon"),
											array("code" => "GM", "country" => "Gambie"),
											array("code" => "GE", "country" => "Géorgie"),
											array("code" => "GS", "country" => "Géorgie du Sud et les Îles Sandwich du Sud"),
											array("code" => "GH", "country" => "Ghana"),
											array("code" => "GI", "country" => "Gibraltar"),
											array("code" => "GR", "country" => "Grèce"),
											array("code" => "GD", "country" => "Grenade"),
											array("code" => "GL", "country" => "Groenland"),
											array("code" => "GP", "country" => "Guadeloupe"),
											array("code" => "GU", "country" => "Guam"),
											array("code" => "GT", "country" => "Guatemala"),
											array("code" => "GG", "country" => "Guernesey"),
											array("code" => "GN", "country" => "Guinée"),
											array("code" => "GW", "country" => "Guinée-Bissau"),
											array("code" => "GQ", "country" => "Guinée équatoriale"),
											array("code" => "GY", "country" => "Guyana"),
											array("code" => "GF", "country" => "Guyane française"),
											array("code" => "HT", "country" => "Haïti"),
											array("code" => "HN", "country" => "Honduras"),
											array("code" => "HK", "country" => "Hong-Kong"),
											array("code" => "HU", "country" => "Hongrie"),
											array("code" => "AX", "country" => "Îles Aland"),
											array("code" => "BV", "country" => "Îles Bouvet"),
											array("code" => "KY", "country" => "Îles Caïmans"),
											array("code" => "CX", "country" => "Îles Christmas"),
											array("code" => "CC", "country" => "Îles Cocos"),
											array("code" => "CK", "country" => "Îles Cook"),
											array("code" => "IM", "country" => "Îles de Man"),
											array("code" => "FO", "country" => "Îles Féroé"),
											array("code" => "HM", "country" => "Îles Heard-et-MacDonald"),
											array("code" => "MP", "country" => "Îles Mariannes du Nord"),
											array("code" => "MH", "country" => "Îles Marshall"),
											array("code" => "NF", "country" => "Îles Norfolk"),
											array("code" => "SB", "country" => "Îles Salomon"),
											array("code" => "TC", "country" => "Îles Turques-et-Caïques"),
											array("code" => "VI", "country" => "Îles Vierges américaines"),
											array("code" => "IN", "country" => "Inde"),
											array("code" => "ID", "country" => "Indonésie"),
											array("code" => "IR", "country" => "Iran"),
											array("code" => "IQ", "country" => "Irak"),
											array("code" => "IE", "country" => "Irlande"),
											array("code" => "IS", "country" => "Islande"),
											array("code" => "IL", "country" => "Israël"),
											array("code" => "IT", "country" => "Italie"),
											array("code" => "JM", "country" => "Jamaïque"),
											array("code" => "JP", "country" => "Japon"),
											array("code" => "JE", "country" => "Jersey"),
											array("code" => "JO", "country" => "Jordanie"),
											array("code" => "KZ", "country" => "Kazakhstan"),
											array("code" => "KE", "country" => "Kenya"),
											array("code" => "KG", "country" => "Kirghizistan"),
											array("code" => "KI", "country" => "Kiribati"),
											array("code" => "XK", "country" => "Kosovo"),
											array("code" => "KW", "country" => "Koweït"),
											array("code" => "RE", "country" => "La Réunion"),
											array("code" => "LA", "country" => "Laos"),
											array("code" => "LS", "country" => "Lesotho"),
											array("code" => "LV", "country" => "Lettonie"),
											array("code" => "LB", "country" => "Liban"),
											array("code" => "LR", "country" => "Libéria"),
											array("code" => "LY", "country" => "Lybie"),
											array("code" => "LI", "country" => "Liechtenstein"),
											array("code" => "LT", "country" => "Lituanie"),
											array("code" => "LU", "country" => "Luxembourg"),
											array("code" => "MO", "country" => "Macao"),	
											array("code" => "MK", "country" => "Macédoine"),
											array("code" => "MG", "country" => "Madagascar"),
											array("code" => "MY", "country" => "Malaisie"),
											array("code" => "MW", "country" => "Malawi"),
											array("code" => "MV", "country" => "Maldives"),
											array("code" => "ML", "country" => "Mali"),
											array("code" => "FK", "country" => "Malouines (Falkland"),
											array("code" => "MT", "country" => "Malte"),
											array("code" => "MA", "country" => "Maroc"),
											array("code" => "MQ", "country" => "Martinique"),
											array("code" => "MU", "country" => "Maurice"),
											array("code" => "MR", "country" => "Mauritanie"),
											array("code" => "YT", "country" => "Mayotte"),
											array("code" => "MX", "country" => "Mexique"),
											array("code" => "FM", "country" => "Micronésie"),
											array("code" => "MD", "country" => "Moldavie"),
											array("code" => "MC", "country" => "Monaco"),
											array("code" => "MN", "country" => "Mongolie"),
											array("code" => "ME", "country" => "Monténégro"),
											array("code" => "MS", "country" => "Montserrat"),
											array("code" => "MZ", "country" => "Mozambique"),
											array("code" => "NA", "country" => "Namibie"),	
											array("code" => "NR", "country" => "Nauru"),	
											array("code" => "NP", "country" => "Népal"),	
											array("code" => "NI", "country" => "Nicaragua"),	
											array("code" => "NE", "country" => "Niger"),	
											array("code" => "NG", "country" => "Nigeria"),	
											array("code" => "NU", "country" => "Niue"),		
											array("code" => "NO", "country" => "Norvège"),
											array("code" => "NC", "country" => "Nouvelle-Calédonie"),
											array("code" => "NZ", "country" => "Nouvelle-Zélande"),		
											array("code" => "OM", "country" => "Oman"),
											array("code" => "UG", "country" => "Ouganda"),
											array("code" => "UZ", "country" => "Ouzbékistan"),	
											array("code" => "PK", "country" => "Pakistan"),		
											array("code" => "PW", "country" => "Palaos"),	
											array("code" => "PS", "country" => "Palestine"),	
											array("code" => "PA", "country" => "Panama"),	
											array("code" => "PG", "country" => "Papouasie-Nouvelle-Guinée"),	
											array("code" => "PY", "country" => "Paraguay"),						
											array("code" => "NL", "country" => "Pays-Bas"),	
											array("code" => "PE", "country" => "Pérou"),	
											array("code" => "PH", "country" => "Philippines"),
											array("code" => "PN", "country" => "Pitcairn"),		
											array("code" => "PL", "country" => "Pologne"),	
											array("code" => "PF", "country" => "Polynésie française"),
											array("code" => "PR", "country" => "Porto Rico"),	
											array("code" => "PT", "country" => "Portugal"),
											array("code" => "QA", "country" => "Qatar"),
											array("code" => "CF", "country" => "République Centrafricaine"),
											array("code" => "CD", "country" => "République démocratique du Congo"),
											array("code" => "DO", "country" => "République dominicaine"),
											array("code" => "CG", "country" => "République du Congo"),
											array("code" => "CZ", "country" => "République Tchèque"),
											array("code" => "RO", "country" => "Roumanie"),
											array("code" => "GB", "country" => "Royaume-Uni"),
											array("code" => "RU", "country" => "Russie"),
											array("code" => "RW", "country" => "Rwanda"),
											array("code" => "EH", "country" => "Sahara occidental"),
											array("code" => "BL", "country" => "Saint-Barthélemy"),
											array("code" => "SH", "country" => "Sainte-Hélène"),
											array("code" => "KN", "country" => "Saint-Kitts-et-Nevis"),
											array("code" => "LC", "country" => "Sainte-Lucie"),
											array("code" => "SM", "country" => "Saint-Marin"),
											array("code" => "MF", "country" => "Saint-Martin (partie française)"),
											array("code" => "SX", "country" => "Saint-Martin (partie néerlandaise)"),
											array("code" => "PM", "country" => "Saint-Pierre-et-Miquelon"),
											array("code" => "VC", "country" => "Saint-Vincent-et-les Grenadines"),
											array("code" => "ws", "country" => "Samoa"),
											array("code" => "AS", "country" => "Samoa américaines"),
											array("code" => "ST", "country" => "Sao Tomé-et-Principe"),
											array("code" => "SN", "country" => "Sénégal"),
											array("code" => "RS", "country" => "Serbie"),
											array("code" => "SC", "country" => "Seychelles"),
											array("code" => "SL", "country" => "Sierra Leone"),
											array("code" => "SG", "country" => "Singapour"),
											array("code" => "LK", "country" => "Sir Lanka"),
											array("code" => "SK", "country" => "Slovaquie"),
											array("code" => "SI", "country" => "Slovénie"),
											array("code" => "SO", "country" => "Somalie"),
											array("code" => "SD", "country" => "Soudan"),
											array("code" => "SS", "country" => "Sud-Soudan"),
											array("code" => "SE", "country" => "Suède"),
											array("code" => "CH", "country" => "Suisse"),
											array("code" => "SR", "country" => "Suriname"),
											array("code" => "SJ", "country" => "Svalbar et Jan Mayen"),
											array("code" => "SY", "country" => "Syrie"),
											array("code" => "TW", "country" => "Taïwan"),
											array("code" => "TJ", "country" => "Tadjikistan"),
											array("code" => "TZ", "country" => "Tanzanie"),
											array("code" => "TD", "country" => "Tchad"),
											array("code" => "TF", "country" => "Terre australes et antarctiques française"),
											array("code" => "IO", "country" => "Territoire Britanique de l'Océan Indien"),
											array("code" => "TH", "country" => "Thaïlande"),
											array("code" => "TL", "country" => "Timor-Leste"),
											array("code" => "TG", "country" => "Togo"),
											array("code" => "TK", "country" => "Tokelau"),
											array("code" => "TO", "country" => "Tonga"),
											array("code" => "TT", "country" => "Trinité-et-Tobago"),
											array("code" => "TN", "country" => "Tunisie"),
											array("code" => "TM", "country" => "Turkménistan"),
											array("code" => "TR", "country" => "Turquie"),
											array("code" => "TV", "country" => "Tuvalu"),
											array("code" => "UA", "country" => "Ukraine"),
											array("code" => "UY", "country" => "Uruguay"),
											array("code" => "VU", "country" => "Vanuatu"),
											array("code" => "VA", "country" => "Vatican"),
											array("code" => "VE", "country" => "Venezuela"),
											array("code" => "VN", "country" => "Viêt Nam"),
											array("code" => "WF", "country" => "Wallis-et-Futuna"),
											array("code" => "YE", "country" => "Yémen"),
											array("code" => "ZM", "country" => "Zambie"),
											array("code" => "ZW", "country" => "Zimbabwe"),
										)));
	
	define("STATUT_CNAPS", serialize(array ("en cours", "en attente", "matriculé")));
	define("TYPE_PAIEMENT", serialize(array ("par chèque", "par virement", "en espèce")));
	define("CONTRAT", serialize(array ("CDI", "CDD", "Stage", "Itérim", "Saisonnier", "Journalier")));
	define("CHAMP_NOM_SALARIE", '[NOM_SALARIE]');
    define("CHAMP_PRENOM_SALARIE", '[PRENOM_SALARIE]');
    define("CHAMP_CIVILITE_SALARIE", '[CIVILITE_SALARIE]');
    define("CHAMP_ADRESSE_SALARIE", '[ADRESSE_SALARIE]');
    define("CHAMP_SALAIRE_EN_CHIFFRE", '[SALAIRE_EN_CHIFFRE]');
    define("CHAMP_SALAIRE_EN_LETTRE", '[SALAIRE_EN_LETTRE]');
    define("CHAMP_DATE_DE_NAISSANCE", '[DATE_DE_NAISSANCE]');
    define("CHAMP_NUMERO_CIN", '[NUMERO_CIN]');
    define("CHAMP_DATE_CIN", '[DATE_CIN]');
    define("CHAMP_LIEU_CIN", '[LIEU_CIN]');
    define("CHAMP_NOM_ENTREPRISE", '[NOM_ENTREPRISE]');
    define("CHAMP_NIF_ENTREPRISE", '[NIF_ENTREPRISE]');
    define("CHAMP_STAT_ENTREPRISE", '[STAT_ENTREPRISE]');
    define("CHAMP_ADRESSE_ENTREPRISE", '[ADRESSE_ENTREPRISE]');
    define("CHAMP_RCS_ENTREPRISE", '[RCS_ENTREPRISE]');
    define("CHAMP_REPRESENTANT_ENTREPRISE", '[REPRESENTANT_ENTREPRISE]');
    define("CHAMP_QUALITE_REPRESENTANT", '[QUALITE_REPRESENTANT]');
    define("CHAMP_DATE_DE_DEBUT", '[DATE_DE_DEBUT]');
    define("CHAMP_DATE_DE_FIN", '[DATE_DE_FIN]');
    define("CHAMP_POSTE", '[POSTE]');
    define("CHAMP_SERVICE", '[SERVICE]');
    define("CHAMP_CATEGORIE_PROFESSIONNELLE", '[CATEGORIE_PROFESSIONNELLE]');
    define("CHAMP_NOTIFICATION", '[NOTIFICATION]');
    define("CHAMP_ESSAI", '[ESSAI]');
    define("CHAMP_OBLIGATIONS", '[OBLIGATIONS]');
    define("CHAMP_POSTES_OCCUPES", '[POSTES_OCCUPES]');
    define("CHAMP_DATE_DU_JOUR", '[DATE_DU_JOUR]');
    define("CHAMP_BENEFICE_CONGE", '[BENEFICE_CONGE]');

	$appLoader = new SplClassLoader('App', __DIR__);
	$appLoader->register();	
	use \App\Frontend\FrontendApplication;
	use \App\Backend\BackendApplication;

	$coreLoader = new SplClassLoader('Core', __DIR__. '/Lib/');
	$coreLoader->register();

	$moduleFrontLoader = new SplClassLoader('Modules', __DIR__.'/App/Frontend/');
	$moduleFrontLoader->register();

	$moduleBackLoader = new SplClassLoader('Modules', __DIR__.'/App/Backend/');
	$moduleBackLoader->register();

	$entityLoader = new SplClassLoader('Entity', __DIR__.'/Lib/Vendors/');
	$entityLoader->register();

	$modelLoader = new SplClassLoader('Model', __DIR__.'/Lib/Vendors/');
	$modelLoader->register();
	/** @changeLog 2023-05-19 Lansky [FIX] Supprimer l'ajout fbclid depuis Facebook dans l'url */
	if (isset($_GET['fbclid'])) {
		unset($_GET['fbclid']);
	}
	$request = $_GET;
	if (empty($request)) {
		header("Location : " . HOST . "connexion");
	} 
	if (isset($_SESSION['compte']) && isset($request['page'])) {
		if ($request["page"] == "connexion") {
			$_SESSION['info']['info'] = "Vous êtes connecté";
			header("Location:" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
			exit();
		}
	}
	$applicationName          = 'Frontend';
	$_SESSION['isBackOffice'] = false;
	if (strstr($request['page'], "manage")) {
		if (isset($_SESSION['compte'])) {
			$applicationName 		  = 'Backend';
			$_SESSION['isBackOffice'] = true;
		} else {
			$_SESSION['info']['info'] = "Veuillez vous connecter s'il vous plaît";
			header("Location:" . HOST . "connexion");
			exit();
		}
	} 
	$application 	= '\App\\' . $applicationName . '\\' . $applicationName . 'Application';
	$app 			= new $application($request);
	$app->run();