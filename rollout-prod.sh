#!/bin/bash

# rollout-prod.sh

echo "Which version would you like to push to production?"
read version

cd /var/www/IT490_Fitness_App/deployment/version$version/

# Create version.txt if it doesn't exist and write the version number
if [ ! -f version.txt ]; then
    echo "$version" > version.txt
fi

# Create status.txt if it doesn't exist
touch status.txt

# Copy files to production directory
cp -r /var/www/IT490_Fitness_App/deployment/version$version/* /var/www/IT490_Fitness_App/

# Validation checks
if [ -f /var/www/IT490_Fitness_App/index.php ]; then
    echo "Deployment to Production successful"
else
    echo "Deployment to Production failed"
fi

