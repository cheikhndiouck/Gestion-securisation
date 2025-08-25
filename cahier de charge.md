

































                ┌───────────────┐
                | Responsables  |
                | id_responsable|
                | nom           |
                | centre_resp.  |
                └───────┬───────┘
                        │ émis par
                        │
                ┌───────▼────────┐
                | Bons_de_sortie |
                | id_bon         |
                | num_bon        |
                | date_sortie    |
                | zone           |
                | origine_demande|
                └───────┬────────┘
        contient        │ lié à
                        │
 ┌───────────────┐      │         ┌──────────────┐
 | Matériels     |      │         | Réception    |
 | id_materiel   |◄─────┘         | id_reception |
 | designation   | utilise        | num_fiche    |
 | type          |                | date_recept. |
 | prix_unitaire |                | id_bon (FK)  |
 └───────────────┘                | id_gie (FK)  |
                                  └───────┬──────┘
                                          │ effectué par
                                          │
                                  ┌───────▼───────┐
                                  | GIE           |
                                  | id_gie        |
                                  | nom           |
                                  | zone          |
                                  └───────┬───────┘
                                          │ concerne
                                          │
                                  ┌───────▼───────┐
                                  | Factures      |
                                  | id_facture    |
                                  | num_facture   |
                                  | date_facture  |
                                  | montant_total |
                                  | id_reception  |
                                  └───────────────┘


         ┌────────────────┐
         | Clients        |
         | id_client      |
         | nom, prenom    |
         | email          |
         | zone           |
         | contrat        |
         └────────────────┘
