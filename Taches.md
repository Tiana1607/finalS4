# Liste des taches Projet Opérateur S4 Final Exam

## BDD: 
- Migration table admins [x] Rovatiana
- Migration table prefixes [x] Rovatiana
- Migration table types_operation [x] Rovatiana
- Migration table baremes [x] Rovatiana
- Migration table transactions [x] Rovatiana
- Migration table clients [x] Rovatiana
- Seeder client de test []

## Coté Opérateur :

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

## Coté Client :

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
    - Fonction validation montant + vérification solde suffisant (solde >= montant + frais) []
    - Fonction calcul du frais via le barème opérateur (type = retrait) []
    - Fonction débiter le solde + enregistrer la transaction []
    - Vue
- Page Transfert
    - Fonction validation numéro destinataire existant []
    - Fonction validation solde suffisant (solde >= montant + frais) []
    - Fonction calcul du frais via le barème opérateur (type = transfert) []
    - Fonction double mouvement (débit émetteur + crédit destinataire) + enregistrement transaction 
    - Vue

- Page Historique
    - TransactionModel : requête de base (par client_id) []
    - Fonction filtre par type d'opération []
    - Fonction filtre par date (plage ou date précise) []
    - Fonction filtre par montant (min/max) []
    - Fonction tri asc/desc (probablement sur la date ou le montant) []
    - Vue