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
    - AdminModel (recherche par email) []
    - Fonction de vérification des champs (validation + password) []
    - Gestion de la session admin (session()->set()) []
    - Filter AdminAuth (protection des routes /admin/*) []
    - vue login []
- Page Liste préfixe (page qui liste les préfixes disponible)
    - PrefixeModel []
    - Fonction ajouter un préfixe (insert + validation format) []
    - Fonction supprimer un préfixe []
    - Fonction lister les préfixes []
    - vue prefixe []
- Page Frais (liste les frais par tranche)
    - Fonction ajouter une tranche (insert + validation min < max) []
    - Fonction modifier une tranche (update) []
    - Fonction supprimer une tranche []
    - Fonction lister les barèmes par type d'opération []
    - Vue frais[]
- Page Totale frais retrais/transfert
    - Fonction requête SUM(frais) GROUP BY type_operation []
    - Fonction qui formate les données en JSON pour Chart.js []
    - Vue Totale []

## Coté Client : Rovatiana

- Login()
    - ClientModel (recherche par téléphone) [x]
    - Fonction vérifier existence + créer automatiquement si absent [x]
    - Session client (session()->set(...)) [x]
    - Filter ClientAuth (protection des pages dépôt/retrait/transfert/historique) [x]
    - Vue [x]
- Page Depot 
    - Fonction validation montant (positif, non vide) [x]
    - Fonction créditer le solde + enregistrer la transaction [x]
    - Vue [x]
- Page Retrait
    - Fonction validation montant + vérification solde suffisant (solde >= montant + frais) [x]
    - Fonction calcul des frais via le barème opérateur (type = retrait) [x]
    - Fonction débiter le solde + enregistrer la transaction [x]
    - Vue [x]
- Page Transfert
    - Fonction validation numéro destinataire existant [x]
    - Fonction validation solde suffisant (solde >= montant + frais) [x]
    - Fonction calcul des frais via le barème opérateur (type = transfert) [x]
    - Fonction double mouvement (débit émetteur + crédit destinataire) + enregistrement transaction [x]
    - Vue [x]
- Page Historique
    - TransactionModel : requête de base (par client_id) [x]
    - Fonction filtre par type d'opération [x]
    - Fonction filtre par date (plage ou date précise) [x]
    - Fonction filtre par montant (min/max) [x]
    - Fonction tri asc/desc (probablement sur la date ou le montant) [x]
    - Vue [x]

# Version 2

## BDD:
- Migration table operateurs []
- Migration ALTER prefixes : ajout colonne operateur_id []
- Migration table commissions_externes []
- Migration ALTER transactions : ajout operateur_destinataire_id, commission_externe, frais_retrait_inclus, montant_frais_retrait_couvert, groupe_id []
- Migration table transferts_groupes []
- Seeder operateur "nous" + rattachement des préfixes existants []

## Coté Opérateur :

- Page Liste préfixe (mise à jour)
    - Fonction lister les préfixes avec leur opérateur associé []
    - Formulaire ajout préfixe : sélection de l'opérateur []
- Page Gestion des opérateurs
    - OperateurModel []
    - Fonction ajouter un opérateur []
    - Fonction modifier un opérateur []
    - Fonction supprimer un opérateur []
    - Fonction lister les opérateurs []
    - Vue opérateurs []
- Page Commissions externes
    - CommissionModel []
    - Fonction ajouter/configurer un % de commission par opérateur []
    - Fonction modifier un % de commission []
    - Fonction lister les commissions par opérateur []
    - Vue commissions []
- Page Totale frais retrais/transfert (mise à jour)
    - Fonction séparation gains internes / gains externes (par opérateur) []
    - Fonction SUM(commission_externe) par opérateur []
    - Vue Totale : blocs distincts interne / externe []
- Page Montants à envoyer aux opérateurs
    - Fonction requête SUM(montant) GROUP BY operateur_destinataire_id (transferts externes uniquement) []
    - Vue montants à reverser par opérateur []

## Coté Client :

- Page Transfert (mise à jour)
    - Fonction détection opérateur destinataire (interne/externe via prefixes) []
    - Fonction calcul commission externe si destinataire hors réseau []
    - Option "inclure frais de retrait" : calcul + prélèvement anticipé []
    - Vue : case à cocher "inclure frais de retrait" + récapitulatif frais détaillé []
- Page Envoi multiple
    - Fonction validation liste de numéros destinataires (existence/opérateur pour chacun) []
    - Fonction répartition du montant total / nombre de destinataires []
    - Fonction validation solde suffisant (montant total + somme des frais) []
    - Fonction création transferts_groupes + boucle transactions liées (groupe_id) []
    - Vue formulaire multi-destinataires + récapitulatif avant confirmation []
- Page Historique (mise à jour)
    - Fonction affichage détail transferts groupés []