#!/bin/bash

# Define source and destination directories
SOURCE_DIR="/var/www/IT490-Final-Project"
DESTINATION_DIR="/var/www/backup"


# Define the IP address of the standby VM
STANDBY_VM_IP="10.243.127.224"

# Set RSYNC_RSH environment variable
export RSYNC_RSH='ssh -p 22 -i ~/.ssh/Primary_Machine'


# Run rsync command to replicate data
rsync -avz --delete $SOURCE_DIR/* $STANDBY_VM_IP:$DESTINATION_DIR

# Check rsync exit status
if [ $? -eq 0 ]; then
    echo "Replication completed successfully."
else
    echo "Error: Replication failed."
fi
