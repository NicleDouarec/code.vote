
this document gives you a line by line description of what's required to setup and run an election using code.vote and belenios underlying cryptographic protocol

# Root

Please ask us to create a server instance for you

# Administrator

login to your code.vote server with your userid and password

    $ SSH AdminUserID@code.vote
    AdminUserID@code.vote's password:
    
### setting up the election 

1. Generate an UUID with the `uuidgen` command.

        $ uuidgen
         4f112241-8b3c-4b6b-8995-9c224c44867d
    

2. Edit `questions.json`

3. create the **bulletin board**
`belenios-tool mkelection --uuid $UUID --group default.json --template questions.json`
to generate `election.json`

4. Publish `$UUID` on the bulletin board and notify the **trustees** and the **census**

### Tallying the election

 1. check  `election.json`,`public_keys.jsons`, `public_creds.txt` and `ballots.jsons`files
 2. Concatenate the `partial_decryption.json` received from each trustee into a `partial_decryptions.jsons`, in the same order as in
    `public_keys.jsons`.
 3. Run `belenios-tool finalize`.  It will create
    `result.json`. 
Publish this file on the **bulletin board**, along with the files listed in the first step above. The whole set will enable universal
    verifiability.

Note: `partial_decryptions.jsons` is a temporary file whose contents is embedded in `result.json`, so it can be discarded.

## Auditor

**ANYONE** can be an auditor. Everyone who aims to play a role
in an election should start by auditing the election data.

During an election, anybody has read only access to the following files:

 * `election.json`: election parameters
 * `public_keys.jsons`: trustees' public keys
 * `public_creds.txt`: the public keys associated to valid credentials
 * `ballots.jsons`: accepted ballots

Note that the last one is dynamic, and evolve during the election. At
the end of the election, it is frozen and a `result.json` file will be
published.

simply run the following command

    belenios-tool verify


Voter's guide
-------------

If you put your secret credential in a file `/path/to/credential` and
your choices in a file `/path/to/choices.json` (as an array of arrays
of 0/1 in JSON format), the following command will output a raw ballot
that can be sent to the administrator of the election:

    belenios-tool vote --dir /path/to/election --privcred /path/to/credential --ballot /path/to/choices.json

In the case where the election is administered with the web interface,
a raw ballot prepared with the command-line tool can be uploaded directly
via the web interface.


## bulletin board

### Running the election

For each received ballot, append it to `ballots.jsons` and run:

    belenios-tool verify --dir $DIR

If no error is reported, publish the new `ballots.jsons`; otherwise,
the new ballot is incorrect and revert `ballots.jsons` to its
previous state.

Note that each ballot must be authenticated in order to prevent the
credential authority from stuffing the ballot box.

Credential authority's guide
----------------------------

### Credential generation

If you have a list of identities in a file `F` with `N` lines, one
identity per line, run:

    belenios-tool credgen --uuid XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX --file F

where `XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX` is the UUID of the
election given by the administrator. It will generate three files with
`N` lines:

 * `T.privcreds`: each line of this file contains an identity and a
   private credential. Send each voter the associated credential. Keep
   this file secret, and secure if you want to be able to re-send a
   credential later (e.g. if a voter lost or did not receive it).
 * `T.pubcreds`: each line of this file contains a public credential.
   Send the whole file to the election administrator; it will be the
   `public_creds.txt` for the election (and you must check that);
 * `T.hashcreds`: each line of this file contains, for each id in
   `T.privcreds`, the hash of the corresponding public key. At the
   moment, this file has no practical purpose (but this might change in
   the future). Destroy it.



Trustee's guide
---------------

### Key generation

To generate a keypair, run:

    belenios-tool trustee-keygen

It will generate two files, `XXXXXXXX.public` and `XXXXXXXX.private`,
containing respectively the public and the private key. Send the
public key file to the server administrator, and keep the private key
with extreme care. When the election is open, you must check that
your public key is present in the published `public_keys.jsons`.

### Partial decryption

To compute your decryption share, set `/path/to/election` up as
described in the _Voter's guide_ section above, and run:

    belenios-tool decrypt --dir /path/to/election --privkey /path/to/privkey > partial_decryption.json

and send `partial_decryption.json` to the election administrator.

Note: be sure to authenticate all your input files when you use your
private key!
