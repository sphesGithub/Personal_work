#!/bin/bash

# Source directory (files to be backed up)
SOURCE_DIR="utili"
# Destination directory (backup location)
BACKUP_DIR="backup/vault/users"
# Log file to record backup operations
LOG_FILE="backup/vault/log/backup.log"

# Backup frequency (daily, weekly, monthly)
FREQUENCY="$1"

# Validate input parameter
if [[ "$FREQUENCY" != "daily" && "$FREQUENCY" != "weekly" && "$FREQUENCY" != "monthly" ]]; then
    echo "Usage: $0 <daily|weekly|monthly>"
    exit 1
fi

# Function to perform backup
backup_files() {
    echo "Starting backup at $(date)" >> "$LOG_FILE"
    rsync -av --delete "$SOURCE_DIR" "$BACKUP_DIR" >> "$LOG_FILE" 2>&1
    echo "Backup completed at $(date)" >> "$LOG_FILE"
}

# Execute backup based on frequency
case "$FREQUENCY" in
    daily)
        # Run daily backup
        backup_files
        ;;
    weekly)
        # Run weekly backup (every Sunday at midnight)
        echo "0 0 * * 0 $0 daily" | crontab -
        ;;
    monthly)
        # Run monthly backup (first day of the month at midnight)
        echo "0 0 1 * * $0 daily" | crontab -
        ;;
esac
