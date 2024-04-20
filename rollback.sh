#!/bin/bash

# rollback.sh

echo "Which environment would you like to roll back (QA or Production)?"
read environment

echo "Rolling back to the last known good version..."

# Find the last known good version
last_good_version=$(ls -t /var/www/IT490_Fitness_App/deployment/version* | xargs -I {} grep -l "Good" {} | head -n 1 | sed 's/.*version\([0-9]*\).*/\1/')

if [ -z "$last_good_version" ]; then
    echo "No known good version found. Rollback failed."
else
    echo "Rolling back to version $last_good_version"
    
    cd /var/www/IT490_Fitness_App/deployment/version$last_good_version/

    # Create version.txt if it doesn't exist and write the version number
    if [ ! -f version.txt ]; then
        echo "$last_good_version" > version.txt
    fi

    # Create status.txt if it doesn't exist
    touch status.txt
    
    # Rollback to the last known good version
    if [ "$environment" = "QA" ]; then
        cp -r /var/www/IT490_Fitness_App/deployment/version$last_good_version/* /var/www/IT490_Fitness_App/
    elif [ "$environment" = "Production" ]; then
        cp -r /var/www/IT490_Fitness_App/deployment/version$last_good_version/* /var/www/IT490_Fitness_App/
    else
        echo "Invalid environment. Rollback failed."
    fi
fi

