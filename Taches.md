# Liste des taches Projet Opérateur S4 Final Exam

# Version 1

## BDD:
- Migration table admins [x] Rovatiana
- Migration table prefixes [x] Rovatiana
- Migration table types_operation [x] Rovatiana
- Migration table baremes [x] Rovatiana
- Migration table transactions [x] Rovatiana
- Migration table clients [x] Rovatiana
- Seeder client de test [x] Jérémie

## Coté Opérateur : Jérémie

- Login ()
    - AdminModel (recherche par email) [x] Jérémie
    - Fonction de vérification des champs (validation + password) [x] Jérémie
    - Gestion de la session admin (session()->set()) [x] Jérémie
    - Filter AdminAuth (protection des routes /admin/*) [x] Jérémie
    - vue login [x] Jérémie
- Page Liste préfixe (page qui liste les préfixes disponible)
    - PrefixeModel [x] Jérémie
    - Fonction ajouter un préfixe (insert + validation format) [x] Jérémie
    - Fonction supprimer un préfixe [x] Jérémie
    - Fonction lister les préfixes [x] Jérémie
    - vue prefixe [x] Jérémie
- Page Frais (liste les frais par tranche)
    - Fonction ajouter une tranche (insert + validation min < max) [x] Jérémie
    - Fonction modifier une tranche (update) [x] Jérémie
    - Fonction supprimer une tranche [x] Jérémie
    - Fonction lister les barèmes par type d'opération [x] Jérémie
    - Vue frais[x] Jérémie
- Page Totale frais retrais/transfert
    - Fonction requête SUM(frais) GROUP BY type_operation [x] Jérémie
    - Fonction qui formate les données en JSON pour Chart.js [x] Jérémie
    - Vue Totale [x] Jérémie

## Coté Client : 

- Login()
    - ClientModel (recherche par téléphone) [x] Rovatiana
    - Fonction vérifier existence + créer automatiquement si absent [x] Rovatiana
    - Session client (session()->set(...)) [x] Rovatiana
    - Filter ClientAuth (protection des pages dépôt/retrait/transfert/historique) [x] Rovatiana
    - Vue [x] Rovatiana
- Page Depot 
    - Fonction validation montant (positif, non vide) [x] Rovatiana
    - Fonction créditer le solde + enregistrer la transaction [x] Rovatiana
    - Vue [x] Rovatiana
- Page Retrait
    - Fonction validation montant + vérification solde suffisant (solde >= montant + frais) [x] Rovatiana
    - Fonction calcul des frais via le barème opérateur (type = retrait) [x] Rovatiana
    - Fonction débiter le solde + enregistrer la transaction [x] Rovatiana
    - Vue [x] Rovatiana
- Page Transfert
    - Fonction validation numéro destinataire existant [x] Rovatiana
    - Fonction validation solde suffisant (solde >= montant + frais) [x] Rovatiana
    - Fonction calcul des frais via le barème opérateur (type = transfert) [x] Rovatiana
    - Option "inclure frais de retrait" : calcul + prélèvement anticipé [x] Rovatiana
    - Fonction double mouvement (débit émetteur + crédit destinataire) + enregistrement transaction [x] Rovatiana
    - Vue [x] Rovatiana
- Page Historique
    - TransactionModel : requête de base (par client_id) [x] Rovatiana
    - Fonction filtre par type d'opération [x] Rovatiana
    - Fonction filtre par date (plage ou date précise) [x] Rovatiana
    - Fonction filtre par montant (min/max) [x] Rovatiana
    - Fonction tri asc/desc (probablement sur la date ou le montant) [x] Rovatiana
    - Vue [x] Rovatiana

# Version 2

## BDD: 
- Migration table operateurs [x] Rovatiana
- Migration ALTER prefixes : ajout colonne operateur_id [x] Rovatiana
- Migration ALTER transactions : ajout operateur_destinataire_id, commission_externe, groupe_id [x] Rovatiana
- Migration table transferts_groupes [x] Rovatiana
- Seeder operateur + préfixes avec operateur_id [x] Rovatiana
- base.sql : schéma complet v2 + seed data [x] Rovatiana

## Coté Opérateur : Jérémie

- Page Liste préfixe (mise à jour)
    - Fonction lister les préfixes avec leur opérateur associé [x] Jérémie
    - Formulaire ajout préfixe : sélection de l'opérateur [x] Jérémie
- Page Gestion des opérateurs
    - OperateurModel [x] Jérémie
    - Fonction ajouter un opérateur [x] Jérémie
    - Fonction modifier un opérateur [x] Jérémie
    - Fonction supprimer un opérateur [x] Jérémie
    - Fonction lister les opérateurs [x] Jérémie
    - Vue opérateurs [x] Jérémie
- Page Commissions externes
    - CommissionModel [x] Jérémie
    - Fonction ajouter/configurer un % de commission par opérateur [x] Jérémie
    - Fonction modifier un % de commission [x] Jérémie
    - Fonction lister les commissions par opérateur [x] Jérémie
    - Vue commissions [x] Jérémie
- Page Totale frais retrais/transfert (mise à jour)
    - Fonction séparation gains internes / gains externes (par opérateur) [x] Jérémie
    - Fonction SUM(commission_externe) par opérateur [x] Jérémie
    - Vue Totale : blocs distincts interne / externe [x] Jérémie
- Page Montants à envoyer aux opérateurs
    - Fonction requête SUM(montant) GROUP BY operateur_destinataire_id (transferts externes uniquement) [x] Jérémie
    - Vue montants à reverser par opérateur [x] Jérémie

## Coté Client : Rovatiana

- Page Transfert (mise à jour)
    - OperateurModel + TransfertsGroupeModel créés [x] Rovatiana
    - PrefixesModel : ajout operateur_id + getOperateurIdByTelephone() [x] Rovatiana
    - Fonction détection opérateur destinataire (interne/externe via AJAX) [x] Rovatiana
    - Radio frais_retrait visible uniquement si destinataire même opérateur [x] Rovatiana
    - Vue : multi-destinataires dynamique (ajout/suppression) [x] Rovatiana
- Page Envoi multiple
    - Envoi multiple vers N destinataires (même opérateur uniquement) [x] Rovatiana
    - Répartition du montant total / nombre de destinataires [x] Rovatiana
    - Validation solde suffisant (montant total + somme des frais) [x] Rovatiana
    - Création transferts_groupes + transactions liées (groupe_id) [x] Rovatiana
    - Vue formulaire multi-destinataires + récapitulatif [x] Rovatiana
- Page Historique (mise à jour)
    - TransactionsModel : jointure transferts_groupes + groupe_nb [x] Rovatiana
- Ajout truc de commission dans les calculs [x]