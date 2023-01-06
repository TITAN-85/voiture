DROP DATABASE voiture;
CREATE database voiture;


CREATE TABLE voiture.profil (
profil_id INT NOT NULL auto_increment primary key, 
profil_nom VARCHAR(45)
);


CREATE TABLE voiture.pays(
pays_id INT NOT NULL auto_increment primary key, 
pays_nom VARCHAR(45)
);


CREATE TABLE voiture.utilisateur (
utilisateur_id INT NOT NULL auto_increment primary key,
utilisateur_nom VARCHAR(45),
utilisateur_prenom VARCHAR(45),
utilisateur_courriel VARCHAR(45),
utilisateur_mdp VARCHAR(255),
utilisateur_profil_id INT not null,
constraint utilisateur_profil_id_fk foreign key (utilisateur_profil_id) references profil (profil_id)
) ENGINE=InnoDB  DEFAULT CHARSET=UTF8;


CREATE TABLE voiture.enchere (
enchere_id INT NOT NULL auto_increment primary key,
enchere_date_debut DATE,
enchere_date_fin DATE,
enchere_utilisateur_id INT not null,
constraint enchere_utilisateur_id_fk foreign key (enchere_utilisateur_id) references utilisateur (utilisateur_id)
);


CREATE TABLE voiture.mise(
mise_id INT NOT NULL auto_increment primary key,
mise_prix DOUBLE,
mise_date DATE NOT NULL,
mise_enchere_id INT not null,
constraint mise_enchere_id_fk foreign key (mise_enchere_id) references enchere (enchere_id),
mise_utilisateur_id INT not null,
constraint mise_utilisateur_id_fk foreign key (mise_utilisateur_id) references utilisateur (utilisateur_id)
);

CREATE TABLE voiture.categorie(
categorie_id INT NOT NULL auto_increment primary key,
categorie_nom VARCHAR(45) NOT NULL
);


CREATE TABLE voiture.timbre(
timbre_id INT NOT NULL auto_increment primary key, 
timbre_nom VARCHAR(45) NOT NULL, 
timbre_description VARCHAR(255), 
timbre_prix DOUBLE NOT NULL,
timbre_km DOUBLE NOT NULL,
timbre_anee DOUBLE NOT NULL,
timbre_rate DOUBLE NOT NULL,
timbre_pays_id INT NOT NULL,
constraint timbre_pays_id_fk foreign key (timbre_pays_id) references pays (pays_id),
timbre_categorie_id INT NOT NULL, 
constraint timbre_categorie_id_fk foreign key (timbre_categorie_id) references categorie (categorie_id),
timbre_enchere_id INT NOT NULL,
constraint timbre_enchere_id_fk foreign key (timbre_enchere_id) references enchere (enchere_id),
timbre_utilisateur_id INT NOT NULL,
constraint timbre_utilisateur_id_fk foreign key (timbre_utilisateur_id) references utilisateur (utilisateur_id)
);

CREATE TABLE voiture.image (
image_id INT NOT NULL auto_increment primary key,
image_url VARCHAR(255),
image_timbre_id INT not null,
constraint image_timbre_id_fk foreign key (image_timbre_id) references timbre (timbre_id)
);

-- ================================= INSERT profil ==========================
INSERT INTO voiture.profil VALUES (null, "administrateur");
INSERT INTO voiture.profil VALUES (null, "membre");

-- ================================= INSERT utilisateur ==========================
INSERT INTO voiture.utilisateur VALUES
(null, "candutatiana@gmail.com",   "Candu Tatiana", "candutatiana@gmail.com",  SHA2("1234", 512), "1"),
(null, "Jouhannet", "Charles", "cjouhannet@cmaisonneuve.qc.ca", SHA2("a1b2c3d4e5", 512), "1"),
(null, "Tremblay",  "Jean",    "jean.tremblay@site1.ca",        SHA2("f1g2h3i4j5", 512), "2"),
(null, "Legrand",   "Jacques", "jacques.legrand@site2.ca",      SHA2("k1l2m3n4o5", 512), "2"),
(null, "alex",   "alex", "alex@alex.ca",      SHA2("1234", 512), "1"),
(null, "alex2",   "alex2", "alex2@alex2.ca",  SHA2("1234", 512), "2");



-- ================================= INSERT pays ==========================
insert into voiture.pays value (null, "Ни хрена");
insert into voiture.pays value (null, "только Автом. багажник");
insert into voiture.pays value (null, "только Автом. Кондиционер");
insert into voiture.pays value (null, "Автома. и багажник и Кондиционер");

-- select * from voiture.pays
-- ================================= INSERT categorie ==========================
insert into voiture.categorie value (null, "Железные диски");
insert into voiture.categorie value (null, "Алюминевые диски");


-- ================================= INSERT enchere ==========================
insert into voiture.enchere value (null, "2022-12-28", "2023-12-28", "1");

-- ================================= INSERT timbre ==========================
insert into voiture.timbre value (null, "2015 Subaru outback", "https://www.facebook.com/marketplace/item/1115804522418438/?hoisted=false&ref=category_feed&referral_code=undefined&referral_story_type=listing&tracking=%7B%22qid%22%3A%22-4986267086444170428%22%2C%22mf_story_key%22%3A%2256", "15995", "135000", "2015", "85", "1", "1", "1", "1");

-- ================================= INSERT images ==========================
insert into voiture.image value (null, "0111.jpg", "1");
