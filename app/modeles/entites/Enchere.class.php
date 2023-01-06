<?php

/**
 * Classe de l'entité enchere
 *
 */
class Enchere extends Entite
{
  protected $enchere_date_debut;
  protected $enchere_date_fin;
  protected $timbre_nom;
  protected $timbre_prix;
  protected $timbre_km;
  protected $timbre_anee;
  protected $timbre_rate;
  protected $timbre_description;
  protected $timbre_pays;
  protected $timbre_categorie;

  protected $erreurs = [];

  public function getEnchere_date_debut()       { return $this->enchere_date_debut; }
  public function getEnchere_date_fin()       { return $this->enchere_date_fin; }
  public function getTimbre_nom()               { return $this->timbre_nom; }
  public function getTimbre_prix()               { return $this->timbre_prix; }
  public function getTimbre_km()               { return $this->timbre_km; }
  public function getTimbre_anee()               { return $this->timbre_anee; }
  public function getTimbre_rate()               { return $this->timbre_rate; }
  public function getTimbre_description()       { return $this->timbre_description; }
  public function getTimbre_pays()              { return $this->timbre_pays; }
  public function getTimbre_categorie()         { return $this->timbre_categorie; }
  public function getErreurs()                  { return $this->erreurs; }
  
  /**
   * Mutateur de la propriété film_titre 
   * @param string $film_titre
   * @return $this
   */    
  public function setEnchere_date_debut($enchere_date_debut) {
    unset($this->erreurs['enchere_date_debut']);
    $this->enchere_date_debut = $enchere_date_debut;
    return $this;
  }

  /**
   * Mutateur de la propriété film_titre 
   * @param string $film_titre
   * @return $this
   */    
  public function setTimbre_nom($timbre_nom) {
    unset($this->erreurs['timbre_nom']);
    $timbre_nom = trim($timbre_nom);
    // $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
    // if (!preg_match($regExp, $timbre_nom)) {
    //   $this->erreurs['timbre_nom'] = 'Au moins 2 caractères alphabétiques';
    // }
    $this->timbre_nom = $timbre_nom;
    return $this;
  }

  /**
   * Mutateur de la propriété film_duree 
   * @param int $film_duree, en minutes
   * @return $this
   */        
  public function setTimbre_description($timbre_description) {
    unset($this->erreurs['timbre_description']);
    $timbre_description = trim($timbre_description);
    // $regExp = '/^\S+(\s+\S+){4,}$/';
    // if (!preg_match($regExp, $timbre_description)) {
    //   $this->erreurs['timbre_description'] = 'Au moins 5 Motts';
    // }
    $this->timbre_description = $timbre_description;
    return $this;
  }


  /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_pays_id($timbre_pays_id) {
    unset($this->erreurs['timbre_pays_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_pays_id)) {
      $this->erreurs['timbre_pays_id'] = 'Numéro de timbre incorrect.';
    }
    $this->timbre_pays_id = $timbre_pays_id;
    return $this;
  }    
  /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_categorie_id($timbre_categorie_id) {
    unset($this->erreurs['$timbre_categorie_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_categorie_id)) {
      $this->erreurs['timbre_categorie_id'] = 'Numéro de timbre incorrect.';
    }
    $this->timbre_categorie_id = $timbre_categorie_id;
    return $this;
  }    



      /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_prix($timbre_prix) {
    unset($this->erreurs['$timbre_prix']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_prix)) {
      $this->erreurs['timbre_prix'] = 'timbre_prix peux pas etre 0 ou negatif';
    }
    $this->timbre_prix = $timbre_prix;
    return $this;
  }    
      /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_km($timbre_km) {
    unset($this->erreurs['$timbre_km']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_km)) {
      $this->erreurs['timbre_km'] = 'timbre_km peux pas etre 0 ou negatif';
    }
    $this->timbre_km = $timbre_km;
    return $this;
  }    
      /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_anee($timbre_anee) {
    unset($this->erreurs['$timbre_anee']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_anee)) {
      $this->erreurs['timbre_anee'] = 'timbre_anee peux pas etre 0 ou negatif';
    }
    $this->timbre_anee = $timbre_anee;
    return $this;
  }    
      /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_rate($timbre_rate) {
    unset($this->erreurs['$timbre_rate']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_rate)) {
      $this->erreurs['timbre_rate'] = 'timbre_rate peux pas etre 0 ou negatif';
    }
    $this->timbre_rate = $timbre_rate;
    return $this;
  }    

    
    /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_pays($timbre_pays) {
    unset($this->erreurs['$timbre_pays']);
    if ($timbre_pays <= 0 ) {
      $this->erreurs['timbre_pays'] = 'cVoyez choisir une ville';
    }
    $this->timbre_pays = $timbre_pays;
    return $this;
  } 

      /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_categorie($timbre_categorie) {
    unset($this->erreurs['$timbre_categorie']);
    if ($timbre_categorie <= 0 ) {
      $this->erreurs['timbre_categorie'] = 'cVoyez choisir une ville';
    }
    $this->timbre_categorie = $timbre_categorie;
    return $this;
  } 

}