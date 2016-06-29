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

Belenios est une amélioration du protocole de Helios-C décrit [ici](http://eprint.iacr.org/2013/177), lui même une évolution du protocole de [Helios](http://vote.heliosvoting.org).


Un scrutin avec code.vote.
--------------------------

le scrutin numerique est similaire a tout scrutin et comporte :
- une phase de préapration
- une phase / tache d'enregistrement des electeurs dans une 'liste lectorale'
- une phase de vote a proprement parlé
- une phase de dépuoillement, vérification
- un archivage auditable.

le détail opératoire se trouve sur le [**wiki**](https://github.com/NicleDouarec/code.vote/wiki)


