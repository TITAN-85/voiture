<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */
class Frontend extends Routeur {

  private $timbre_id;
  private $enchere_id;
  private $mise;
  private $oUtilConn;
  
  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->enchere_id = $_GET['enchere_id'] ?? null;
    $this->oUtilConn = $_SESSION['oUtilConn'] ?? null;
    $this->oRequetesSQL = new RequetesSQL; 
    date_default_timezone_set('America/Toronto');
    $this->mise = $_POST ?? null;
  }
  /* =============================================================================== */
  /**
   * Voir les Accueil
   */  
  public function listerAccueil() {
    $dateCourent = date('Y-m-d');
    $timbres = $this->oRequetesSQL->getTimbres();

    // echo "<pre>" . print_r($timbres, true) . "<pre>"; exit;

    $prixMoyene = 0;
    $kmMoyene = 0;
    $count = 0;
    
    if ($timbres) {
      foreach ($timbres as $timbre) {
        
        $prixMoyene = $timbre["timbre_prix"] + $prixMoyene;
        $kmMoyene = $timbre["timbre_km"] + $kmMoyene;
        
        // echo "<pre>" . print_r($careRate, true) . "<pre>"; exit;
        $count += 1;
      }
    }

    if ($prixMoyene) {$prixMoyene = round($prixMoyene / $count);}
    if ($kmMoyene) {$kmMoyene = round($kmMoyene / $count);}

    // echo "<pre>" . print_r($timbres, true) . "<pre>"; exit;

    (new Vue)->generer("vListeAccueil",
            array(
              'timbres'             => $timbres,
              'oUtilConn'           => $this->oUtilConn,
              'dateCourent'         => $dateCourent,
              'prixMoyene'          => $prixMoyene,
              'kmMoyene'            => $kmMoyene
            ),
            "gabarit-frontend");
  }

  /* =============================================================================== */


  public function listerCatalogueArch() {
    $dateCourent = date('Y-m-d');
    // $dateFin = date('Y-m-d', strtotime($dateCourent. "+14 days"));
    $timbres = $this->oRequetesSQL->getTimbres();
    // echo "<pre>" . print_r($timbres, true) . "<pre>"; exit;
    (new Vue)->generer("vListeCatalogueArch",
            array(
              'timbre_id'           => 'Timbres',
              'timbres'             => $timbres,
              'oUtilConn'           => $this->oUtilConn,
              'dateCourent'         => $dateCourent

            ),
            "gabarit-frontend");
  }


  /**
   * Voir la page ajouter utilisateur
   * 
   */  
  public function ajouterUtilisateur() {

    (new Vue)->generer("vUtilisateurAjouter",
            array(
              'oUtilConn'               => $this->oUtilConn
            ),
            "gabarit-frontend");
  }

  /**
   * Voir Catalogue
   * 
   */  
  public function listerCatalogue() {
    $dateCourent = date('Y-m-d');

    $timbres = $this->oRequetesSQL->getTimbres(); 

    // echo "<pre>" . print_r($this->oUtilConn, true) . "<pre>";
    // if (!$timbres) throw new Exception("Timbres inexistant.");

    (new Vue)->generer("vListeCatalogue",
            array(
              'timbre_id'           => 'Timbres ....Catalog...',
              'timbres'             => $timbres,
              'oUtilConn'           => $this->oUtilConn,
              'dateCourent'         => $dateCourent
            ),
            "gabarit-frontend");
  }

  /**
   * Voir les informations d'un timbre
   */  
  public function listerFiche() {
    
    $timbre = false;
    $dateCourent = date('Y-m-d');
    
    
    // if (!is_null($this->enchere_id)) {
      $timbre = $this->oRequetesSQL->getTimbre($this->enchere_id);
      $retour = $this->oRequetesSQL->getMisePrix($this->enchere_id);
      
      if ($retour) {
        $max_mise_prix = $retour[0]["mise_prix"];
      } else {
        $max_mise_prix = $timbre['mise_prix'];
      }

    (new Vue)->generer("vListeFiche",
            array(
              'timbre_id'           => $timbre['timbre_id'],
              'timbre_nom'          => $timbre['timbre_nom'],
              'timbre_description'  => $timbre['timbre_description'],
              'timbre_prix'         => $timbre['timbre_prix'],
              'timbre_km'           => $timbre['timbre_km'],
              'timbre_anee'         => $timbre['timbre_anee'],
              'pays_nom'            => $timbre['pays_nom'],
              'categorie_nom'       => $timbre['categorie_nom'],
              'enchere_date_debut'  => $timbre['enchere_date_debut'],
              'enchere_date_fin'    => $timbre['enchere_date_fin'],
              'timbre_image'        => $timbre['image_url'],
              'enchere_id'          => $timbre['enchere_id'],
              'mise_prix'           => $timbre['mise_prix'],
              'oUtilConn'           => $this->oUtilConn,
              'dateCourent'         => $dateCourent,
              'max_mise_prix'       => $max_mise_prix
            ),
            "gabarit-frontend");
  }




}