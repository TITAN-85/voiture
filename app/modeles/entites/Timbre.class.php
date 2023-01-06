<?php

/**
 * Classe de l'entité Timbre
 *
 */
class Timbre extends Entite
{
  private $timbre_id;
  private $timbre_nom;
  private $timbre_description;
  private $timbre_prix;
  private $timbre_pays_id;
  private $timbre_categorie_id;
  private $timbre_enchere_id;
  private $timbre_utilisateur_id;

  
  private $erreurs = [];


 /**
   * Mutateur de la propriété film_id 
   * @param int $film_id
   * @return $this
   */    
  public function setTimbre_id($timbre_id) {
    unset($this->erreurs['timbre_id']);
    // TODO:
    $this->timbre_id = $timbre_id;
    return $this;
  }    

  /**
   * Mutateur de la propriété film_titre 
   * @param string $film_titre
   * @return $this
   */    
  public function setTimbre_nom($timbre_nom) {
    unset($this->erreurs['timbre_nom']);
    // TODO:
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
    // TODO:
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
    // TODO:
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
    // TODO:
    $this->timbre_categorie_id = $timbre_categorie_id;
    return $this;
  }    
  /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_enchere_id($timbre_enchere_id) {
    unset($this->erreurs['$timbre_enchere_id']);
    // TODO:
    $this->timbre_enchere_id = $timbre_enchere_id;
    return $this;
  }    
  /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_utilisateur_id($timbre_utilisateur_id) {
    unset($this->erreurs['$timbre_utilisateur_id']);
    // TODO:
    $this->timbre_utilisateur_id = $timbre_utilisateur_id;
    return $this;
  }    

    /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_prix($timbre_prix) {
    unset($this->erreurs['$timbre_prix']);
    // TODO:
    $this->timbre_prix = $timbre_prix;
    return $this;
  }    

  
    /**
   * Mutateur de la propriété film_genre_id 
   * @param int $film_genre_id
   * @return $this
   */    
  public function setTimbre_pays($timbre_pays) {
    unset($this->erreurs['$timbre_pays']);
    // TODO:
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
    // TODO:
    $this->timbre_categorie = $timbre_categorie;
    return $this;
  } 

}