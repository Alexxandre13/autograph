# AUTOGraph

Ceci est un projet qui m'a été demandé pour un entretien d'embauche. Il s'agit d'un CRUD de véhicules avec appel d'Api externe.

## Cahier des charges

J'ai effectué toutes les demandes qui était sur le document que je remets ici : 

Création d’un projet Symfony 5.2 comprenant les fonctions suivantes :
- Formulaire d’ajout/modification de véhicules.
Un véhicule est composé d’un nom, d’un état (il s’agit d’une liste fixe, non administrable. Par exemple: Neuf, endommagé, cassé), d’une plaque d’immatriculation,
lors de l’ajout il faut vérifier que la plaque d’immatriculation n’est pas déjà utilisée (via des contraintes Doctrine)
- Tableau listant les véhicules avec un bouton d’édition et de suppression
Le tableau contiendra les colonnes suivantes: « Id », « Nom », « Plaque d’immatriculation », « État ».
La colonne « État » est composée d’une liste déroulante positionnée sur l’état du véhicule. Il est possible de changer la valeur directement depuis le tableau.
En JavaScript il faut détecter le changement et envoyer la mise à jour en AJAX via Jquery.
Les autres colonnes présentent uniquement la valeur.
- Page détail d'un véhicule qui affiche le nom, l'immatriculation et appelle les APIs externes suivantes pour obtenir un lien permettant d’acheter des pièces détachées compatibles :
Voici la liste des appels à faire pour obtenir ce lien : https://www.piecesauto.com/homepage/numberplate? value=NUMERODEPLAQUE retourne un JSON avec un carID https://www.piecesauto.com//common/seekCar?carid=CARID&language=fr retourne un chemin qui permet de construire l’url à afficher dans la page de
détail : https://www.piecesauto.com/CHEMIN

## Ne marche pas

- La génération des liens avec l'API PiecesAuto. Tout fonctionnait en local mais une fois l'application déployé sur le serveur, l'api répond que je ne suis pas en France... Je pense que je vais avoir une petite discussion avec OVH. Pour palier au problème, j'ai renvoie simplement le lien vers le site de PiecesAuto.com.

## Notes

J'ai laissé l'application en environnement de développement pour faire apparaitre la barre de debug en bas.

## Installation du projet

### Requis

- php 7.2.5 ou supérieur.
- Composer version 2 ou supérieur.
- Docker version 20.10.8 ou supérieure.

### Installation du projet en local

Copier le projet depuis ce dépôt:
`git clone https://github.com/Alexxandre13/autograph.git`

Lancer la base de données ainsi qu'Adminer.
`docker-compose up -d`

Installer les dépendances php.
`composer install`

Mettre à jour la base de données
`php bin/console doctrine:migrations:migrate --no-interaction`

Installer les dépendances CSS, JS (Bootstrap entre autre et l'ajax)
`npm install`
`npm run build`

Lancer le serveur de développement
`symfony serve -d`
