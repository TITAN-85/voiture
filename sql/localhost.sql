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
constraint utilisateur_profil_id_fk ,
foreign key (utilisateur_profil_id) ,
references voiture.profil (profil_id)
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
insert into voiture.pays value (null, "basic");
insert into voiture.pays value (null, "auto-trunk");
insert into voiture.pays value (null, "auto-air cond.");
insert into voiture.pays value (null, "auto-air.c.+ trunk");

-- update voiture.pays set pays_nom = "базовая" where pays_id = 1;
-- update voiture.pays set pays_nom = "Багажник" where pays_id = 2;
-- update voiture.pays set pays_nom = "Кондиционер" where pays_id = 3;
-- update voiture.pays set pays_nom = "Баг. + Конд." where pays_id = 4;

-- select * from voiture.pays
-- ================================= INSERT categorie ==========================
insert into voiture.categorie value (null, "Rims");
insert into voiture.categorie value (null, "Mags");

-- update voiture.categorie set categorie_nom = "Железные" where categorie_id = 1;
-- update voiture.categorie set categorie_nom = "Алюминевые" where categorie_id = 2;
-- select * from voiture.categorie

-- ================================= INSERT enchere ==========================
insert into voiture.enchere value (null, "2022-12-28", "2023-12-28", "1");
insert into voiture.enchere value (null, "2022-12-28", "2023-12-28", "1");
insert into voiture.enchere value (null, "2022-12-28", "2023-12-28", "1");
insert into voiture.enchere value (null, "2022-12-28", "2023-12-28", "1");

-- ================================= INSERT timbre ==========================
insert into voiture.timbre value (null, "2015 Subaru outback", "google.com", "16995", "135000", "2015", "85", "2", "1", "1", "1");
insert into voiture.timbre value (null, "2016 Subaru outback", "google.com", "17995", "165000", "2016", "110", "3", "2", "2", "1");
insert into voiture.timbre value (null, "2017 Subaru outback", "google.com", "18995", "115000", "2017", "135", "2", "2", "3", "1");
insert into voiture.timbre value (null, "2018 Subaru outback", "google.com", "19995", "185000", "2018", "155", "1", "1", "4", "1");

-- update dbs9660527.timbr set timbre_enchere_id = 4 where timbre_id = 5;


-- ================================= INSERT images ==========================
insert into voiture.image value (null, "0111.jpg", "1");
insert into voiture.image value (null, "0222.jpg", "1");
insert into voiture.image value (null, "0333.jpg", "1");
insert into voiture.image value (null, "0444.jpg", "1");
