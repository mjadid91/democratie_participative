CREATE TABLE utilisateur
(
    loginUtilisateur VARCHAR(50),
    nomUtilisateur VARCHAR(50),
    prenomUtilisateur VARCHAR(50),
    emailUtilisateur VARCHAR(50),
    mdpUtilisateur VARCHAR(50),
    adresseUtilisateur VARCHAR(50),
    villeUtilisateur VARCHAR(50),
    cpUtilisateur VARCHAR(50),
    PRIMARY KEY(loginUtilisateur)
);

CREATE TABLE groupe
(
    IDGroupe INT AUTO_INCREMENT,
    nomGroupe VARCHAR(50),
    descriptionGroupe VARCHAR(255),
    dateCreationGroupe DATE,
    couleurGroupe VARCHAR(50),
    imageGroupe VARCHAR(255),
    PRIMARY KEY(IDGroupe)
);

CREATE TABLE budget (
    IDBudget INT AUTO_INCREMENT,
    montantTotalBudget DECIMAL(15,2),
    montantUtiliseeBudget DECIMAL(15,2),
    IDGroupe INT NOT NULL,
    PRIMARY KEY (IDBudget),
    FOREIGN KEY (IDGroupe) REFERENCES groupe (IDGroupe) ON DELETE CASCADE
);

CREATE TABLE proposition
(
    IDProposition INT AUTO_INCREMENT,
    etatProposition VARCHAR(50),
    titreProposition VARCHAR(50),
    descriptionProposition VARCHAR(255),
    dateSoumissionProposition DATETIME,
    dateFinProposition DATETIME,
    montantProposition DECIMAL(15,2),
    loginUtilisateur VARCHAR(50) NOT NULL,
    IDGroupe INT NOT NULL,
    PRIMARY KEY(IDProposition),
    FOREIGN KEY(loginUtilisateur) REFERENCES utilisateur (loginUtilisateur)
);

CREATE TABLE decisionbudgetaire (
    IDDecision INT AUTO_INCREMENT,
    IDProposition INT NOT NULL,
    montantAlloue DECIMAL(15,2),
    dateDecision DATE,
    loginUtilisateur VARCHAR(50) NOT NULL,
    PRIMARY KEY(IDDecision),
    FOREIGN KEY(IDProposition) REFERENCES proposition (IDProposition),
    FOREIGN KEY(loginUtilisateur) REFERENCES utilisateur (loginUtilisateur)
);

CREATE TABLE vote (
    IDVote INT AUTO_INCREMENT,
    IDProposition INT NOT NULL,
    typeVote varchar(50),
    dateDebutVote DATETIME,
    dateFinVote DATETIME,
    majoriteVote enum('Pour', 'Contre', 'En cours'),   
    PRIMARY KEY (IDVote),
    FOREIGN KEY (IDProposition) REFERENCES proposition (IDProposition)
);


CREATE TABLE role
(
    IDRole INT AUTO_INCREMENT,
    nomRole VARCHAR(50),
    PRIMARY KEY(IDRole)
);

CREATE TABLE commentaire
(
    IDCommentaire INT AUTO_INCREMENT,
    texteCommentaire VARCHAR(255),
    dateCommentaire DATETIME,
    PRIMARY KEY(IDCommentaire)
);

CREATE TABLE reaction(
    IDReaction INT AUTO_INCREMENT,
    typeReaction VARCHAR(50),
    PRIMARY KEY(IDReaction)
);

CREATE TABLE signalement(
    IDSignalement INT AUTO_INCREMENT,
    motifSignalement VARCHAR(255),
    estSignalee BOOLEAN,
    dateSignalement DATETIME,
    IDCommentaire INT NOT NULL,
    loginUtilisateur VARCHAR(50) NOT NULL,
    PRIMARY KEY(IDSignalement),
    FOREIGN KEY(IDCommentaire) REFERENCES commentaire (IDCommentaire),
    FOREIGN KEY(loginUtilisateur) REFERENCES utilisateur (loginUtilisateur)
);

CREATE TABLE role_dans_groupe(
    loginUtilisateur VARCHAR(50),
    IDGroupe INT,
    IDRole INT,
    PRIMARY KEY(loginUtilisateur, IDGroupe, IDRole),
    FOREIGN KEY(loginUtilisateur) REFERENCES utilisateur(loginUtilisateur),
    FOREIGN KEY(IDGroupe) REFERENCES groupe (IDGroupe),
    FOREIGN KEY(IDRole) REFERENCES role (IDRole)
);

CREATE TABLE vote_utilisateur (
    loginUtilisateur VARCHAR(50),
    IDProposition INT,
    sensVote ENUM('Pour', 'Contre'),
    PRIMARY KEY(loginUtilisateur, IDProposition),
    FOREIGN KEY(loginUtilisateur) REFERENCES utilisateur (loginUtilisateur),
    FOREIGN KEY(IDProposition) REFERENCES proposition (IDProposition)
);

CREATE TABLE notifie (
    loginUtilisateur VARCHAR(50),
    IDSignalement INT,
    texteNotification VARCHAR(255),
    dateNotification DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(loginUtilisateur, IDSignalement),
    FOREIGN KEY(loginUtilisateur) REFERENCES utilisateur (loginUtilisateur),
    FOREIGN KEY(IDSignalement) REFERENCES signalement(IDSignalement)
);

CREATE TABLE commentaire_utilisateur(
    loginUtilisateur VARCHAR(50),
    IDCommentaire INT,
    PRIMARY KEY(loginUtilisateur, IDCommentaire),
    FOREIGN KEY(loginUtilisateur) REFERENCES utilisateur (loginUtilisateur),
    FOREIGN KEY(IDCommentaire) REFERENCES commentaire (IDCommentaire)
);

CREATE TABLE commentaire_proposition(
    IDProposition INT,
    IDCommentaire INT,
    PRIMARY KEY(IDProposition, IDCommentaire),
    FOREIGN KEY(IDProposition) REFERENCES proposition (IDProposition),
    FOREIGN KEY(IDCommentaire) REFERENCES commentaire (IDCommentaire)
);

CREATE TABLE envoie_reaction (
    IDCommentaire INT NOT NULL,
    IDReaction INT NOT NULL,
    loginUtilisateur VARCHAR(50) NOT NULL,
    PRIMARY KEY (IDCommentaire, IDReaction, loginUtilisateur),
    FOREIGN KEY (IDCommentaire) REFERENCES commentaire(IDCommentaire) ON DELETE CASCADE,
    FOREIGN KEY (IDReaction) REFERENCES reaction(IDReaction) ON DELETE CASCADE,
    FOREIGN KEY (loginUtilisateur) REFERENCES utilisateur(loginUtilisateur) ON DELETE CASCADE
);

CREATE TABLE commentaire_signalement(
    IDSignalement INT,
    IDCommentaire INT,
    PRIMARY KEY(IDSignalement, IDCommentaire),
    FOREIGN KEY(IDSignalement) REFERENCES signalement(IDSignalement),
    FOREIGN KEY(IDCommentaire) REFERENCES commentaire (IDCommentaire)
);

CREATE TABLE invitation (
    IDInvitation INT AUTO_INCREMENT PRIMARY KEY,
    emailInvite VARCHAR(50) NOT NULL,
    IDGroupe INT NOT NULL,
    loginUtilisateur VARCHAR(50) NOT NULL,
    dateInvitation DATE DEFAULT CURRENT_DATE,
    statutInvitation ENUM('En attente', 'Acceptée', 'Refusée') DEFAULT 'En attente',
    FOREIGN KEY (IDGroupe) REFERENCES groupe (IDGroupe),
    FOREIGN KEY (loginUtilisateur) REFERENCES utilisateur(loginUtilisateur)
);





