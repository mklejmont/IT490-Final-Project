#!/bin/bash

# Function to create package
create_package() {
    # Define directory to package
    SOURCE_DIR="/var/www/IT490_Fitness_App"
    # Define initial version number
    VERSION=1

    PACKAGE_NAME="app_${VERSION}.zip"
    zip -r "$PACKAGE_NAME" "$SOURCE_DIR"

    # Increment version number
    ((VERSION++))
}

# Function to deploy package to QA machine
deploy_to_qa() {
    read -p "Enter QA machine IP address: " QA_IP
    read -p "Enter SSH username: " SSH_USER
    SSH_KEY="~/.ssh/id_rsa"

    # Copy package to QA machine
    scp -i "$SSH_KEY" "$PACKAGE_NAME" "$SSH_USER@$QA_IP:/tmp"

    # SSH into QA machine and install package
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" "unzip -o /tmp/$PACKAGE_NAME -d /tmp && sudo mv /tmp/var/www/IT490_Fitness_App/* /var/www/IT490_Fitness_App"
}


# Function to mark package as bad
mark_as_bad() {
    echo "app_${VERSION}.zip: BAD" >> deployment_status.txt
}

# Function to rollback to last good version
# Function to rollback to last good version
rollback() {
    # Check if the directory exists on the remote machine
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" '[ -d /tmp/IT490_Fitness_App/ ] && echo "Directory exists" || echo "Directory does not exist"'

    # Check if there are files in the directory on the remote machine
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" '[ "$(ls -A /tmp/IT490_Fitness_App/)" ] && echo "Files exist" || echo "No files in directory"'

    # If both checks pass, proceed with rollback
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" '[ -d /tmp/IT490_Fitness_App/ ] && [ "$(ls -A /tmp/IT490_Fitness_App/)" ] && sudo mv /var/www/IT490_Fitness_App/* /tmp && unzip /tmp/$LAST_GOOD_VERSION -d /tmp && sudo mv /tmp/IT490_Fitness_App/* /var/www/IT490_Fitness_App || echo "Error: Directory does not exist or no files to move"'
}


# Function to deploy fix to both QA and production
deploy_fix() {
    read -p "Enter production machine IP address: " PROD_IP
    read -p "Enter SSH username: " SSH_USER

    SSH_KEY="~/.ssh/id_rsa"

    # Copy package to production machine
    scp -i "$SSH_KEY" "$PACKAGE_NAME" "$SSH_USER@$PROD_IP:/tmp"

    # SSH into production machine and install package
    ssh -i "$SSH_KEY" "$SSH_USER@$PROD_IP" "unzip /tmp/$PACKAGE_NAME -d /tmp && sudo mv /tmp/IT490_Fitness_App/* /var/www/IT490_Fitness_App"

    # Deploy to QA machine as well
    deploy_to_qa
}

# Main script execution

# Create package
create_package

# Deploy to QA
deploy_to_qa

# Mark package as bad (if needed)
# mark_as_bad

# Rollback to last good version (if needed)
# rollback

# Deploy fix to both QA and production
# deploy_fix

# Sudoers configuration (commented out)
# mklejmont ALL=(ALL) NOPASSWD: /bin/mv, /usr/bin/unzip, /bin/cp


