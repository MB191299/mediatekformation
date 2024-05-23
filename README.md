# Mediatekformation
## Présentation
Ce site, développé avec Symfony 5.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.<br> 
Actuellement, seule la partie front office a été développée. Elle contient les fonctionnalités globales suivantes :<br>
![img1](https://github.com/CNED-SLAM/mediatekformation/assets/100127886/eed72688-c9e5-4509-ab44-7309d3e86041)
## Les différentes pages
Voici les 5 pages correspondant aux différents cas d’utilisation.
### Page 1 : l'accueil
Cette page présente le fonctionnement du site et les 2 dernières vidéos mises en ligne.<br>
La partie du haut contient une bannière (logo, nom et phrase présentant le but du site) et le menu permettant d'accéder aux 3 pages principales (Accueil, Formations, Playlists).<br>
Le centre contient un texte de présentation avec, entre autres, les liens pour accéder aux 2 autres pages principales.<br>
La partie basse contient les 2 dernières formations mises en ligne. Cliquer sur une image permet d'accéder à la page 3 de présentation de la formation.<br>
Le bas de page contient un lien pour accéder à la page des CGU : ce lien est présent en bas de chaque page excepté la page des CGU.<br>
![img2](https://github.com/CNED-SLAM/mediatekformation/assets/100127886/9168058b-7e21-4dc9-a6b8-d6299f5b16c9)
### Page 2 : les formations
Cette page présente les formations proposées en ligne (accessibles sur YouTube).<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale contient un tableau composé de 5 colonnes :<br>
•	La 1ère colonne ("formation") contient le titre de chaque formation.<br>
•	La 2ème colonne ("playlist") contient le nom de la playlist dans laquelle chaque formation se trouve.<br>
•	La 3ème colonne ("catégories") contient la ou les catégories concernées par chaque formation (langage…).<br>
•	La 4ème colonne ("date") contient la date de parution de chaque formation.<br>
•	LA 5ème contient la capture visible sur YouTube, pour chaque formation.<br>
Au niveau des colonnes "formation", "playlist" et "date", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">").<br>
Au niveau des colonnes "formation" et "playlist", il est possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les formations qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les formations.<br>
Par défaut la liste est triée sur la date par ordre décroissant (la formation la plus récente en premier).<br>
Le fait de cliquer sur une miniature permet d'accéder à la troisième page contenant le détail de la formation.<br>
![img3](https://github.com/CNED-SLAM/mediatekformation/assets/100127886/6744b340-b6a2-41cb-ae43-18b4ba86f29e)
### Page 3 : détail d'une formation
Cette page n'est pas accessible par le menu mais uniquement en cliquant sur une miniature dans la page "Formations" ou une image dans la page "Accueil".<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale est séparée en 2 parties :<br>
•	La partie gauche contient la vidéo qui peut être directement visible dans le site ou sur YouTube.<br>
•	La partie droite contient la date de parution, le titre de la formation, le nom de la playlist, la liste des catégories et sa description détaillée.<br>
![img4](https://github.com/CNED-SLAM/mediatekformation/assets/100127886/6c8b31ef-b650-4b69-8cf9-fbca8f340cde)
### Page 4 : les playlists
Cette page présente les playlists.<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale contient un tableau composé de 3 colonnes :<br>
•	La 1ère colonne ("playlist") contient le nom de chaque playlist.<br>
•	La 2ème colonne ("catégories") contient la ou les catégories concernées par chaque playlist (langage…).<br>
•	La 3ème contient un bouton pour accéder à la page de présentation de la playlist.<br>
Au niveau de la colonne "playlist", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">"). Il est aussi possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les playlists qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les playlists.<br>
Par défaut la liste est triée sur le nom de la playlist.<br>
Cliquer sur le bouton "voir détail" d'une playlist permet d'accéder à la page 5 qui présente le détail de la playlist concernée.<br>
![img5](https://github.com/CNED-SLAM/mediatekformation/assets/100127886/83e4a279-3882-46d2-a7d8-b1b511c184b7)
### Page 5 : détail d'une playlist
Cette page n'est pas accessible par le menu mais uniquement en cliquant sur un bouton "voir détail" dans la page "Playlists".<br>
La partie haute est identique à la page d'accueil (bannière et menu).<br>
La partie centrale est séparée en 2 parties :<br>
•	La partie gauche contient les informations de la playlist (titre, liste des catégories, description).<br>
•	La partie droite contient la liste des formations contenues dans la playlist (miniature et titre) avec possibilité de cliquer sur une formation pour aller dans la page de la formation.<br>
![img6](https://github.com/CNED-SLAM/mediatekformation/assets/100127886/f72a1d0f-fcc7-4fea-bf91-5a3f301e96db)
## La base de données
La base de données exploitée par le site est au format MySQL.
### Schéma conceptuel de données
Voici le schéma correspondant à la BDD.<br>
![img7](https://github.com/CNED-SLAM/mediatekformation/assets/100127886/1f1f4c83-5955-4ae9-b2f2-a030055c1d3f)
<br>video_id contient le code YouTube de la vidéo, qui permet ensuite de lancer la vidéo à l'adresse suivante :<br>
https://www.youtube.com/embed/<<<video_id>>>
### Relations issues du schéma
<code><strong>formation (id, published_at, title, video_id, description, playlist_id)</strong>
id : clé primaire
playlist_id : clé étrangère en ref. à id de playlist
<strong>playlist (id, name, description)</strong>
id : clé primaire
<strong>categorie (id, name)</strong>
id : clé primaire
<strong>formation_categorie (id_formation, id_categorie)</strong>
id_formation, id_categorie : clé primaire
id_formation : clé étrangère en ref. à id de formation
id_categorie : clé étrangère en ref. à id de categorie</code>

Remarques : 
Les clés primaires des entités sont en auto-incrémentation.<br>
Le chemin des images (des 2 tailles) n'est pas mémorisé dans la BDD car il peut être fabriqué de la façon suivante :<br>
"https://i.ytimg.com/vi/" suivi de, soit "/default.jpg" (pour la miniature), soit "/hqdefault.jpg" (pour l'image plus grande de la page d'accueil).
## Installation de l'application
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
### Ajout de fonctionnalités
- Dans la page playlist, ajout d'une colonne indiquant le nombre de formations par playlist. Sur cette colonne, un tri croissant ou décroissant est possile, permettant d'afficher les playlists dans l'ordre selon leur nombre de formations.
- Création d'une partie back office, permettant la gestion des formations, playlists et catégories après authentification.
![img8](https://github.com/MB191299/mediatekformation/blob/master/diagramme%20cas%20d'utilisation/Capture%20d'%C3%A9cran%202024-05-20%20153937.png)
![img11](https://github.com/MB191299/mediatekformation/blob/master/captures%20ecran%20mediatekformation/Capture%20d'%C3%A9cran%202024-05-20%20155256.png)
- Concernant la gestion des formations, la liste apparait, et il est possible d'ajouter, modifier ou supprimer une formation. Pour l'ajout ou la modification, redirection vers un formulaire (prérempli pour la modification). Dans ce formulaire, la saisie est controllée, la playlist et la catégorie sont a sélectionner dans une liste, et la date, elle aussi a sélectionner ne peut pas etre antérieure a la date du jour.
Pour la suppression, lorsqu'une formation est supprimée, une confirmation est requise, et la formation sera automatiquement supprimée dans la playlist à laquelle elle appartient.
Les mêmes tris et filtres pésents dan le front office le sont dans le back office.
- Concernant la gestion des playlists, la liste apparait, et il est possible d'ajouter, de modifier ou de supprimer une playlist. Pour l'ajout et la modification, il y a une redirection vers un formulaire (prérempli pour la modification). Dans ce formulaire, la saisie est controllée.
Pour la suppression, elle n'est possible que si aucune formation n'est rattachée à la playlist.
Les mêmes tris et filtres présents dans le front office le sont dans le back office.
![img9](https://github.com/MB191299/mediatekformation/blob/master/diagramme%20cas%20d'utilisation/Capture%20d'%C3%A9cran%202024-05-20%20153957.png)
![img13](https://github.com/MB191299/mediatekformation/blob/master/captures%20ecran%20mediatekformation/Capture%20d'%C3%A9cran%202024-05-20%20155440.png)
- Concernant la gestion des catégories, la liste apparait, et il est possible d'ajouter ou de supprimer une catégorie. Pour l'ajoût, un mini formulaire apparait en haut de la page, et il n'est pas possible d'ajouter une catégorie si le nom est déja existant.
Pour la suppression, une catégorie ne peut être supprimée que si aucune formation ne lui est rattachée.
![img10](https://github.com/MB191299/mediatekformation/blob/master/Capture%20d'%C3%A9cran%202024-05-20%20160221.png))
![img12](https://github.com/MB191299/mediatekformation/blob/master/captures%20ecran%20mediatekformation/Capture%20d'%C3%A9cran%202024-05-20%20155440.png)
- L'authentification de l'accès est gérée par Keycloak. Il est possible de se déconnecter a partir de toutes les pages.
- La documentation technique a été générée.
- Le site est déployé et le serveur d'authentification Keycloack est configuré sur une vm en ligne.
- Mise en place du déploiement continu
