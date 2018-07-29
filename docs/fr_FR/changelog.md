# xx/xx/2018 - V1.2
- Mise à jour de la documentation

# 29/07/2018 - V1.1

- Ajout de la documentation (en cours)
- Page Plugin: Mise en conformité avec dernières évolutions des plugins officiels (ajout lien vers page de configuration) et améliorations visuelles.
- Page Plugin: dernière option: label inversé de l'effet de l'option. Corrigé. 
- Panel: Modification visuelles mineures pour la gestion d'un compte qui a un grand nom. 
- Lien avec GitHub

# 28/07/2018 - V1.0

- Première version stable (sans documentation à jour au nouveau format)

# Bugs connu à corriger
- Dans le panel, le switch entre les compte génère un problème d'affichage du menu à gauche et l'option d'accès à la configuration du compte ne point pas sur le bon compte si l'on vient du dashboard (avec l'id du compte dans la barre des tâches)

# Versions de développement

### V0.1
1ère version du plugin 

### V0.2
Modification de la couleur de l'icône du plugin 
Correction d'un bug lors de la sélection d'une catégorie "chapeau", son nom était affiché deux fois. 

### V0.3
Ajout d'une fonction d'import de catégories par défaut (proposition) 
Mise à jour de la documentation associée 

### V0.4
Correction d'un bug de lancement du cron 
Ajout d'une information sur le solde à la fin du mois 

### V0.5
Non affichage d'un compte dans le panel, si l'option "visible" n'est pas cochée 
Modification visuel liste des banque dans le panel comptes 
Réorganisation des images 
Ajout de logos de banques par défaut 
Ajout d'une fonction qui permet de cacher le montant d'une opération (en double cliquant sur le logo de la catégorie) 

### V0.6
Changement sélection de la catégorie: Il faut maintenant cliquer sur le logo de la catégorie pour en changer: introduction d'un logo "no_cat" par défaut 
Modification de l'option "double clic sur la catégorie pour cacher le montant de la transaction" : suite à la modification précédente le double clic est remplacé par le clic droit 
Ajout fonction de transfert entre compte 
Modification des icônes dans le panel compte en dessous des comptes: réduction de taille 

### V0.7
Ajout d'une fonction d'historisation du solde des comptes 

### V0.8
Ajout d'icones pour la banque "compte nickel" (merci Numeror)  
Correction bug mise à jour historisation des comptes 

### V0.9
Modification pour une utilisation par des utilisateurs non admin  
Modification système des commandes pour pouvoir récupérer le solde  

### V0.10
Amélioration de l'affichage pour plus petites résolutions  
Changement de logique pour l'affichage des comptes: visible = widget sur le dasboard ; activé = visible dans le panel compte 
Correction bug sur l'affichage des soldes du compte (non raffraichi dans certains cas)  

### V0.11
Refonte de l'interface utilisateur du panel (encore de nombreux bugs)  
Correctifs / adaptation évolutions jeedom 

### V0.12
Changement de méthode d'affichage des opérations : repassage en mode tableau pour faciliter l'affichage dynamique des opérations  
Ajout d'un lien vers la configuration de la banque directement depuis la liste des opérations  

### V0.13
Option "Activation du pointage" rendue opérationelle afin de pouvoir désactiver ce comportement. 
Optoin "Type d'opération" rendue opérationnelle afin de pouvoir désactiver cette option 
A venir dans la prochaine version, la dernière option pour une seule date d'opération au lieu de deux. 

### V0.14
Correction bug édition option Activation Pointage désactivée  

### V1.0
Corrections visuelles mineures 
Gestion de l'option une seule date au lieu d'une date opération et une date valeur 
Correction bug dans la gestion des historiques et ajout de la particularité de l'option de pointage activée ou non 
Correction gestion des catégorie: ajout de la catégorie par défaut "Aucune" non supprimable. 
Correctton bug focus catégorie lors de l'édition
TODO: reste à modifier la documentation avec la nouvelle méthode pour stabiliser la version 
