# Thin Martian CMS #

Thin Martian CMS platform built on the Laravel PHP Framework

----------

*IN DEVELOPMENT. BELOW IS A DUMP OF INFORMATION AND NOTES FOR NOW, WILL BE ORGANISED AT A LATER DATE*


----------

## Deployment ##

SSH Private and Public keys are on LastPass under `CMS SSH Private Key` and `CMS SSH Public Key`.

Example config below:


    Host thinmartiancms
        HostName github.com
        Port 22
        User git
        IdentityFile /home/steve/.ssh/keys/thinmartiancms
