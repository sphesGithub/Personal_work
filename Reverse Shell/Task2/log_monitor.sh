#!/bin/bash

# Log file to monitor
LOG_FILE="backup/vault/log/auth.log"

# Log file for confirmation messages
CONFIRMATION_LOG="backup/vault/log/monitoring_confirmation.log"
INTRUSION_LOG="backup/vault/log/possible_intrusion.log"

# Define patterns for detecting suspicious activity
PATTERNS=(
    "Failed password"
    "Accepted password for .* from"
    ".* accessed by .*"
    ".* modified by .*"
    ".* changed permissions to .*"
)

# Function to check log file for suspicious activity
check_logs() {
    # Continuously monitor log file for new entries
    tail -n0 -F "$LOG_FILE" | while read -r line; do
        # Loop through patterns
        for pattern in "${PATTERNS[@]}"; do
            # Check if the line matches the current pattern
            if echo "$line" | grep -q "$pattern"; then
                # If a match is found, print a message
                echo "Potential intrusion detected: $line">> "$INTRUSION_LOG"
                break
            fi
        done
        # Append confirmation message to the confirmation log
        echo "Log entry monitored: $line" >> "$CONFIRMATION_LOG"
    done
}

# Execute the function to start monitoring logs
check_logs
