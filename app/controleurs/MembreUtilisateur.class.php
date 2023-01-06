<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class MembreUtilisateur extends Membre {

  protected $methodes = [
    'd'           => ['nom'    =>'deconnecter'],
    'a'           => ['nom'    =>'ajouterUtilisateur'],
    'm'           => ['nom'    =>'modifierUtilisateur'],
    'l'           => ['nom'    =>'listerTimbres'],
    'f'           => ['nom'    =>'listerTimbresMembre']
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->utilisateur_id = $_GET['utilisateur_id'] ?? null; 
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Connecter un utilisateur
   */
  public function connecter() {
    $this->oRequetesSQL = new RequetesSQL;
    $messageErreurConnexion = ""; 
    if (count($_POST) !== 0) {
      $u = $this->oRequetesSQL->connecter($_POST);
      if ($u !== false) {
        $_SESSION['oUtilConn'] = new Utilisateur($u);
        parent::gererEntite();
        exit;
      } else {
        $messageErreurConnexion = "Courriel ou mot de passe incorrect.";
      }
    }
    $dateCourent = date('Y-m-d');
    (new Vue)->generer(
      'vMembreUtilisateurConnecter',
      [
        'titre'                  => 'Connexion',
        'messageErreurConnexion' => $messageErreurConnexion,
      ],
      'gabarit-membre-login');
  }

  /**
   * Déconnecter un utilisateur
   */
  public function deconnecter() {
    unset ($_SESSION['oUtilConn']);
    $dateCourent = date('Y-m-d');
    $timbres = $this->oRequetesSQL->getTimbres(); 
    if (!$timbres) throw new Exception("Timbres inexistant.");
    (new Vue)->generer("vListeAccueil",
            array(
              'timbre_id'           => 'Timbres',
              'timbres'             => $timbres,
              'dateCourent'         => $dateCourent

            ),
            "gabarit-frontend");
  }

  /**
   * Lister les utilisateurs
   */
  public function listerUtilisateurs() {
    $utilisateurs = $this->oRequetesSQL->getUtilisateurs();
    (new Vue)->generer(
      'vMembreUtilisateurs',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des utilisateurs',
        'utilisateurs'        => $utilisateurs,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction
      ],
      'gabarit-membre');
  }

  /**
   * Ajouter un utilisateur
   */
  public function ajouterUtilisateur() {
    $msgErrAjouterUtilisateur = '';
    if (count($_POST) !== 0) {
      $utilisateur = $_POST;
      $oUtilisateur = new Utilisateur($utilisateur);
      // echo "<pre>" . print_r($oUtilisateur, true) . "<pre>"; exit;
      // $oUtilisateur->courrielExiste(); // DO NOT DELETE !!! Verify if mail exist!
      $erreurs = $oUtilisateur->erreurs;
      if (count($erreurs) === 0) {
        $retour = $this->oRequetesSQL->ajouterUtilisateur([
          'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
          'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
          'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
          'utilisateur_mdp'      => $oUtilisateur->utilisateur_mdp,
          'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
        ]);
        if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
          
          $this->pageConnection();
          exit;
        } else {
          $erreurs['utilisateur_courriel'] = $retour;
          // echo "<pre>" . print_r($msgErrAjouterUtilisateur, true) . "<pre>"; exit;
        }
      }
    } else {
      $msgErrAjouterUtilisateur = "Beaucoup des erreures OMG";
      $utilisateur = [];
      $erreurs     = [];
    }

    (new Vue)->generer(
      'vUtilisateurAjouter',
      [
        'msgErrAjouterUtilisateur' => $msgErrAjouterUtilisateur,
        'oUtilConn'   => self::$oUtilConn,
        'titre'       => 'Ajouter un utilisateur',
        'utilisateur' => $utilisateur,
        'erreurs'     => $erreurs
      ],
      'gabarit-frontend');
  }

  /**
   * Lister les Timbres
   */ 
  public function pageConnection() {
    (new Vue)->generer(
      'vMembreUtilisateurConnecter',
      [
        'titre'               => 'Gestion des utilisateurs',
      ],
      'gabarit-membre-login');
  }

  public function pageAjouterUtilisateur() {
    (new Vue)->generer(
      'vUtilisateurAjouter',
      [
        'msgErrAjouterUtilisateur' => $this->msgErrAjouterUtilisateur,
        'titre'               => 'Gestion des utilisateurs',
      ],
      'gabarit-frontend');
  }


  /**
   * Modifier un utilisateur
   */
  public function modifierUtilisateur() {
    if (!preg_match('/^\d+$/', $this->utilisateur_id))
      throw new Exception("Numéro d'utilisateur non renseigné pour une modification");

    if (count($_POST) !== 0) {
    $utilisateur = $_POST;
    $oUtilisateur = new Utilisateur($utilisateur);
    $oUtilisateur->courrielExiste();
    $erreurs = $oUtilisateur->erreurs;
    if (count($erreurs) === 0) {
      $retour = $this->oRequetesSQL->modifierUtilisateur([
        'utilisateur_id'       => $oUtilisateur->utilisateur_id, 
        'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
        'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
        'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
        'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
      ]);
      if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
        if ($retour === true)  {
          $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id effectuée.";    
        } else {  
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id non effectuée.";
        }
        $this->listerUtilisateurs();
        exit;
      } else {
        $erreurs['utilisateur_courriel'] = $retour;
      }
    }
    } else {
      $utilisateur = $this->oRequetesSQL->getUtilisateur($this->utilisateur_id);
      $erreurs = [];
    }
  
    (new Vue)->generer(
    'vAdminUtilisateurModifier',
    [
      'oUtilConn'   => self::$oUtilConn,
      'titre'       => "Modifier l'utilisateur numéro $this->utilisateur_id",
      'utilisateur' => $utilisateur,
      'erreurs'     => $erreurs
    ],
    'gabarit-admin');
  }
  
}