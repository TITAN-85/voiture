<?php

echo "<pre>";
print_r($_POST);
print_r($_FILES);
$nom_fichier = $_FILES['userfile']['name'];
$fichier = $_FILES['userfile']['tmp_name'];
$taille = $_FILES['userfile']['size'];

if(move_uploaded_file($fichier, "assets/jpeg/timbres/".$nom_fichier)){
    echo "ficier copie";
}else{
    echo "fichier non copie";
}
