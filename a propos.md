# code.vote

Code to vote est un ensemble de processus, modes opératoires, configurations de serveurs et bien sur du code informatique qui permet d'organiser un vote numérique, dématérialisé.

Le systeme de vote ainsi formé forme une référence de l'état de l'art. Les meilleures pratiques, et les meilleures tecnologies au service d'un système de vote dématérialisé sont implémentées dans code.vote.
Le résultat est un système de vote :
- verifiable de bout en bout selon [la définition de l'US vote foundation](https://www.usvotefoundation.org/sites/default/files/E2EVIV_full_report.pdf)
- répondant aux exigences de la CNIL 

En particulier, les protocoles cryptologiques de l'urne numérique sont ceux de Belenios, projet de l'INRIA [Belenios] (http://belenios.gforge.inria.fr/)
le [code interne de Belenios](https://github.com/glondu/belenios/tree/master/src) est un logiciel libre, regi par la licence GNU Affero General Public License, version 3 ou ultérieure

Belenios est une amélioration du protocole de Helios-C décrit [ici](http://eprint.iacr.org/2013/177), lui même une évolution duu protocole de [Helios](http://vote.heliosvoting.org).


Un scrutin avec code.vote.
--------------------------

Un scrutin commence par clarifier et attribuer les roles suivants :

administrateur :
census :  
trustees :
Ballot Box

Electeurs. 

POur assurer une sécurité maxium ces roles doivent être distincts et cloisonnés (a l'exception de l'electeur) par des individus et institutions indépendantes.

 1. The administrator initiates the process.
 2. The credential authority generates one credential per voter; he
    sends the private part to each voter and all public parts to
    the administrator.
 3. Each trustee generates a keypair and sends his/her public key to
    the administrator.
 4. The administrator collects all public credentials and trustees'
    public keys and sets up the election.
 5. The administrator opens the election.
 6. Each voter votes; the administrator collects, checks and publishes
    all the ballots.
 7. The administrator closes the election.
 8. Trustees collectively decrypt the result.
 9. The administrator announces the result of the election.


