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

    # Check if the destination directory exists, and if so, remove it
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" "if [ -d '/var/www/IT490_Fitness_App' ]; then sudo rm -rf /var/www/IT490_Fitness_App; fi"

    # SSH into QA machine and install package
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" "unzip -o /tmp/$PACKAGE_NAME -d /tmp && sudo mv -f /tmp/var/www/IT490_Fitness_App /var/www"
}


# Function to mark package as bad
mark_as_bad() {
    local package_name="$1"
    echo "$package_name: BAD" >> deployment_status.txt
}

# Function to rollback to last good version
rollback() {

    LAST_GOOD_VERSION=$(grep -v -e "BAD" -e "FAIL" deployment_status.txt | tail -n 1 | cut -d ' ' -f 1)
    if [ -z "$LAST_GOOD_VERSION" ]; then
        echo "No working version available to rollback to."
        return 1
    fi
    # SSH into QA machine and rollback
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" "sudo rm -rf /var/www/IT490_Fitness_App/* && unzip /tmp/$LAST_GOOD_VERSION -d /tmp && sudo mv -f /tmp/var/www/IT490_Fitness_App/* /var/www/IT490_Fitness_App"
}

# Function to deploy fix to both QA and production
deploy_fix() {
    read -p "Enter production machine IP address: " PROD_IP
    read -p "Enter SSH username: " SSH_USER

    SSH_KEY="~/.ssh/id_rsa"

    # Copy package to production machine
    scp -i "$SSH_KEY" "$PACKAGE_NAME" "$SSH_USER@$PROD_IP:/tmp"

    # SSH into production machine and install package
    ssh -i "$SSH_KEY" "$SSH_USER@$PROD_IP" "unzip -o /tmp/$PACKAGE_NAME -d /tmp && sudo mkdir -p /var/www/IT490_Fitness_App && sudo mv -f /tmp/var/www/IT490_Fitness_App/* /var/www/IT490_Fitness_App"

    # Deploy to QA machine as well
    deploy_to_qa
}

# Function to create a new version
create_new_version() {
    read -p "Enter version number: " VERSION_NUMBER
    PACKAGE_NAME="site_version${VERSION_NUMBER}.zip"
    zip -r "$PACKAGE_NAME" "/var/www/IT490_Fitness_App"
    echo "$PACKAGE_NAME: GOOD" >> deployment_status.txt
    deploy_to_qa
}

# Function to rollback to a working version
rollback_to_working_version() {
    local QA_IP
    local SSH_USER
    local SSH_KEY="~/.ssh/id_rsa"
    local SOURCE_IP="10.0.2.15"  
    local SOURCE_DIR="/var/www/IT490_Fitness_App/deployment"  


    # Prompt user for QA machine IP address and SSH username
    read -p "Enter QA machine IP address: " QA_IP
    read -p "Enter SSH username: " SSH_USER

    LAST_GOOD_VERSION=$(grep -v -e "BAD" -e "FAIL" deployment_status.txt | tail -n 1 | cut -d ' ' -f 1 | sed 's/:$//')
    if [ -z "$LAST_GOOD_VERSION" ]; then
        echo "No working version available to rollback to."
        return 1
    fi

    echo "Rolling back to version: $LAST_GOOD_VERSION"

    # Transfer the specific zip file from the source machine to the QA machine
    scp -i "$SSH_KEY" "$SSH_USER@$SOURCE_IP:$SOURCE_DIR/$LAST_GOOD_VERSION" "/tmp/$LAST_GOOD_VERSION"

    # SSH into QA machine and rollback
    ssh -i "$SSH_KEY" "$SSH_USER@$QA_IP" "sudo rm -rf /var/www/IT490_Fitness_App/* && unzip -o /tmp/$LAST_GOOD_VERSION -d /tmp && sudo mv -f /tmp/var/www/IT490_Fitness_App/* /var/www/IT490_Fitness_App"

    # Rollback on the source machine
    ssh -i "$SSH_KEY" "$SSH_USER@$SOURCE_IP" "unzip -o $SOURCE_DIR/$LAST_GOOD_VERSION -d $SOURCE_DIR && sudo mv -f $SOURCE_DIR/* /var/www/IT490_Fitness_App"
}






# Function to mark a version as bad
mark_version_as_bad() {
    local package_name="$1"
    sed -i "/$package_name/d" deployment_status.txt  # Remove any existing entry for this package
    echo "$package_name: BAD" >> deployment_status.txt
}

# Main script execution
echo "Choose an action:"
echo "1. Create a new version"
echo "2. Rollback to a working version"
read -p "Enter your choice: " CHOICE

case $CHOICE in
    1)
        create_new_version
        ;;
    2)
        rollback_to_working_version
        ;;
    *)
        echo "Invalid choice. Exiting."
        exit 1
        ;;
esac

