<?php


/**
 * Classe Contrôleur des requêtes de l'interface Enchere
 * 
 */

class MiseTimbre extends Routeur
{

    protected $utilisateur_id;
    protected $enchere_id;
    protected $oUtilConn;
    protected $mise;
    protected $mise_date;
    protected $mise_prix_actuel;
    protected $timbreNom;
    protected $messageMise;
    protected $max_Mise_prix;
    protected $mise_prix_valeur;

    /**
     * Constructeur qui initialise des propriétés à partir du query string
     * et la propriété oRequetesSQL déclarée dans la classe Routeur
     * 
     */
    public function __construct()
    {
        $this->mise = $_POST ?? null;
        $this->oRequetesSQL = new RequetesSQL;
        date_default_timezone_set('America/Toronto');
        
        $this->utilisateur_id = $_SESSION['oUtilConn']->utilisateur_id;
        $this->oUtilConn = $_SESSION['oUtilConn'];
    }

    /**
     *  MISER
     */
    public function mise() {
        
        $this->enchere_id = $this->mise["enchere_id"];
        $retour = $this->oRequetesSQL->getMisePrix($this->enchere_id);

        $this->mise_prix_actuel = $this->mise["timbre_prix"];
        $this->mise_prix_valeur = $this->mise["timbre_prix_valeur"];

        if ($retour) {
            $this->max_mise_prix = $retour[0]["mise_prix"];
            } else {
            $this->max_mise_prix = $this->mise_prix_actuel;
        }

        if (count($_POST) !== 0) {
            $dateCourent = date('Y-m-d');
            $this->enchere_id = $this->mise["enchere_id"];
            $this->mise_prix_actuel = $this->mise["timbre_prix"];
            $this->timbreNom = $this->mise["timbre_nom"];

            if ($this->mise_prix_valeur > $this->mise_prix_actuel && $this->mise_prix_valeur > $this->max_mise_prix) {

                // echo "<pre>" . print_r($this->max_mise_prix, true) . "<pre>"; exit;
                $retour = $this->oRequetesSQL->miser([
                    'mise_prix'             => $this->mise_prix_valeur,
                    'mise_date'             => $dateCourent,
                    'utilisateur_id'        => $this->utilisateur_id,
                    'enchere_id'            => $this->enchere_id
                ]);

                $this->messageMise =  'La mise de '.$this->mise_prix_valeur.'.00 CAD de timbre : "'.$this->timbreNom. '" a été effectué'; 
                $this->AccueilView($this->messageMise);

            } else {

                $this->messageMise = 'Vous ne pouvez pas miser moins de: '. $this->max_mise_prix .'.00 CAD';
                $this->AccueilView($this->messageMise);
            }
        }
    }

    // TODO: TO DELETE
    /**
     *  Vue pour AFFICHER ACCUEIL
     */ 
    public function membreAccueilView() {
        $timbres = $this->oRequetesSQL->getTimbresMembre($this->oUtilConn);
        (new Vue)->generer(
            'vListeAccueil',
            [
            'messageMise'         => 'Timbre mise "'.$this->timbreNom. '" a ete effectue',
            'oUtilConn'           => $this->utilisateur_id,
            'timbres'             => $timbres,

            ],
            'gabarit-frontend'
        );
    }

    /**
     *  Vue pour AFFICHER ACCUEIL
     */
    public function AccueilView($messageMise) {
        $dateCourent = date('Y-m-d');
        $timbres = $this->oRequetesSQL->getTimbres();

        (new Vue)->generer(
            'vListeAccueil',
            [
            'messageMise'         => $messageMise,
            'oUtilConn'           => $this->utilisateur_id,
            'timbres'             => $timbres,
            'dateCourent'         => $dateCourent
            ],
            'gabarit-frontend'
        );
    }
}

