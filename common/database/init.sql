DROP SCHEMA IF EXISTS pls CASCADE;
CREATE SCHEMA IF NOT EXISTS pls;
SET SCHEMA 'pls';

CREATE TABLE _adresse(
    id_adresse SERIAL PRIMARY KEY,
    numero INTEGER,
    complement_numero VARCHAR(20),
    rue_adresse VARCHAR(200) NOT NULL,
    complement_adresse VARCHAR(200),
    ville_adresse VARCHAR(200) NOT NULL,
    code_postal_adresse VARCHAR(15) NOT NULL,
    pays_adresse VARCHAR(100) NOT NULL
);

CREATE TABLE _compte (
    id_compte VARCHAR(20) PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    photo_profil VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    id_adresse INTEGER NOT NULL, 
    telephone VARCHAR(20) NOT NULL,
    mail VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    civilite VARCHAR(3) NOT NULL,
    CONSTRAINT compte_fk_adresse FOREIGN KEY(id_adresse) 
            REFERENCES _adresse(id_adresse)
);

CREATE TABLE _locataire (
    id_locataire VARCHAR(20) PRIMARY KEY,
    CONSTRAINT locataire_fk_compte FOREIGN KEY(id_locataire) 
            REFERENCES _compte(id_compte)
);

CREATE TABLE _token (
    cle_api VARCHAR(100) PRIMARY KEY
);

CREATE TABLE _proprietaire (
    id_proprietaire VARCHAR(20) PRIMARY KEY,
    piece_identite VARCHAR(100) NOT NULL,
    note_proprietaire INTEGER DEFAULT 0,
    num_carte_identite VARCHAR(20) NOT NULL,
    rib_proprietaire VARCHAR(40) NOT NULL,
    date_identite DATE NOT NULL,
    cle_api VARCHAR(100),
    CONSTRAINT proprietaire_fk_compte FOREIGN KEY(id_proprietaire)
            REFERENCES _compte(id_compte),
    CONSTRAINT proprietaire_fk_token FOREIGN KEY(cle_api)
            REFERENCES _token(cle_api)
);

CREATE TABLE _langue (
    id_proprietaire VARCHAR(20),
    nom_langue VARCHAR(50),
    CONSTRAINT langue_pk PRIMARY KEY(id_proprietaire,nom_langue),
    CONSTRAINT langue_fk_proprietaire FOREIGN KEY(id_proprietaire) 
            REFERENCES _proprietaire(id_proprietaire)
);

CREATE TABLE _logement (
    id_logement	SERIAL PRIMARY KEY,
    id_proprietaire VARCHAR(20) NOT NULL,
    id_adresse INTEGER NOT NULL,
    titre_logement VARCHAR(120) NOT NULL,
    photo_logement VARCHAR(100),
    accroche_logement VARCHAR(400) NOT NULL,
    description_logement VARCHAR(400) NOT NULL,
    gps_longitude_logement VARCHAR(12) NOT NULL,
    gps_latitude_logement VARCHAR(12) NOT NULL,
    categorie_logement  VARCHAR(50) NOT NULL,
    surface_logement INTEGER NOT NULL ,
    max_personne_logement INTEGER NOT NULL,
    nb_lits_simples_logement INTEGER DEFAULT 0,
    nb_lits_doubles_logement INTEGER DEFAULT 0,
    prix_ht_logement FLOAT NOT NULL,
    prix_ttc_logement FLOAT NOT NULL, 
    est_visible	BOOLEAN DEFAULT FALSE,
    duree_minimale_reservation INTEGER NOT NULL,
    delais_minimum_reservation INTEGER NOT NULL,
    delais_prevenance INTEGER NOT NULL,
    classe_energetique CHAR,
    type_logement VARCHAR(50) NOT NULL,
    CONSTRAINT logement_fk_proprietaire FOREIGN KEY(id_proprietaire) 
            REFERENCES _proprietaire(id_proprietaire),
    CONSTRAINT logement_fk_adresse FOREIGN KEY(id_adresse)
            REFERENCES _adresse(id_adresse)
);

CREATE TABLE _icalator (
    cle_api VARCHAR(100) PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,

    CONSTRAINT icalator_fk_token FOREIGN KEY(cle_api) 
            REFERENCES _token(cle_api)
);

CREATE TABLE _icalator_logement (
    cle_api VARCHAR(100) PRIMARY KEY,
    id_logement INTEGER NOT NULL,

    CONSTRAINT icalator_logement_fk_icalator FOREIGN KEY(cle_api) 
            REFERENCES _icalator(cle_api),
    CONSTRAINT icalator_logement_fk_logement FOREIGN KEY(id_logement)
            REFERENCES _logement(id_logement)
);

/* TRIGGER POUR CALCULER PRIX TTC */

CREATE TABLE _activite(
    nom_activite VARCHAR(50) PRIMARY KEY
);

CREATE TABLE _logement_activite(
    id_activite SERIAL NOT NULL,
    nom_activite VARCHAR(50),
    id_logement INTEGER,
    perimetre_activite VARCHAR(30),
    CONSTRAINT _logement_activite_pk PRIMARY KEY(id_activite),
    CONSTRAINT _logement_activite_fk_activite FOREIGN KEY(nom_activite) 
            REFERENCES _activite(nom_activite),
    CONSTRAINT _logement_activite_fk_logement FOREIGN KEY(id_logement) 
            REFERENCES _logement(id_logement)
);

CREATE TABLE _logement_amenagement(
    id_amenagement SERIAL NOT NULL,
    nom_amenagement VARCHAR(50),
    id_logement INTEGER,
    CONSTRAINT _logement_amenagement_pk PRIMARY KEY(nom_amenagement,id_logement),
    CONSTRAINT _logement_amenagement_fk_logement FOREIGN KEY(id_logement) 
            REFERENCES _logement(id_logement)
);

CREATE TABLE  _reservation (
    id_reservation SERIAL PRIMARY KEY,
    id_locataire VARCHAR(20) NOT NULL,
    id_logement INTEGER NOT NULL,
    nb_nuit INTEGER NOT NULL,
    date_arrivee DATE NOT NULL,
    date_depart DATE NOT NULL,
    nb_voyageur INTEGER NOT NULL,
    date_reservation DATE NOT NULL,
    frais_de_service FLOAT NOT NULL,
    prix_nuitee_TTC FLOAT NOT NULL,
    prix_total FLOAT NOT NULL,
    est_payee BOOLEAN DEFAULT FALSE,
    est_annulee BOOLEAN DEFAULT FALSE,
    CONSTRAINT reservation_fk_locataire FOREIGN KEY(id_locataire)
            REFERENCES _locataire(id_locataire),
    CONSTRAINT reservation_fk_logement FOREIGN KEY(id_logement) 
            REFERENCES _logement(id_logement)

);

CREATE TABLE _facture (
    id_reservation INTEGER PRIMARY KEY,
    moyen_paiement VARCHAR(30) NOT NULL,
    CONSTRAINT facture_fk_reservation FOREIGN KEY(id_reservation)
        REFERENCES _reservation(id_reservation)
);

CREATE TABLE _avis (
    id_reservation INTEGER PRIMARY KEY,
    avis_reservation VARCHAR(400) NOT NULL,
    note_reservation FLOAT NOT NULL,
    commentaire VARCHAR(400),
    date_avis DATE NOT NULL,
    CONSTRAINT avis_fk_reservation FOREIGN KEY(id_reservation)
        REFERENCES _reservation(id_reservation)
);

CREATE TABLE _notification (
    id_notif SERIAL PRIMARY KEY,
    id_compte VARCHAR(20),
    titre_notif VARCHAR(50) NOT NULL,
    message_notif VARCHAR(75),
    est_lue BOOLEAN DEFAULT FALSE,
    CONSTRAINT notification_fk_compte FOREIGN KEY(id_compte)
            REFERENCES _compte(id_compte)
);

CREATE TABLE _departement (
    num_departement VARCHAR(3) PRIMARY KEY,
    nom_departement VARCHAR(100)
);

CREATE TABLE _commune (
    num_departement VARCHAR(3),
    nom_commune VARCHAR(200),
    code_postal VARCHAR(10),
    CONSTRAINT commune_pk PRIMARY KEY(code_postal,nom_commune),
    CONSTRAINT commune_fk_department FOREIGN KEY(num_departement) 
            REFERENCES _departement(num_departement)
);


CREATE OR REPLACE VIEW proprietaire AS SELECT id_compte,nom,prenom,mot_de_passe,_compte.id_adresse, telephone,mail,date_naissance,civilite,photo_profil,
    piece_identite,note_proprietaire,num_carte_identite,rib_proprietaire,date_identite,numero,complement_numero,rue_adresse,complement_adresse,ville_adresse,code_postal_adresse,pays_adresse,_token.cle_api
    FROM _compte
    INNER JOIN _proprietaire ON id_compte = id_proprietaire
    INNER JOIN _adresse ON _compte.id_adresse = _adresse.id_adresse
    LEFT JOIN _token ON _proprietaire.cle_api = _token.cle_api; 

CREATE OR REPLACE VIEW  locataire AS SELECT
    id_compte,nom,prenom,photo_profil,mot_de_passe,telephone,mail,date_naissance,civilite,_compte.id_adresse,numero,complement_numero,rue_adresse,complement_adresse,ville_adresse,code_postal_adresse,pays_adresse FROM _compte 
    INNER JOIN _locataire ON id_compte = id_locataire
    INNER JOIN _adresse ON _compte.id_adresse = _adresse.id_adresse; 


CREATE EXTENSION IF NOT EXISTS tablefunc;

CREATE OR REPLACE VIEW logement_activite AS
    SELECT
    id_logement,
    MAX(CASE WHEN activite_rank = 1 THEN id_activite ELSE NULL END) AS id_activite_1,
    MAX(CASE WHEN activite_rank = 1 THEN nom_activite ELSE NULL END) AS activite_1,
    MAX(CASE WHEN activite_rank = 1 THEN perimetre_activite ELSE NULL END) AS perimetre_activite_1,
    MAX(CASE WHEN activite_rank = 2 THEN id_activite ELSE NULL END) AS id_activite_2,
    MAX(CASE WHEN activite_rank = 2 THEN nom_activite ELSE NULL END) AS activite_2,
    MAX(CASE WHEN activite_rank = 2 THEN perimetre_activite ELSE NULL END) AS perimetre_activite_2,
    MAX(CASE WHEN activite_rank = 3 THEN id_activite ELSE NULL END) AS id_activite_3,
    MAX(CASE WHEN activite_rank = 3 THEN nom_activite ELSE NULL END) AS activite_3,
    MAX(CASE WHEN activite_rank = 3 THEN perimetre_activite ELSE NULL END) AS perimetre_activite_3,
    MAX(CASE WHEN activite_rank = 4 THEN id_activite ELSE NULL END) AS id_activite_4,
    MAX(CASE WHEN activite_rank = 4 THEN nom_activite ELSE NULL END) AS activite_4,
    MAX(CASE WHEN activite_rank = 4 THEN perimetre_activite ELSE NULL END) AS perimetre_activite_4,
    MAX(CASE WHEN activite_rank = 5 THEN id_activite ELSE NULL END) AS id_activite_5,
    MAX(CASE WHEN activite_rank = 5 THEN nom_activite ELSE NULL END) AS activite_5,
    MAX(CASE WHEN activite_rank = 5 THEN perimetre_activite ELSE NULL END) AS perimetre_activite_5,
    MAX(CASE WHEN activite_rank = 6 THEN id_activite ELSE NULL END) AS id_activite_6,
    MAX(CASE WHEN activite_rank = 6 THEN nom_activite ELSE NULL END) AS activite_6,
    MAX(CASE WHEN activite_rank = 6 THEN perimetre_activite ELSE NULL END) AS perimetre_activite_6,
    MAX(CASE WHEN activite_rank = 7 THEN id_activite ELSE NULL END) AS id_activite_7,
    MAX(CASE WHEN activite_rank = 7 THEN nom_activite ELSE NULL END) AS activite_7,
    MAX(CASE WHEN activite_rank = 7 THEN perimetre_activite ELSE NULL END) AS perimetre_activite_7
FROM (
    SELECT
        la.id_activite,
        la.id_logement,
        la.nom_activite,
        la.perimetre_activite,
        ROW_NUMBER() OVER (PARTITION BY la.id_logement ORDER BY la.nom_activite) AS activite_rank
    FROM
        _logement_activite la
) AS subquery
GROUP BY id_logement;

CREATE OR REPLACE VIEW logement_amenagement AS
    SELECT
        id_logement,
        MAX(CASE WHEN rn = 1 THEN id_amenagement END) AS id_amenagement_1,
        MAX(CASE WHEN rn = 1 THEN nom_amenagement END) AS amenagement_1,
        MAX(CASE WHEN rn = 2 THEN id_amenagement END) AS id_amenagement_2,
        MAX(CASE WHEN rn = 2 THEN nom_amenagement END) AS amenagement_2,
        MAX(CASE WHEN rn = 3 THEN id_amenagement END) AS id_amenagement_3,
        MAX(CASE WHEN rn = 3 THEN nom_amenagement END) AS amenagement_3,
        MAX(CASE WHEN rn = 4 THEN id_amenagement END) AS id_amenagement_4,
        MAX(CASE WHEN rn = 4 THEN nom_amenagement END) AS amenagement_4,
        MAX(CASE WHEN rn = 5 THEN id_amenagement END) AS id_amenagement_5,
        MAX(CASE WHEN rn = 5 THEN nom_amenagement END) AS amenagement_5
    FROM (
        SELECT
            la.id_amenagement,
            la.id_logement,
            la.nom_amenagement,
            ROW_NUMBER() OVER(PARTITION BY la.id_logement ORDER BY la.nom_amenagement) AS rn
        FROM
            _logement_amenagement la
    ) AS subquery
    GROUP BY
        id_logement;

CREATE OR REPLACE VIEW logement AS 
    SELECT _logement.id_logement,_logement.id_proprietaire, _logement.id_adresse, _logement.titre_logement, _logement.photo_logement, _logement.accroche_logement,
        _logement.description_logement, _logement.gps_longitude_logement, _logement.gps_latitude_logement, _logement.categorie_logement,
        _logement.surface_logement, _logement.max_personne_logement, _logement.nb_lits_simples_logement, _logement.nb_lits_doubles_logement,
        _logement.prix_ht_logement, _logement.prix_ttc_logement, _logement.est_visible, _logement.duree_minimale_reservation, _logement.delais_minimum_reservation,
        _logement.delais_prevenance, _logement.classe_energetique, _logement.type_logement, 
        _adresse.numero,_adresse.complement_numero,_adresse.rue_adresse, _adresse.complement_adresse, _adresse.ville_adresse, _adresse.code_postal_adresse, _adresse.pays_adresse,
        logement_activite.activite_1, logement_activite.activite_2, logement_activite.activite_3, logement_activite.activite_4,
        logement_activite.activite_5, logement_activite.activite_6, logement_activite.activite_7,logement_activite.perimetre_activite_1, logement_activite.perimetre_activite_2, 
		logement_activite.perimetre_activite_3, logement_activite.perimetre_activite_4, logement_activite.perimetre_activite_5, logement_activite.perimetre_activite_6,
        logement_activite.id_activite_1, logement_activite.id_activite_2, logement_activite.id_activite_3, logement_activite.id_activite_4, logement_activite.id_activite_5,
        logement_activite.id_activite_6, logement_activite.id_activite_7,logement_activite.perimetre_activite_7, logement_amenagement.amenagement_1,
        logement_amenagement.amenagement_2, logement_amenagement.amenagement_3, logement_amenagement.amenagement_4, logement_amenagement.amenagement_5,
        logement_amenagement.id_amenagement_1, logement_amenagement.id_amenagement_2, logement_amenagement.id_amenagement_3,logement_amenagement.id_amenagement_4,
        logement_amenagement.id_amenagement_5 FROM _logement 
    LEFT JOIN logement_activite ON logement_activite.id_logement = _logement.id_logement
    INNER JOIN _adresse ON _logement.id_adresse = _adresse.id_adresse
    LEFT JOIN logement_amenagement ON logement_amenagement.id_logement = _logement.id_logement;

CREATE OR REPLACE FUNCTION update_locataire() RETURNS TRIGGER AS $BODY$
DECLARE
  compte_id_adresse INTEGER;
BEGIN
  IF NEW.id_compte <> OLD.id_compte OR OLD.id_adresse <> NEW.id_adresse THEN 
    RAISE EXCEPTION 'Impossible de changer le pseudo ou l''id de l''adresse';
  ELSE
    UPDATE _compte 
    SET 
      photo_profil = COALESCE(NEW.photo_profil, OLD.photo_profil),nom = COALESCE(NEW.nom, OLD.nom),prenom = COALESCE(NEW.prenom, OLD.prenom),mot_de_passe = COALESCE(NEW.mot_de_passe, OLD.mot_de_passe),telephone = COALESCE(NEW.telephone, OLD.telephone),mail = COALESCE(NEW.mail, OLD.mail),date_naissance = COALESCE(NEW.date_naissance, OLD.date_naissance),civilite = COALESCE(NEW.civilite, OLD.civilite)
    WHERE id_compte = OLD.id_compte;
    
    SELECT id_adresse INTO compte_id_adresse FROM _compte WHERE id_compte = OLD.id_compte; 
    
    UPDATE _adresse 
    SET 
      numero = COALESCE(NEW.numero, OLD.numero),complement_numero = COALESCE(NEW.complement_numero, OLD.complement_numero),rue_adresse = COALESCE(NEW.rue_adresse, OLD.rue_adresse),complement_adresse = COALESCE(NEW.complement_adresse, OLD.complement_adresse),ville_adresse = COALESCE(NEW.ville_adresse, OLD.ville_adresse),code_postal_adresse = COALESCE(NEW.code_postal_adresse, OLD.code_postal_adresse),pays_adresse = COALESCE(NEW.pays_adresse, OLD.pays_adresse)
    WHERE id_adresse = compte_id_adresse;
  END IF; 
  RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER tg_update_locataire
    INSTEAD OF UPDATE on locataire 
    FOR EACH ROW 
    EXECUTE PROCEDURE update_locataire();
/*
CREATE TRIGGER tg_delete_locataire
    INSTEAD OF DELETE on locataire 
    FOR EACH ROW 
    EXECUTE PROCEDURE delete_locataire();
*/
create or replace function create_locataire() RETURNS TRIGGER AS $BODY$
  DECLARE
    new_id_adresse INTEGER;
  BEGIN
    INSERT INTO _adresse(numero,complement_numero,rue_adresse, complement_adresse, ville_adresse, code_postal_adresse, pays_adresse) 
                VALUES (NEW.numero, NEW.complement_numero,new.rue_adresse,new.complement_adresse,new.ville_adresse,new.code_postal_adresse,new.pays_adresse)
                    RETURNING id_adresse INTO new_id_adresse;
                    
    INSERT INTO _compte(id_compte,nom,prenom,id_adresse,photo_profil,mot_de_passe,telephone,mail,date_naissance,civilite) 
                VALUES (new.id_compte,new.nom,new.prenom,new_id_adresse,new.photo_profil,new.mot_de_passe,new.telephone,new.mail,new.date_naissance,new.civilite);
	  return new;
    INSERT INTO _locataire(id_locataire) 
                VALUES (new.id_compte);
	  return new;
	END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER tg_create_locataire
    INSTEAD OF INSERT on locataire 
    FOR EACH ROW 
    EXECUTE PROCEDURE create_locataire();
    
CREATE OR REPLACE FUNCTION update_proprietaire() RETURNS TRIGGER AS $BODY$
DECLARE
  compte_id_adresse INTEGER;
BEGIN
  IF NEW.id_compte <> OLD.id_compte OR OLD.id_adresse <> NEW.id_adresse THEN 
    RAISE EXCEPTION 'Impossible de changer le pseudo ou l''id de l''adresse';
  ELSE
    UPDATE _compte 
    SET 
      photo_profil = COALESCE(new.photo_profil, old.photo_profil),nom = COALESCE(new.nom, old.nom),prenom = COALESCE(new.prenom, old.prenom),mot_de_passe = COALESCE(new.mot_de_passe, old.mot_de_passe),telephone = COALESCE(new.telephone, old.telephone),mail = COALESCE(new.mail, old.mail),date_naissance = COALESCE(new.date_naissance, old.date_naissance),civilite = COALESCE(new.civilite, old.civilite)
    WHERE id_compte = OLD.id_compte;
    
    SELECT id_adresse INTO compte_id_adresse FROM _compte WHERE id_compte = OLD.id_compte; 
    
    UPDATE _adresse 
    SET 
      numero = COALESCE(NEW.numero, OLD.numero),complement_numero = COALESCE(NEW.complement_numero, OLD.complement_numero),rue_adresse = COALESCE(new.rue_adresse, old.rue_adresse),complement_adresse = COALESCE(new.complement_adresse, old.complement_adresse),ville_adresse = COALESCE(new.ville_adresse, old.ville_adresse),code_postal_adresse = COALESCE(new.code_postal_adresse, old.code_postal_adresse),pays_adresse = COALESCE(new.pays_adresse, old.pays_adresse)
    WHERE id_adresse = compte_id_adresse;

    UPDATE _token 
    SET 
      cle_api = COALESCE(new.cle_api,old.cle_api);

    UPDATE _proprietaire 
    SET 
      piece_identite = COALESCE(new.piece_identite, old.piece_identite),note_proprietaire = COALESCE(new.note_proprietaire, old.note_proprietaire),num_carte_identite = COALESCE(new.num_carte_identite, old.num_carte_identite),rib_proprietaire = COALESCE(new.rib_proprietaire, old.rib_proprietaire),cle_api = COALESCE(new.cle_api,old.cle_api);
    
  END IF; 
  RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER tg_update_proprietaire
    INSTEAD OF UPDATE on proprietaire
    FOR EACH ROW 
    EXECUTE PROCEDURE update_proprietaire();

create or replace function create_proprietaire() RETURNS TRIGGER AS $BODY$
  DECLARE
	new_id_adresse INTEGER;
	new_id VARCHAR(20);
  BEGIN
    INSERT INTO _adresse(numero,complement_numero,rue_adresse, complement_adresse, ville_adresse, code_postal_adresse, pays_adresse) 
                VALUES (NEW.numero, NEW.complement_numero,new.rue_adresse,new.complement_adresse,new.ville_adresse,new.code_postal_adresse,new.pays_adresse)
                    RETURNING id_adresse INTO new_id_adresse;
	INSERT INTO _compte(id_compte,photo_profil,nom,prenom,id_adresse,mot_de_passe,telephone,mail,date_naissance,civilite) 
                VALUES (new.id_compte,new.photo_profil,new.nom,new.prenom,new_id_adresse,new.mot_de_passe,new.telephone,new.mail,new.date_naissance,new.civilite)
                    RETURNING id_compte INTO new_id;
    INSERT INTO _proprietaire(id_proprietaire,piece_identite,note_proprietaire,num_carte_identite,rib_proprietaire,date_identite)
                VALUES (new_id,new.piece_identite,new.note_proprietaire,new.num_carte_identite,new.rib_proprietaire,new.date_identite);
	  return new;
	END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER tg_create_proprietaire
    INSTEAD OF INSERT on proprietaire
    FOR EACH ROW 
    EXECUTE PROCEDURE create_proprietaire();

CREATE OR REPLACE FUNCTION create_logement() RETURNS TRIGGER AS $BODY$
    DECLARE
        prix_ttc FLOAT := NEW.prix_ht_logement * 1.1;
		new_id_adresse INTEGER;
		new_id_logement INTEGER;
    BEGIN
        INSERT INTO _adresse (numero,complement_numero,rue_adresse, complement_adresse, ville_adresse, code_postal_adresse, pays_adresse) 
            VALUES (NEW.numero, NEW.complement_numero,NEW.rue_adresse, NEW.complement_adresse, NEW.ville_adresse, NEW.code_postal_adresse, NEW.pays_adresse)
            RETURNING id_adresse INTO new_id_adresse;
            
        INSERT INTO _logement(id_proprietaire, id_adresse, titre_logement, photo_logement, accroche_logement, description_logement,
        gps_longitude_logement, gps_latitude_logement, categorie_logement, surface_logement, max_personne_logement, nb_lits_simples_logement,
        nb_lits_doubles_logement, prix_ht_logement, prix_ttc_logement, est_visible, duree_minimale_reservation, delais_minimum_reservation,
        delais_prevenance, classe_energetique, type_logement) 
            VALUES (NEW.id_proprietaire, new_id_adresse, NEW.titre_logement, NEW.photo_logement, NEW.accroche_logement, NEW.description_logement,
            NEW.gps_longitude_logement, NEW.gps_latitude_logement, NEW.categorie_logement, NEW.surface_logement, NEW.max_personne_logement,
            NEW.nb_lits_simples_logement, NEW.nb_lits_doubles_logement, NEW.prix_ht_logement,prix_ttc, NEW.est_visible, NEW.duree_minimale_reservation,NEW.delais_minimum_reservation,
            NEW.delais_prevenance, NEW.classe_energetique, NEW.type_logement)RETURNING id_logement INTO new_id_logement;

        IF NEW.amenagement_1 IS NOT NULL THEN
            INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_1,new_id_logement);
        END IF;
        IF NEW.amenagement_2 IS NOT NULL THEN
            INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_2,new_id_logement);
        END IF;
        IF NEW.amenagement_3 IS NOT NULL THEN
            INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_3, new_id_logement);
        END IF;
        IF NEW.amenagement_4 IS NOT NULL THEN
            INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_4,new_id_logement);
        END IF;
        IF NEW.amenagement_5 IS NOT NULL THEN
            INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_5, new_id_logement);
        END IF;
        IF NEW.activite_1 IS NOT NULL THEN
            INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_1, NEW.perimetre_activite_1, new_id_logement);
        END IF;
        IF NEW.activite_2 IS NOT NULL THEN
            INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_2, NEW.perimetre_activite_2,new_id_logement);
        END IF;
        IF NEW.activite_3 IS NOT NULL THEN
            INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_3, NEW.perimetre_activite_3, new_id_logement);
        END IF;
        IF NEW.activite_4 IS NOT NULL THEN
            INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_4, NEW.perimetre_activite_4, new_id_logement);
        END IF;
        IF NEW.activite_5 IS NOT NULL THEN
            INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_5, NEW.perimetre_activite_5,new_id_logement);
        END IF;
        IF NEW.activite_6 IS NOT NULL THEN
            INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_6, NEW.perimetre_activite_6, new_id_logement);
        END IF;
        IF NEW.activite_7 IS NOT NULL THEN
            INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_7, NEW.perimetre_activite_7, new_id_logement);
    	END IF;

        UPDATE _logement SET photo_logement = CONCAT(new_id_logement, '_', NEW.type_logement) WHERE id_logement = new_id_logement;
	RETURN NEW;
	END;
    $BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER tg_create_logement
    INSTEAD OF INSERT ON logement
    FOR EACH ROW
    EXECUTE PROCEDURE create_logement();

CREATE OR REPLACE FUNCTION update_logement() RETURNS TRIGGER AS $BODY$
    DECLARE
        prix_ttc FLOAT := NEW.prix_ht_logement * 1.1;
    BEGIN

        IF NEW.id_logement <> OLD.id_logement OR OLD.id_adresse <> NEW.id_adresse THEN 
            RAISE EXCEPTION 'Impossible de changer l''id du logement ou l''id de l''adresse';
        ELSE
            UPDATE _adresse SET numero = COALESCE(NEW.numero, OLD.numero),complement_numero = COALESCE(NEW.complement_numero, OLD.complement_numero),rue_adresse = COALESCE(NEW.rue_adresse, OLD.rue_adresse),complement_adresse = COALESCE(NEW.complement_adresse, OLD.complement_adresse),ville_adresse = COALESCE(NEW.ville_adresse, OLD.ville_adresse),code_postal_adresse = COALESCE(NEW.code_postal_adresse, OLD.code_postal_adresse),pays_adresse = COALESCE(NEW.pays_adresse, OLD.pays_adresse)
            WHERE id_adresse = NEW.id_adresse;
            
            UPDATE _logement SET id_proprietaire = COALESCE(NEW.id_proprietaire, OLD.id_proprietaire),id_adresse = COALESCE(NEW.id_adresse, OLD.id_adresse),titre_logement = COALESCE(NEW.titre_logement, OLD.titre_logement),photo_logement = COALESCE(NEW.photo_logement, OLD.photo_logement),accroche_logement = COALESCE(NEW.accroche_logement, OLD.accroche_logement),description_logement = COALESCE(NEW.description_logement, OLD.description_logement),gps_longitude_logement = COALESCE(NEW.gps_longitude_logement, OLD.gps_longitude_logement),gps_latitude_logement = COALESCE(NEW.gps_latitude_logement, OLD.gps_latitude_logement),categorie_logement = COALESCE(NEW.categorie_logement, OLD.categorie_logement),surface_logement = COALESCE(NEW.surface_logement, OLD.surface_logement),max_personne_logement = COALESCE(NEW.max_personne_logement, OLD.max_personne_logement),nb_lits_simples_logement = COALESCE(NEW.nb_lits_simples_logement, OLD.nb_lits_simples_logement),nb_lits_doubles_logement = COALESCE(NEW.nb_lits_doubles_logement, OLD.nb_lits_doubles_logement),prix_ht_logement = COALESCE(NEW.prix_ht_logement, OLD.prix_ht_logement),prix_ttc_logement = COALESCE(NEW.prix_ttc_logement, OLD.prix_ttc_logement),est_visible = COALESCE(NEW.est_visible, OLD.est_visible),duree_minimale_reservation = COALESCE(NEW.duree_minimale_reservation, OLD.duree_minimale_reservation),delais_minimum_reservation = COALESCE(NEW.delais_minimum_reservation, OLD.delais_minimum_reservation),delais_prevenance = COALESCE(NEW.delais_prevenance, OLD.delais_prevenance),classe_energetique = COALESCE(NEW.classe_energetique, OLD.classe_energetique),type_logement = COALESCE(NEW.type_logement, OLD.type_logement)
            WHERE id_logement = OLD.id_logement;
            
            IF NEW.activite_1 IS NOT NULL AND OLD.activite_1 IS NULL THEN
                INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_1, NEW.perimetre_activite_1, NEW.id_logement);
            ELSIF NEW.activite_1 IS NOT NULL AND OLD.activite_1 IS NOT NULL THEN
                UPDATE _logement_activite SET nom_activite = COALESCE(NEW.activite_1, OLD.activite_1), perimetre_activite = COALESCE(NEW.perimetre_activite_1, OLD.perimetre_activite_1) WHERE id_activite = NEW.id_activite_1;
            END IF; 

            IF NEW.activite_2 IS NOT NULL AND OLD.activite_2 IS NULL THEN
                INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_2, NEW.perimetre_activite_2, NEW.id_logement);
            ELSIF NEW.activite_2 IS NOT NULL AND OLD.activite_2 IS NOT NULL THEN
                UPDATE _logement_activite SET nom_activite = COALESCE(NEW.activite_2, OLD.activite_2), perimetre_activite = COALESCE(NEW.perimetre_activite_2, OLD.perimetre_activite_2) WHERE id_activite = NEW.id_activite_2;
            END IF;

            IF NEW.activite_3 IS NOT NULL AND OLD.activite_3 IS NULL THEN
                INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_3, NEW.perimetre_activite_3, NEW.id_logement);
            ELSIF NEW.activite_3 IS NOT NULL AND OLD.activite_3 IS NOT NULL THEN
                UPDATE _logement_activite SET nom_activite = COALESCE(NEW.activite_3, OLD.activite_3), perimetre_activite = COALESCE(NEW.perimetre_activite_3, OLD.perimetre_activite_3) WHERE id_activite = NEW.id_activite_3;
            END IF;

            IF NEW.activite_4 IS NOT NULL AND OLD.activite_4 IS NULL THEN
                INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_4, NEW.perimetre_activite_4, NEW.id_logement);
            ELSIF NEW.activite_4 IS NOT NULL AND OLD.activite_4 IS NOT NULL THEN
                UPDATE _logement_activite SET nom_activite = COALESCE(NEW.activite_4, OLD.activite_4), perimetre_activite = COALESCE(NEW.perimetre_activite_4, OLD.perimetre_activite_4) WHERE id_activite = NEW.id_activite_4;
            END IF;

            IF NEW.activite_5 IS NOT NULL AND OLD.activite_5 IS NULL THEN
                INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_5, NEW.perimetre_activite_5, NEW.id_logement);
            ELSIF NEW.activite_5 IS NOT NULL AND OLD.activite_5 IS NOT NULL THEN
                UPDATE _logement_activite SET nom_activite = COALESCE(NEW.activite_5, OLD.activite_5), perimetre_activite = COALESCE(NEW.perimetre_activite_5, OLD.perimetre_activite_5) WHERE id_activite = NEW.id_activite_5;
            END IF;

            IF NEW.activite_6 IS NOT NULL AND OLD.activite_6 IS NULL THEN
                INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_6, NEW.perimetre_activite_6, NEW.id_logement);
            ELSIF NEW.activite_6 IS NOT NULL AND OLD.activite_6 IS NOT NULL THEN
                UPDATE _logement_activite SET nom_activite = COALESCE(NEW.activite_6, OLD.activite_6), perimetre_activite = COALESCE(NEW.perimetre_activite_6, OLD.perimetre_activite_6) WHERE id_activite = NEW.id_activite_6;
            END IF;

            IF NEW.activite_7 IS NOT NULL AND OLD.activite_7 IS NULL THEN
                INSERT INTO _logement_activite (nom_activite, perimetre_activite, id_logement) VALUES (NEW.activite_7, NEW.perimetre_activite_7, NEW.id_logement);
            ELSIF NEW.activite_7 IS NOT NULL AND OLD.activite_7 IS NOT NULL THEN
                UPDATE _logement_activite SET nom_activite = COALESCE(NEW.activite_7, OLD.activite_7), perimetre_activite = COALESCE(NEW.perimetre_activite_7, OLD.perimetre_activite_7) WHERE id_activite = NEW.id_activite_7;
            END IF;
            
            IF NEW.amenagement_1 IS NOT NULL AND OLD.amenagement_1 IS NULL THEN
                INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_1, NEW.id_logement);
            ELSIF NEW.amenagement_1 IS NOT NULL AND OLD.amenagement_1 IS NOT NULL THEN
                UPDATE _logement_amenagement SET nom_amenagement = COALESCE(NEW.amenagement_1, OLD.amenagement_1) WHERE id_amenagement = NEW.id_amenagement_1;
            END IF;

            IF NEW.amenagement_2 IS NOT NULL AND OLD.amenagement_2 IS NULL THEN
                INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_2, NEW.id_logement);
            ELSIF NEW.amenagement_2 IS NOT NULL AND OLD.amenagement_2 IS NOT NULL THEN
                UPDATE _logement_amenagement SET nom_amenagement = COALESCE(NEW.amenagement_2, OLD.amenagement_2) WHERE id_amenagement = NEW.id_amenagement_2;
            END IF;

            IF NEW.amenagement_3 IS NOT NULL AND OLD.amenagement_3 IS NULL THEN
                INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_3, NEW.id_logement);
            ELSIF NEW.amenagement_3 IS NOT NULL AND OLD.amenagement_3 IS NOT NULL THEN
                UPDATE _logement_amenagement SET nom_amenagement = COALESCE(NEW.amenagement_3, OLD.amenagement_3) WHERE id_amenagement = NEW.id_amenagement_3;
            END IF;

            IF NEW.amenagement_4 IS NOT NULL AND OLD.amenagement_4 IS NULL THEN
                INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_4, NEW.id_logement);
            ELSIF NEW.amenagement_4 IS NOT NULL AND OLD.amenagement_4 IS NOT NULL THEN
                UPDATE _logement_amenagement SET nom_amenagement = COALESCE(NEW.amenagement_4, OLD.amenagement_4) WHERE id_amenagement = NEW.id_amenagement_4;
            END IF;

            IF NEW.amenagement_5 IS NOT NULL AND OLD.amenagement_5 IS NULL THEN
                INSERT INTO _logement_amenagement (nom_amenagement, id_logement) VALUES (NEW.amenagement_5, NEW.id_logement);
            ELSIF NEW.amenagement_5 IS NOT NULL AND OLD.amenagement_5 IS NOT NULL THEN
                UPDATE _logement_amenagement SET nom_amenagement = COALESCE(NEW.amenagement_5, OLD.amenagement_5) WHERE id_amenagement = NEW.id_amenagement_5;
            END IF;

            IF NEW.photo_logement IS NOT NULL AND OLD.photo_logement IS NULL THEN
                UPDATE _logement SET photo_logement = CONCAT(OLD.id_logement, '_', COALESCE(NEW.type_logement, OLD.type_logement)) WHERE id_logement = OLD.id_logement;
            END IF;
        END IF;
	RETURN NEW;
	END;
    $BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER tg_update_logement
    INSTEAD OF UPDATE ON logement
    FOR EACH ROW
    EXECUTE PROCEDURE update_logement();

COPY _adresse(numero, complement_numero, rue_adresse, complement_adresse, ville_adresse, code_postal_adresse, pays_adresse) 
FROM '/docker-entrypoint-initdb.d/adresse.csv'
DELIMITER ','
CSV HEADER; 

COPY _compte(id_compte, nom, prenom, id_adresse, mot_de_passe, telephone, mail, photo_profil, date_naissance, civilite)
FROM '/docker-entrypoint-initdb.d/compte.csv'
DELIMITER ','
CSV HEADER;

COPY _locataire(id_locataire)
FROM '/docker-entrypoint-initdb.d/locataire.csv'
DELIMITER ','
CSV HEADER;

COPY _proprietaire(id_proprietaire, piece_identite, note_proprietaire, num_carte_identite, date_identite, rib_proprietaire)
FROM '/docker-entrypoint-initdb.d/proprio.csv'
DELIMITER ','
CSV HEADER;

COPY _logement(id_proprietaire, id_adresse, titre_logement, photo_logement, accroche_logement, description_logement, gps_longitude_logement, gps_latitude_logement, categorie_logement, type_logement, surface_logement, max_personne_logement, nb_lits_simples_logement, nb_lits_doubles_logement, prix_ht_logement, prix_ttc_logement, est_visible, duree_minimale_reservation,delais_minimum_reservation, delais_prevenance, classe_energetique)
FROM '/docker-entrypoint-initdb.d/logement.csv'
DELIMITER ','
CSV HEADER;

COPY _activite(nom_activite)
FROM '/docker-entrypoint-initdb.d/activite.csv'
DELIMITER ','
CSV HEADER;

COPY _logement_activite(nom_activite, perimetre_activite, id_logement)
FROM '/docker-entrypoint-initdb.d/logement_activite.csv'
DELIMITER ','
CSV HEADER;

COPY _langue(id_proprietaire, nom_langue)
FROM '/docker-entrypoint-initdb.d/langues.csv'
DELIMITER ','
CSV HEADER;

COPY _logement_amenagement(id_logement, nom_amenagement)
FROM '/docker-entrypoint-initdb.d/amenagement.csv'
DELIMITER ','
CSV HEADER;

COPY _reservation(id_reservation,id_locataire,id_logement,nb_nuit,date_arrivee,date_depart,nb_voyageur,date_reservation,frais_de_service,prix_nuitee_ttc,est_payee,est_annulee,prix_total)
FROM '/docker-entrypoint-initdb.d/reservation.csv'
DELIMITER ','
CSV HEADER;

COPY _departement(num_departement, nom_departement)
FROM '/docker-entrypoint-initdb.d/departement.csv'
DELIMITER ','
CSV HEADER;

COPY _commune(nom_commune, num_departement, code_postal)
FROM '/docker-entrypoint-initdb.d/commune.csv'
DELIMITER ','
CSV HEADER;
