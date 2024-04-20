#!/bin/bash

# rollout-qa.sh

version=1
cd /var/www/IT490_Fitness_App/deployment/
while : 
do
    if [ ! -d "version$version" ]; then
        mkdir "version$version"
        cd version$version
        
        # Create version.txt if it doesn't exist and write the version number
        if [ ! -f version.txt ]; then
            echo "$version" > version.txt
        fi
        
        # Create status.txt if it doesn't exist
        touch status.txt
        
        # Copy files to deployment directory
        cp -r /var/www/IT490_Fitness_App/* /var/www/IT490_Fitness_App/deployment/version$version/
        
        # Validation checks
        if [ -f /var/www/IT490_Fitness_App/deployment/version$version/index.php ]; then
            echo "Deployment to QA successful"
            # Mark the package as good
            echo "Good" > /var/www/IT490_Fitness_App/deployment/version$version/status.txt
        else
            echo "Deployment to QA failed"
            # Mark the package as bad
            echo "Bad" > /var/www/IT490_Fitness_App/deployment/version$version/status.txt
        fi
        
        break
    else
        let "version=version+1"
    fi
done

