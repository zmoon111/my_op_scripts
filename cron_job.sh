#!/bin/bash 
for line in `cat all_hosts_gpu`
do 
   percent=`ssh $line 'bash  /root/get_du_percent.sh'` 
   if [ $percent -gt 80 ]
   then
       echo -e "服务器：[$line]，home空间剩余不足20%, 请相关同事关注.\n" > mail_1
       echo -e "\n-------------------------------------------------\n" > mail_2
       ssh $line 'bash /root/get_home_disk_usage.sh' >> mail_2
       echo -e "\n-------------------------------------------------\n" >> mail_2
       cat mail_1 mail_2 > mail_content
       php ./mail/send_content.php --content=$PWD/mail_content
   fi
done
