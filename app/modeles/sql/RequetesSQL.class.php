<?php

/**
 * Classe des requêtes SQL
 *
 */
class RequetesSQL extends RequetesPDO {



  /* GESTION DES UTILISATEURS 
     ======================== */

  /**
   * Récupération des utilisateurs
   * @return array tableau d'objets Utilisateur
   */ 
  public function getUtilisateurs() {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil_id
      FROM utilisateur ORDER BY utilisateur_id DESC";
     return $this->getLignes();
  }

  /**
   * Récupération d'un utilisateur
   * @param int $utilisateur_id, clé du utilisateur  
   * @return object Utilisateur
   */ 
  public function getUtilisateur($utilisateur_id) {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil_id
      FROM utilisateur
      WHERE utilisateur_id = :utilisateur_id";
    return $this->getLignes(['utilisateur_id' => $utilisateur_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Contrôler si adresse courriel non déjà utilisée par un autre utilisateur que utilisateur_id
   * @param array $champs tableau utilisateur_courriel et utilisateur_id (0 si dans toute la table)
   * @return string|false utilisateur avec ce courriel, false si courriel disponible
   */ 
  public function controlerCourriel($champs) {
    $this->sql = 'SELECT utilisateur_id FROM utilisateur
                  WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_id != :utilisateur_id';
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Connecter un utilisateur
   * @param array $champs, tableau avec les champs utilisateur_courriel et utilisateur_mdp  
   * @return object Utilisateur
   */ 
  public function connecter($champs) {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil_id
      FROM utilisateur
      WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_mdp = SHA2(:utilisateur_mdp, 512)";
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterUtilisateur($champs) {
    // echo "<pre>" . print_r($champs, true) . "<pre>"; exit;
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => 0]);
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      INSERT INTO utilisateur SET
      utilisateur_nom      = :utilisateur_nom,
      utilisateur_prenom   = :utilisateur_prenom,
      utilisateur_courriel = :utilisateur_courriel,
      utilisateur_mdp      = SHA2(:utilisateur_mdp, 512),
      utilisateur_profil_id   = :utilisateur_profil';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierUtilisateur($champs) {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => $champs['utilisateur_id']]);
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      UPDATE utilisateur SET
      utilisateur_nom      = :utilisateur_nom,
      utilisateur_prenom   = :utilisateur_prenom,
      utilisateur_courriel = :utilisateur_courriel,
      utilisateur_profil_id   = :utilisateur_profil_id
      WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne($champs);
  }

 /**
   * Modifier le mot de passe d'un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean true si modifié, false sinon
   */ 
  public function modifierUtilisateurMdp($champs) {
    $this->sql = '
      UPDATE utilisateur SET utilisateur_mdp  = SHA2(:utilisateur_mdp, 512)
      WHERE utilisateur_id = :utilisateur_id AND utilisateur_id > 3';
    return $this->CUDLigne($champs);
  }

  /**
   * Supprimer un utilisateur
   * @param int $utilisateur_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */ 
  public function supprimerUtilisateur($utilisateur_id) {
    $this->sql = '
      DELETE FROM utilisateur WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }

  /* GESTION DES TIMBRES 
  ============================================================ */

  public function getTimbres() {
    
    $this->sql = "SELECT enchere_id, mise_prix, image_url, timbre_id, 
    timbre_nom, timbre_description, timbre_prix, timbre_km, timbre_anee, 
    pays_nom, pays_id, categorie_nom, categorie_id, enchere_date_debut, enchere_date_fin, timbre_rate
    FROM timbre INNER JOIN pays ON pays_id = timbre_pays_id 
    INNER JOIN categorie ON categorie_id = timbre_categorie_id 
    INNER JOIN enchere ON enchere_id = timbre_enchere_id
    INNER JOIN image ON timbre_id = image_timbre_id
    LEFT OUTER JOIN mise ON enchere_id = mise_enchere_id
    and mise_prix =(select max(mise_prix) from mise 
    where enchere_id = mise_enchere_id)
    ORDER BY timbre_prix ASC";
    // echo "<pre>" . print_r($this->sql, true) . "<pre>"; exit;

    return $this->getLignes();
  }


  /* ============ Recevoir des enchere de membre ============ */
  public function getTimbresMembre($champs) {

    // echo "<pre>" . print_r($champs, true) . "<pre>"; exit;
    $this->sql = "SELECT mise_prix, image_url, timbre_id, timbre_nom, timbre_description, 
    timbre_prix, timbre_km, timbre_anee, pays_nom, categorie_nom, enchere_date_debut,
     enchere_date_fin, timbre_rate, enchere_id
    FROM timbre INNER JOIN pays ON pays_id = timbre_pays_id
    INNER JOIN categorie ON categorie_id = timbre_categorie_id
    INNER JOIN enchere ON enchere_id = timbre_enchere_id
    INNER JOIN image ON timbre_id = image_timbre_id
    LEFT OUTER JOIN mise ON enchere_id = mise_enchere_id 
    WHERE enchere_utilisateur_id = $champs->utilisateur_id
    ORDER BY timbre_id DESC";
    return $this->getLignes();
  }

    /* ============ Recevoir des enchere de membre ============ */
    public function getTimbresMembreMise($champs) {

      // echo "<pre>" . print_r($champs, true) . "<pre>"; exit;
      $this->sql = "SELECT image_url, timbre_id, timbre_nom, timbre_description, 
      timbre_prix, pays_nom, categorie_nom, enchere_date_debut, enchere_date_fin, timbre_rate, enchere_id
      FROM timbre INNER JOIN pays ON pays_id = timbre_pays_id
      INNER JOIN categorie ON categorie_id = timbre_categorie_id
      INNER JOIN enchere ON enchere_id = timbre_enchere_id
      INNER JOIN image ON timbre_id = image_timbre_id
      INNER JOIN mise ON enchere_id = mise_enchere_id
      -- INNER JOIN utilisateur ON  enchere_utilisateur_id = utilisateur_id
      -- right join mise ON enchere_id = mise_enchere_id
      WHERE $champs->utilisateur_id = mise_utilisateur_id
      ORDER BY timbre_id DESC limit 1";
      return $this->getLignes();
    }

  public function getPays() {
    $this->sql = "SELECT pays_id, pays_nom from pays ORDER BY pays_id ASC";
    return $this->getLignes();
  }

  public function getCategorie() {
    $this->sql = " SELECT categorie_id, categorie_nom FROM categorie ORDER BY categorie_id ASC";
    return $this->getLignes();
  }

  // echo "<pre>" . print_r($champs, true) . "<pre>"; exit;

  /* ==================== Ajouter un enchere ================= */
  /* ajouterEnchere */
  public function ajouterEnchere($champs) {

  // echo "<pre>" . print_r($champs, true) . "<pre>"; exit;

    $this->sql = 'INSERT INTO enchere SET
    enchere_date_debut      = :enchere_date_debut,
    enchere_date_fin        = :enchere_date_fin,
    enchere_utilisateur_id  = :enchere_utilisateur_id';
    return $this->CUDLigne($champs);
  }

  /* ajouterTimbre */
  public function ajouterTimbre($champs) {
    
    $this->sql = '
      INSERT INTO timbre SET
      timbre_nom              = :timbre_nom,
      timbre_description      = :timbre_description,
      timbre_prix             = :timbre_prix,
      timbre_km               = :timbre_km,
      timbre_anee             = :timbre_anee,
      timbre_rate             = :timbre_rate,
      timbre_categorie_id     = :timbre_categorie_id,
      timbre_pays_id          = :timbre_pays_id,
      timbre_utilisateur_id   = :timbre_utilisateur_id,
      timbre_enchere_id       = :timbre_enchere_id';
      return $this->CUDLigne($champs);
  }
  
  /* ajouterImage */
  public function ajouterImage($champs) {

    $this->sql = '
      INSERT INTO image SET
      image_url         = :image_url,
      image_timbre_id   = :image_timbre_id';
      return $this->CUDLigne($champs);
  }

  /* ==================== Recevoir un enchere ================= */
  /**
   * Récupération d'un film
   * @param int $film_id, clé du film 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getTimbre($enchere_id) {

    // echo "<pre>" . print_r($enchere_id, true) . "<pre>";exit;

    $this->sql = "SELECT  enchere_id, timbre_enchere_id, image_url, image_timbre_id, 
    timbre_id, timbre_nom, timbre_description, pays_nom, categorie_nom, enchere_date_debut,
    enchere_date_fin, mise_prix, timbre_prix, timbre_km, timbre_anee, timbre_rate
    FROM timbre INNER JOIN pays ON pays_id = timbre_pays_id 
    INNER JOIN categorie ON categorie_id = timbre_categorie_id 
    INNER JOIN enchere ON enchere_id = timbre_enchere_id
    INNER JOIN image ON timbre_id = image_timbre_id
    LEFT OUTER JOIN mise ON enchere_id = mise_enchere_id
    WHERE enchere_id = :enchere_id";

// echo "<pre>" . print_r($enchere_id, true) . "<pre>"; exit;
    return $this->getLignes(['enchere_id' => $enchere_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  public function getImage($timbre_id) {
    $this->sql = "SELECT image_id
    from image
    WHERE image_timbre_id = $timbre_id";
    return $this->getLignes();
  }

  public function getEnchere($timbre_enchere_id) {
    
    // echo "<pre>" . print_r($timbre_id, true) . "<pre>"; exit;
    $this->sql = "SELECT enchere_id
    from enchere
    WHERE enchere_id = $timbre_enchere_id";
    return $this->getLignes();
  }

  public function getMise($enchere_id) {
    
    // echo "<pre>" . print_r($timbre_id, true) . "<pre>"; exit;
    $this->sql = "SELECT * from mise
    WHERE mise_enchere_id = $enchere_id";
    return $this->getLignes();
  }

  /* ==================== Suprimer un enchere ================= */
  /* Suprimer Image */
  public function supprimerImage($image_id) {
    // echo "<pre>" . print_r($image_id, true) . "<pre>"; exit;
    $this->sql = '
      DELETE FROM image WHERE image_id = :image_id';
    return $this->CUDLigne(['image_id' => $image_id]);
  }

  /* Suprimer Timbre */
  public function supprimerTimbre($timbre_id) {
    // echo "<pre>" . print_r($image_id, true) . "<pre>"; exit;
    $this->sql = '
      DELETE FROM timbre WHERE timbre_id = :timbre_id';
    return $this->CUDLigne(['timbre_id' => $timbre_id]);
  }

  /* Suprimer Enchere */
  public function supprimerEnchere($enchere_id) {
    // echo "<pre>" . print_r($enchere_id, true) . "<pre>"; exit;
    $this->sql = "
      DELETE FROM enchere WHERE enchere_id = :enchere_id";
      return $this->CUDLigne(['enchere_id' => $enchere_id]);
    }

  /* =========================== MISER ======================= */


  public function getMisePrix($enchere_id) {
    $this->sql = "SELECT mise_prix from mise 
    WHERE mise_enchere_id = $enchere_id
    ORDER BY mise_prix DESC limit 1 ";
    return $this->getLignes();
  }

  
  /* ajouterEnchere */
  public function miser($champs) {

    // echo "<pre>" . print_r($champs, true) . "<pre>"; exit;
    $this->sql = 'INSERT INTO mise SET
    mise_prix            = :mise_prix,
    mise_date            = :mise_date,
    mise_utilisateur_id  = :utilisateur_id,
    mise_enchere_id      = :enchere_id';
    return $this->CUDLigne($champs);
  }


  public function rechercherEnchere($valeurRecherchee) {
    // echo "<pre>" . print_r($valeurRecherchee, true) . "<pre>"; exit;

    $this->sql = "SELECT enchere_id, image_url,
    timbre_id, timbre_nom, timbre_description, timbre_prix,
    pays_nom, categorie_nom, enchere_date_debut, enchere_date_fin
    FROM timbre INNER JOIN pays ON pays_id = timbre_pays_id 
    INNER JOIN categorie ON categorie_id = timbre_categorie_id 
    INNER JOIN enchere ON enchere_id = timbre_enchere_id
    INNER JOIN image ON timbre_id = image_timbre_id
    WHERE timbre_nom LIKE '%$valeurRecherchee%' 
    OR pays_nom LIKE '%$valeurRecherchee%' 
    OR timbre_description LIKE '%$valeurRecherchee%'
    OR timbre_prix LIKE '%$valeurRecherchee%'
    -- OR enchere_date_debut LIKE '%$valeurRecherchee%'
    -- OR enchere_date_fin LIKE '%$valeurRecherchee%'
    -- OR mise_prix LIKE '%$valeurRecherchee%'
    OR categorie_nom LIKE '%$valeurRecherchee%'
    -- where enchere_id = mise_enchere_id)
    ORDER BY timbre_id DESC";
    return $this->getLignes();
  }
  
}