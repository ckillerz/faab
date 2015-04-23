# FAAB

## Introduction

C'est une tribune quoi. développé initialement par LiNuCe<

## Licence

La WTFPL ou Do What The Fuck You Want to Public License (littéralement
« licence publique foutez-en ce que vous voulez ») est une licence
libre non-copyleft. Elle permet en effet la libre redistribution et
modification de l’œuvre sans aucune restriction.


## Installation de la tribune Web FAAB

### Prérequis

 Un serveur Web avec PHP et le support de la fonction de verrouillage 
 de fichier flock(). L'hébergement des pages personnelles Free ne 
 supporte pas l'appel flock() : il échouera systématiquement. 
 L'hébergement chez Dreamhost supporte flock() donc FAAB peut être 
 hébergée chez Dreamhost. De même pour OVH.

### Installation

 Exécutez :

    bash -e -x ./init.sh

 et assurez-vous qu'il termine par :

    + exit 0

 Cela génèrera un fichier config.php que vous devrez éditer pour 
 l'adapter à vos besoins. Ce fichier est largement commenté, une 
 fois que vous avez terminé de l'éditer, supprimez le fichier 
 config.php.in et ce fichier INSTALL.txt et uploadez tout ce dossier 
 sur votre site. Voilà, c'est prêt !
 
 Après upload sur votre serveur, vérifier que l'arborescence data/ 
 est bien accessible en écriture au serveur Web.

