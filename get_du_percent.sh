#!/bin/bash
result=`df -h |sed -n '2p'|awk '{print $5}'|cut -f 1 -d '%'`
echo $result

