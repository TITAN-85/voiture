<?php

/**
 * Classe Routeur
 * analyse l'uri et exécute la méthode associée  
 *
 */
class Routeur {

  private $routes = [
  // uri,                   classe,                                méthode
  // ---------------------------------------------------------------------
    ["membre",              "Membre",                       "gererEntite"],
    ["admin",               "Admin",                        "gererEntite"],
    ["",                    "Frontend",                   "listerAccueil"],
    // ["/",                   "Frontend",                   "listerAccueil"],
    ["accueil",             "Frontend",                   "listerAccueil"],
    // ["/accueil",             "Frontend",                  "listerAccueil"],
    ["fiche",               "Frontend",                     "listerFiche"],
    ["modifier",            "EnchereTimbre",        "modifierEnchereView"],
    ["modifEnchere",        "EnchereTimbre",            "modifierEnchere"],
    ["supprimer",           "EnchereTimbre",           "supprimerEnchere"],
    ["mise",                "MiseTimbre",                          "mise"],
    ["rechercher",          "EnchereTimbre",          "rechercherEnchere"],
    ["catalogue",           "Frontend",                 "listerCatalogue"],
    ["catalogueArch",       "Frontend",             "listerCatalogueArch"],
    ["ajouterUtilisateur",  "Frontend",              "ajouterUtilisateur"],
    ["creeUtilisateur",     "MembreUtilisateur",     "ajouterUtilisateur"],
    ["ajEnchere",           "EnchereTimbre",             "ajouterEnchere"],

    // ["",                    "Membre",                       "gererEntite"],

  ];

  protected $oRequetesSQL; // objet RequetesSQL utilisé par tous les contrôleurs
  
  // WEBDEV:
  // const BASE_URI = '/Projet-web-1/';

  // HOME
  // const BASE_URI = '/git/voiture/'; 

  //.ionos.com
  // const BASE_URI = '/kunden/homepages/41/d947040641/htdocs/';
  const BASE_URI = '/accueil/'; 

  
  const ERROR_FORBIDDEN = "HTTP 403";
  const ERROR_NOT_FOUND = "HTTP 404";

  /**
   * Valider l'URI
   * et instancier la méthode du contrôleur correspondante
   *
   */
  public function router() {
    try {

      // contrôle de l'uri si l'action coïncide

      $uri =  $_SERVER['REQUEST_URI'];
      // $uri =  " http://garage-alex.online/accueil";
      // echo "<pre>" . print_r($uri, true) . "<pre>"; exit; // reponse $uri:  /
      if (strpos($uri, '?')) $uri = strstr($uri, '?', true);

      foreach ($this->routes as $route) {

        $routeUri     = self::BASE_URI.$route[0];
        $routeClasse  = $route[1];
        $routeMethode = $route[2];
        
        if ($routeUri ===  $uri) {
          // on exécute la méthode associée à l'uri
          $oRouteClasse = new $routeClasse;
          $oRouteClasse->$routeMethode();  
          exit;
        }
      }
      // aucune route ne correspond à l'uri
      throw new Exception(self::ERROR_NOT_FOUND);
    }
    catch (Error | Exception $e) {
      $this->erreur($e);
    }
  }

  /**
   * Méthode qui envoie un compte-rendu d'erreur
   * @param Exception $e
   */
  public function erreur($e) {
    $message = $e->getMessage();
    if ($message == self::ERROR_FORBIDDEN) {
      header('HTTP/1.1 403 Forbidden');
    } else if ($message == self::ERROR_NOT_FOUND) {
      header('HTTP/1.1 404 Not Found');
      (new Vue)->generer('vErreur404', [], 'gabarit-erreur');
    } else {
      header('HTTP/1.1 500 Internal Server Error');
      (new Vue)->generer(
        "vErreur500",
        array('message' => $message, 'fichier' => $e->getFile(), 'ligne' => $e->getLine()),
        'gabarit-erreur'
      );
    }
    exit;
  }
}