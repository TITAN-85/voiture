<?php


/**
 * Classe Contrôleur des requêtes de l'interface Enchere
 * 
 */

class EnchereTimbre extends Routeur
{

  protected $timbre_id;
  protected $enchere_id;
  protected $utilisateur_id;
  protected $entite;
  protected $action;
  protected $oUtilConn;
  protected $timbreNom;

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct()
  {
    $this->utilisateur_id = $_GET['utilisateur_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
    date_default_timezone_set('America/Toronto');
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->enchere_id = $_GET['enchere_id'] ?? null;
    $this->timbreNom = '';
    $this->oUtilConn = $_SESSION['oUtilConn'] ?? null;

  }

  /**
   * Form Ajouter Timbre
   */
  public function ajouterEnchere()
  {
    if (count($_POST) !== 0) {
      $oEnchere = new Enchere($_POST);
      $erreurs = $oEnchere->erreurs;
      $dateDebut = date('Y-m-d');

      $dateFin = date('Y-m-d', strtotime($dateDebut. "+360 days"));
      if (count($erreurs) === 0) {

        $this->timbreNom = $oEnchere->timbre_nom;
        
        $utilisateur_conecte = $_SESSION["oUtilConn"]->utilisateur_id;

        // echo "<pre>" . print_r($oEnchere, true) . "<pre>"; exit;

        $careRate = 0;
        $careRate = $this->careRateVerifier($oEnchere);

        // echo "<pre>" . print_r($oEnchere, true) . "<pre>"; exit;

        
        $retour = $this->oRequetesSQL->ajouterEnchere([
          'enchere_date_debut'   => $dateDebut,
          'enchere_date_fin'     => $dateFin,
          'enchere_utilisateur_id' => $utilisateur_conecte,
        ]);

        $retour = $this->oRequetesSQL->ajouterTimbre([
          'timbre_nom'           => $oEnchere->timbre_nom,
          'timbre_description'   => $oEnchere->timbre_description,
          'timbre_prix'          => $oEnchere->timbre_prix,
          'timbre_km'            => $oEnchere->timbre_km,
          'timbre_anee'          => $oEnchere->timbre_anee,
          'timbre_rate'          => $careRate,
          'timbre_categorie_id'  => $oEnchere->timbre_categorie,
          'timbre_pays_id'       => $oEnchere->timbre_pays,
          'timbre_utilisateur_id'=> $utilisateur_conecte,
          'timbre_enchere_id'    => $retour
        ]);

        $nom_fichier = $_FILES['userfile']['name'];
        $fichier = $_FILES['userfile']['tmp_name'];
        move_uploaded_file($fichier, "assets/jpeg/timbres/" . $nom_fichier);

        // echo "<pre>" . print_r($derniereEnchereId, onntrue) . "<pre>"; exit;
        $retour = $this->oRequetesSQL->ajouterImage([
          'image_url'         => $nom_fichier,
          'image_timbre_id'   => $retour
        ]);
        $this->genererViewMembre();
        exit;
      }
    } else {
      $enchere     = [];
      $erreurs     = [];
      
    }
    $pays = $this->oRequetesSQL->getPays();
    $categories = $this->oRequetesSQL->getCategorie();
    $utilisateur_conecte = $_SESSION["oUtilConn"]->utilisateur_id;
    (new Vue)->generer(
      'vMembreEnchereAjouter',
      [
        'oUtilConn'           => $utilisateur_conecte,
        'pays'                => $pays,
        'categories'          => $categories,
        'titre'               => 'Ajouter un Timbre',
        'erreurs'             => $erreurs
      ],
      'gabarit-membre'
    );
  }


  public function careRateVerifier($oEnchere) {
    $careRate = 0;
    // echo "<pre>" . print_r($careRate, true) . "<pre>"; exit;

    if ($oEnchere->timbre_km <= 80000) {
      $careRate = $careRate + 135;
    } elseif ($oEnchere->timbre_km > 80000 && $oEnchere->timbre_km  <= 85000) {
      $careRate = $careRate + 128;
    } elseif ($oEnchere->timbre_km > 85000 && $oEnchere->timbre_km  <= 90000) {
      $careRate = $careRate + 121;
    } elseif ($oEnchere->timbre_km > 90000 && $oEnchere ->timbre_km <= 95000) {
      $careRate = $careRate + 114;
    } elseif ($oEnchere->timbre_km > 95000 && $oEnchere->timbre_km <= 100000) {
      $careRate = $careRate + 107;
    }elseif ($oEnchere->timbre_km > 100000 && $oEnchere->timbre_km <= 105000) {
      $careRate = $careRate + 100;
    }elseif ($oEnchere->timbre_km > 105000 && $oEnchere->timbre_km <= 110000) {
      $careRate = $careRate + 93;
    }elseif ($oEnchere->timbre_km > 110000 && $oEnchere->timbre_km <= 115000) {
      $careRate = $careRate + 86;
    }elseif ($oEnchere->timbre_km > 115000 && $oEnchere->timbre_km <= 120000) {
      $careRate = $careRate + 79;
    }elseif ($oEnchere->timbre_km > 120000 && $oEnchere->timbre_km <= 125000) {
      $careRate = $careRate + 72;
    }elseif ($oEnchere->timbre_km > 125000 && $oEnchere->timbre_km <= 130000) {
      $careRate = $careRate + 65;
    }elseif ($oEnchere->timbre_km > 130000 && $oEnchere->timbre_km <= 135000) {
      $careRate = $careRate + 58;
    }elseif ($oEnchere->timbre_km > 135000 && $oEnchere->timbre_km <= 140000) {
      $careRate = $careRate + 51;
    }elseif ($oEnchere->timbre_km > 140000 && $oEnchere->timbre_km <= 145000) {
      $careRate = $careRate + 44;
    }elseif ($oEnchere->timbre_km > 145000 && $oEnchere->timbre_km <= 150000) {
      $careRate = $careRate + 37;
    }


    if ($oEnchere->timbre_anee == 2015) {
      $careRate = $careRate + 28;
    } elseif ($oEnchere->timbre_anee == 2016) {
      $careRate = $careRate + 56;
    } elseif ($oEnchere->timbre_anee == 2017) {
      $careRate = $careRate + 84;
    } elseif ($oEnchere->timbre_anee < 2015) {
      $careRate = $careRate + 5;
    } elseif ($oEnchere->timbre_anee > 2017) {
      $careRate = $careRate + 112;
    }

    if ($oEnchere->timbre_pays == 1) {
      $careRate = $careRate - 3;
    } elseif ($oEnchere->timbre_pays == 2) {
      $careRate = $careRate + 8;
    } elseif ($oEnchere->timbre_pays == 3) {
      $careRate = $careRate + 10;
    } elseif ($oEnchere->timbre_pays == 4) {
      $careRate = $careRate + 20;
    }

    if ($oEnchere->timbre_categorie == 1) {
      $careRate = $careRate - 3;
    } elseif ($oEnchere->timbre_categorie == 2) {
      $careRate = $careRate + 6;
    }

    return $careRate;
  }


  public function supprimerEnchere() {
    $message = '';
    // if (!$this->timbre_id) throw new Exception("Timbres inexistant.");
    
    
    if (!is_null($this->enchere_id)) {
      
      $timbre = $this->oRequetesSQL->getTimbre($this->enchere_id);
      
      // echo "<pre>" . print_r($timbre, true) . "<pre>"; exit;

      $timbre_id = $timbre["timbre_id"];
      $timbre_enchere_id = $timbre["timbre_enchere_id"];
      $timbre_nom = $timbre["timbre_nom"];


      $image_id = $this->oRequetesSQL->getImage($timbre_id);
      $image_id = $image_id[0]["image_id"];
      
      $enchere_id = $this->oRequetesSQL->getEnchere($timbre_enchere_id);
      $enchere_id = $enchere_id[0]["enchere_id"];
      // echo "<pre>" . print_r($image_id, true) . "<pre>";exit;

      $mise = $this->oRequetesSQL->getMise($enchere_id);
      // echo "<pre>" . print_r($mise, true) . "<pre>"; exit;

      if (!$mise) {
        $this->oRequetesSQL->supprimerImage($image_id);
        $this->oRequetesSQL->supprimerTimbre($timbre_id);
        $this->oRequetesSQL->supprimerEnchere($timbre_enchere_id);
      } else {
        $message = "Vous ne pouvez pas effacer l'enchere $timbre_nom mise courent";
        $this->membreEnchereView($message);
        exit;
      }
      // echo "<pre>" . print_r("ok", true) . "<pre>";exit;
    }
    $message = "Votre enchere " . " \"$timbre_nom\" " . " est supprimee!";
    $this->membreEnchereView($message);
  }

  public function membreEnchereView($message) {
    $dateCourent = date('Y-m-d');
    $oUtilConn = $_SESSION['oUtilConn'];
    $timbres = $this->oRequetesSQL->getTimbresMembre($oUtilConn);
    (new Vue)->generer(
      'vMembreTimbres',
      [
        'oUtilConn'           => $oUtilConn,
        'titre'               => 'Gestion des utilisateurs',
        'timbres'             => $timbres, 
        'message'             => $message,
        'dateCourent'         => $dateCourent, 
      ],
      'gabarit-membre');
  }

  public function rechercherEnchere() {

    if(count($_POST)!==0){

      $valeurRecherchee = $_POST["enchere_recherchee"];

      $retour = $this->oRequetesSQL->rechercherEnchere($valeurRecherchee);
      if ($retour) {
        $this->genererViewCatalog($retour);
      } else {
        $retour = $this->oRequetesSQL->getTimbres(); 
        $this->genererViewCatalog($retour);
      }

    } 
  }

  public function genererViewMembre() {
    $oUtilConn = $_SESSION['oUtilConn'];
    $dateCourent = date('Y-m-d');
    $timbres = $this->oRequetesSQL->getTimbresMembre($oUtilConn);
    (new Vue)->generer(
      'vMembreTimbres',
      [
        'message'             => 'Timbre '. ' "' . $this->timbreNom . '" '. 'a ete ajoute',
        'titre'               => 'Ajouter Timbre',
        'timbres'             => $timbres,
        'dateCourent'         => $dateCourent, 
      ],
      'gabarit-membre'
    );
  }

  public function genererViewCatalog($timbres) {
    $dateCourent = date('Y-m-d');

    // echo "<pre>" . print_r($this->oUtilConn, true) . "<pre>";exit;
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
   * Voir les modifier Enchere View
   */  
  public function modifierEnchereView() {

    $timbre = false;
    $dateCourent = date('Y-m-d');

    
    if (!is_null($this->timbre_id)) {
      $timbre = $this->oRequetesSQL->getTimbre($this->timbre_id);
    }
    $categories = $this->oRequetesSQL->getCategorie();
    $pays = $this->oRequetesSQL->getPays();

    

    // echo "<pre>" . print_r($categories, true) . "<pre>"; exit;

    (new Vue)->generer("vMembreEnchereModifier",
            array(
              'timbre_id'           => $timbre['timbre_id'],
              'timbre_nom'          => $timbre['timbre_nom'],
              'timbre_description'  => $timbre['timbre_description'],
              'timbre_prix'         => $timbre['timbre_prix'],
              'timbre_km'           => $timbre['timbre_km'],
              'pays_nom'            => $timbre['pays_nom'],
              'categorie_nom'       => $timbre['categorie_nom'],
              'enchere_date_debut'  => $timbre['enchere_date_debut'],
              'enchere_date_fin'    => $timbre['enchere_date_fin'],
              'timbre_image'        => $timbre['image_url'],
              'oUtilConn'           => $this->oUtilConn,
              'dateCourent'         => $dateCourent,
              'categories'          => $categories,
              'pays'                => $pays

            ),
            "gabarit-membre");
  }



// TODO:
  // public function modifierEnchere() {


  // }



}
  