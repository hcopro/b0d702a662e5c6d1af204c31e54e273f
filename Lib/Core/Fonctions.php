<?php
	
	/**
	 * Les fonctions utiles
	 *
	 * @author Voahirana
	 *
	 * @since 26/09/19 
	 */

	namespace Core;

	abstract class Fonctions
	{
		/** 
		 * Validation des données
		 * 
		 * @return empty
		 */
		public function validerDonnees($data)
		{	
			echo "<br>Je suis dans test de Fonctions";
			foreach ($data as $key => $value) {
				echo "<br>". $key ." => ". $value;
			}
		}

	}
