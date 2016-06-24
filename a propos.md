# code.vote

Code to vote est un ensemble de processus, modes opératoires et bien sur code qui permet d'organiser un vote numérique, dématérialisé.

Le systeme de vote ainsi formé forme une référence de l'état de l'art. Les meilleures pratiques, et les meilleures tecnologies au service d'un système de vote dématérialisé sont implémentées dans code.vote.
Le résultat est un système

qui répond a la fois et aux exigences de la CNIL 

En particulier, les protocoles cryptologiques de l'urne numérique sont ceux de Belenios, projet de l'INRIA [Belenios] (http://belenios.gforge.inria.fr/)
le code interne de Belenios est un logiciel libre, regi par la licence GNU Affero General Public License, version 3 ou ultérieure

Belenios est une amélioration du protocole de Helios-C décrit [ici](http://eprint.iacr.org/2013/177), lui même une évolution duu protocole de [Helios](http://vote.heliosvoting.org).


Election overview
-----------------

An election involves several roles: an administrator, a credential
authority, trustees and voters. For maximum security, each of these
roles must be performed by a different entity. An election can be
summarized as follows:

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


