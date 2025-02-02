
# Démocratie Participative

Bienvenue dans **Démocratie Participative**, une plateforme visant à faciliter la participation citoyenne à travers diverses fonctionnalités.

## Fonctionnalités

- **Inscription** : Les nouveaux utilisateurs peuvent s'inscrire sur la plateforme.
- **Création de groupe** : Les utilisateurs peuvent créer de nouveaux groupes.
- **Invitation d'utilisateurs** : Invitez des utilisateurs existants à rejoindre vos groupes.
- **Ajout de propositions** : Ajoutez des propositions avec un budget associé.
- **Consultation des propositions** : Consultez les propositions soumises par les membres du groupe.
- **Commentaires** : Soumettez et réagissez aux commentaires sur les propositions.
- **Création de votes** : Créez des votes pour les propositions.
- **Consultation du profil** : Consultez et modifiez votre profil utilisateur.

## Installation

Pour installer ce projet localement, suivez ces étapes :

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/mjadid91/democratie_participative.git
   ```

2. Installez les dépendances :
   ```bash
   composer install
   ```

3. Configurez votre base de données et mettez à jour le fichier `.env` avec vos informations de configuration.

4. Exécutez les migrations de base de données :
   ```bash
   php artisan migrate
   ```

5. Lancez le serveur de développement :
   ```bash
   php artisan serve
   ```

## Utilisation

Après avoir installé le projet, vous pouvez accéder à l'application via `http://localhost:8000` et commencer à utiliser les fonctionnalités décrites ci-dessus.

## Contribuer

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le dépôt.
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`).
3. Commitez vos modifications (`git commit -m 'Add some AmazingFeature'`).
4. Poussez vers la branche (`git push origin feature/AmazingFeature`).
5. Ouvrez une Pull Request.


