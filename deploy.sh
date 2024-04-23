#!/bin/bash

# Define the file to store the version number
VERSION_FILE="/var/www/IT490_Fitness_App/deployment/version.txt"

# Check if the version file exists
if [ ! -f "$VERSION_FILE" ]; then
    # If the version file doesn't exist, create it and set the initial version number to 1
    echo "1" > "$VERSION_FILE"
fi

# Read the current version number from the file
CURRENT_VERSION=$(cat "$VERSION_FILE")

# Increment the version number
NEXT_VERSION=$((CURRENT_VERSION + 1))

# Define the deployment package name with the consecutive version number
DEPLOYMENT_PACKAGE="files_$NEXT_VERSION.zip"
DEPLOYMENT_PACKAGES_DIR="/var/www/IT490_Fitness_App/deployment"

# Step 1: Create the deployment package
zip -r "$DEPLOYMENT_PACKAGE" /var/www/IT490_Fitness_App/*

# Step 2: Copy the deployment package to the deployment directory
cp "$DEPLOYMENT_PACKAGE" "$DEPLOYMENT_PACKAGES_DIR"

# Step 3: Unzip the deployment package
unzip -o "$DEPLOYMENT_PACKAGES_DIR/$DEPLOYMENT_PACKAGE" -d "$DEPLOYMENT_PACKAGES_DIR"

# Step 4: Perform deployment tasks (e.g., SCP to destination IP)
scp "$DEPLOYMENT_PACKAGES_DIR/$DEPLOYMENT_PACKAGE" "$REMOTE_USERNAME@$DESTINATION_IP:/var/www/IT490_Fitness_App/deployment"

# Step 5: Output success message
echo "Deployment to $DESTINATION_IP completed successfully"

# Update the version number file with the next version
echo "$NEXT_VERSION" > "$VERSION_FILE"


