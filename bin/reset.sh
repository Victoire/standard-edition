#!/bin/bash
#############################################################
#####################   RESET SCRIPT   ######################
#############################################################
##  ex.
## - bin/reset.sh
## - bin/reset.sh dev (same as default)
## - bin/reset.sh test
## - bin/reset.sh dev --only-db
## - bin/reset.sh dev --only-fixtures
## - bin/reset.sh dev --only-fixtures --only-seeds
#############################################################

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR/../;

env=dev
if [ "$1" != "" ]; then
    env=$1
fi

if [ "$2" != "--only-db" -o "$2" == "" ]; then
    rm -rf var/logs/*;
    rm -rf var/cache/*;

    #add --allow-root to works in bower container
    #bower install --allow-root;
    #npm install

    talkInColor="tput setaf 5"
    resetColor="tput sgr0"
    ${talkInColor}; echo ">>>>>>> php bin/console doctrine:database:drop"; ${resetColor};
    php bin/console doctrine:database:drop --env=$env --force;
    ${talkInColor}; echo ">>>>>>> php bin/console doctrine:database:create"; ${resetColor};
    php bin/console doctrine:database:create --env=$env;
    ${talkInColor}; echo ">>>>>>> php bin/console bazinga:js:dump"; ${resetColor};
    php bin/console bazinga:js:dump --env=$env;
    ${talkInColor}; echo ">>>>>>> php bin/console cache:warmup"; ${resetColor};
    php bin/console cache:warmup --env=$env;
    ${talkInColor}; echo ">>>>>>> php bin/console doctrine:schema:update"; ${resetColor};
    php bin/console doctrine:schema:update --env=$env --force --dump-sql;
    ${talkInColor}; echo ">>>>>>> php bin/console assets:install"; ${resetColor};
    php bin/console assets:install --env=$env;
    ${talkInColor}; echo ">>>>>>> php bin/console assetic:dump"; ${resetColor};
    php bin/console assetic:dump --env=$env;
    ${talkInColor}; echo ">>>>>>> php bin/console victoire:viewReference:generate"; ${resetColor};
    php bin/console victoire:viewReference:generate --env=$env;
    ${talkInColor}; echo ">>>>>>> php bin/console victoire:widget-cs"; ${resetColor};
    php bin/console victoire:widget-css:generate --env=$env;

    chmod -R 777 var/logs/;
    chmod -R 777 var/cache/;
fi

if [ "$2" == "--only-db" -o "$2" == "" ]; then
    php bin/console doctrine:schema:drop --env=$env --force
    php bin/console doctrine:schema:create --env=$env
fi

if [ "$2" == "--only-fixtures" -o "$2" == "--only-db" -o "$2" == "" ]; then
    if [ "$3" == "--only-seeds" ]; then
        php bin/console doctrine:fixtures:load --fixtures="src/AppBundle/DataFixtures/Seeds" --no-interaction
    else
        php bin/console doctrine:fixtures:load --fixtures="src/AppBundle/DataFixtures/Fixtures" --no-interaction
    fi
fi