#!/bin/bash 
result=`du --max-depth=1 /home/ | sort -rn -k 1`
echo -e  "$result"

