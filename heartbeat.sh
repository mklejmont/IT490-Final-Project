#!/bin/bash

# Define the IP address or hostname of the primary machine
PRIMARY_IP="10.243.158.229"

# Define the port to check for connectivity (e.g., SSH port)
PORT=22

# Function to check the status of the primary machine
check_primary_status() {
    # Use a simple network connectivity check (e.g., ping) to test reachability
    ping -c 1 -W 1 $PRIMARY_IP >/dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo "Primary machine is reachable."
        return 0  # Success
    else
        echo "Primary machine is unreachable."
        return 1  # Failure
    fi
}

# Function to synchronize files from the primary machine to the hot standby

# Main loop to periodically check the status of the primary machine
while true; do
    if check_primary_status; then
        # Primary machine is reachable, sleep for a short interval
        sleep 10  # Adjust the interval as needed
    else
        # Primary machine is unreachable, trigger failover process
        echo "Primary machine is down. Initiating failover..."
        
        break  # Exit the loop to avoid continuous failover attempts
    fi
done
