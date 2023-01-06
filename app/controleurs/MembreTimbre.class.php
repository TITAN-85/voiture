<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Timbre de l'application admin
 */

class MembreTimbre extends Membre {

  protected $methodes = [
    'l' => ['nom'    => 'listerTimbre'],
    'a' => ['nom'    => 'listerAccueil'],
    'm' => ['nom'    => 'modifierEnchere'],
    's' => ['nom'    => 'supprimerTimbre'],
    'fo'=> ['nom'    => 'formAjouterEnchere'],
    'f' => ['nom'    => 'listerTimbresMembre'],
    'h' => ['nom'    => 'getTimbresMembreMise']
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
    date_default_timezone_set('America/Toronto');

  }

  /**
   * Lister les timbres
   */
  public function listerTimbre() {
    $dateCourent = date('Y-m-d');
    $timbres = $this->oRequetesSQL->getTimbres('membre');
    (new Vue)->generer(
      'vMembreTimbres',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des timbres',
        'timbres'               => $timbres,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction,
        'dateCourent'         => $dateCourent
      ],
      'gabarit-membre');
  }

  /**
   * Lister les Timbres
   */
  public function listerTimbresMembre() {
    $dateCourent = date('Y-m-d');

    $timbres = $this->oRequetesSQL->getTimbresMembre(self::$oUtilConn);
    // echo "<pre>" . print_r($timbres, true) . "<pre>"; exit;
    (new Vue)->generer(
      'vMembreTimbres',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des utilisateurs',
        'timbres'             => $timbres, 
        'dateCourent'         => $dateCourent, 
        
      ],
      'gabarit-membre');
  }
  /**
   * Lister les Timbres
   */
  public function getTimbresMembreMise() {
    $dateCourent = date('Y-m-d');
    $timbres = $this->oRequetesSQL->getTimbresMembreMise(self::$oUtilConn);
    // echo "<pre>" . print_r($timbres, true) . "<pre>"; exit;
    (new Vue)->generer(
      'vMembreTimbres',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des utilisateurs',
        'timbres'             => $timbres, 
        'dateCourent'         => $dateCourent, 
        
      ],
      'gabarit-membre');
  }

    /**
   * Voir les Accueil
   */  
  public function listerAccueil() {
    $dateCourent = date('Y-m-d');
    // $dateFin = date('Y-m-d', strtotime($dateCourent. "+14 days"));
    $timbres = $this->oRequetesSQL->getTimbres();
    // echo "<pre>" . print_r($timbres, true) . "<pre>"; exit;
    (new Vue)->generer("vListeAccueil",
            array(
              'timbres'             => $timbres,
              'oUtilConn'           => self::$oUtilConn,
              'dateCourent'         => $dateCourent,
              'titre'               => 'Gestion des utilisateurs'
            ),
            "gabarit-frontend");
  }
  /**
   * Form Ajouter Timbre
   */
  public function formAjouterEnchere() {
    // echo "<pre>" . print_r($this, true) . "<pre>"; exit;
    $pays = $this->oRequetesSQL->getPays();
    $categories = $this->oRequetesSQL->getCategorie();
    (new Vue)->generer(
      'vMembreEnchereAjouter',
      [
        'oUtilConn'           => self::$oUtilConn,
        'pays'                => $pays,
        'categories'           => $categories,
        'titre'               => 'Ajouter Timbre',
      ],
      'gabarit-membre');
  }




}