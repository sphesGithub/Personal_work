#!/bin/bash

# Function to disable unnecessary services based on systemctl list-units
disable_unnecessary_services() {
    local services_file="$1"
    if [ ! -f "$services_file" ]; then
        echo "Error: Services file not found: $services_file"
        exit 1
    fi

    echo "Disabling unnecessary services..."
    while IFS= read -r service; do
        # Disable and stop the service
        sudo systemctl disable "$service"  # Adjust for your init system if needed
    done < "$services_file"
}


# Function to enforce strong passwords
enforce_strong_passwords() {
    echo "Enforcing strong passwords..."
    sudo apt-get install -y libpam-pwquality
    sudo sed -i 's/password    requisite    pam_cracklib.so.*/password    requisite    pam_cracklib.so retry=3 minlen=10 difok=3 ucredit=-1 lcredit=-1 dcredit=-1 ocredit=-1 minclass=2/' /etc/pam.d/common-password
}

# Path to the services file
services_file="utili/services_file.txt"

# Execute security best practices functions
disable_unnecessary_services "$services_file"  # Replace "services.txt" with your services file
enforce_strong_passwords
