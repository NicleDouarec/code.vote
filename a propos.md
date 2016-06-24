# code.vote

Code to vote est un ensemble de processus, modes opératoires, configurations de serveurs et bien sur du code informatique qui permet d'organiser un vote numérique, dématérialisé.

Le systeme de vote ainsi formé forme une référence de l'état de l'art. Les meilleures pratiques, et les meilleures tecnologies au service d'un système de vote dématérialisé sont implémentées dans code.vote.
Le résultat est un système de vote :
- verifiable de bout en bout selon [la définition de l'US vote foundation](https://www.usvotefoundation.org/sites/default/files/E2EVIV_full_report.pdf)
- répondant aux exigences de la CNIL dans leur [DELIBERATION n°2010-371 du 21 octobre 2010](https://www.legifrance.gouv.fr/affichCnil.do?id=CNILTEXT000023174487)

Cela en fait un service unique et résilient en environnement hostile, à destination de toute institution 
- souhaitant une meilleure alternative au vote par correspondance, 
- lorsqu'un vote physique à l'isoloir n'est pas possible
- souhaitant [innover dans le mode de scrutin](https://fr.wikipedia.org/wiki/Syst%C3%A8me_%C3%A9lectoral) grace au numérique

En particulier, les protocoles cryptologiques de l'urne numérique sont ceux de Belenios, projet de l'INRIA [Belenios] (http://belenios.gforge.inria.fr/)
le [code interne de Belenios](https://github.com/glondu/belenios/tree/master/src) est un logiciel libre, regi par la licence GNU Affero General Public License, version 3 ou ultérieure

Belenios est une amélioration du protocole de Helios-C décrit [ici](http://eprint.iacr.org/2013/177), lui même une évolution duu protocole de [Helios](http://vote.heliosvoting.org).


Un scrutin avec code.vote.
--------------------------

### création de l'instance de vote
**root** crée et configure l'instance serveur et les types d'utilisateurs qui forment le système de vote

## configuration de l'election

1. l'**administrateur** technique du crutin numérique initialise les parametres : UUID du crutin, type de scrutin, edition des questions et des choix et leurs options, date de debut et de fin de la période de vote.
2. les **trustees** (commissaires) generent leurs clés de dépouillement et affichent leur clé publique sur le **bulletin board** (bureau de vote numérique))

## création du corps électoral

1. le **census** (registre) enregistre les **electeurs** et leur remet leur clé (privée !) de vote et leur méthode d'authentification. A aucun moment les données personnelles de l'**electeur** ne sont transmises au **census**.
2. lorsque l'enregistrement est clos le **census** publie la liste anonyme des clés publiques de vote sur le **bulletin board**

le scrutin est pret pour la période de vote.

## période de vote

1. l'**électeur** vote et chiffre son bulletin dans son navigateur web.
2. l'**electeur** valide le bulletin en le signant avec la methode d'authentifcation du registre. Une copie est conservée sur son ordinateur.
3. l'**electeur** envoie son bulletin de vote chiffré signé au **bulletin board** qui le publie.
4. a la fin de la période de vote le scrutin est figé et toute modification du **bulletin board** est impossible

qui plus est à tout moment :
- chaque **electeur** peut modifier son vote jusqu'à la cloture de la période de vote. C'est le dernier bulletin qui est pris en compte dans le dépouillement.
- chaque **électeur** peut verifier que son bulletin est bien présent dans l'urne numérique via le **bulletin board**
- quiconque peut verifier que les bulletins de vote publiés sur le **bulletin board** sont bien valides

## dépouillement

1. l'**administrateur** clôt le scrutin, le **bulletin board** est définitivement figé et ne peut plus être modifié par personne
2. les **trustees** (commissiares) décryptent collectivement les résultats et les publie sur le **bulletin board**
3. l'**adnministrateur** agrege et vérifie les résultats.

le résultat du vote est public et vérifiable a l'iade des parametres affichés sur le **bulletin board**.


