#!/bin/bash

#Autor: Jaak Alas

#Skript väljastab kõikvõimalikud pangakaartide PIN-koodid



export LC_ALL=C



#Juurkasutaja õiguste kontroll

if [ $UID -ne 0 ]

then

    echo "Sisene root õigustega!"


fi



#Muutuja arvu kontroll

if [ $# -eq 1 ]

then

    PIN=$1
    
    sleep 1

else


# Muutuja puudumisel for tsükkel mis väljastab kõikvõimalkud Pin-Koodid.

    for i in {0000..9999}; do 

        echo $i; 
    
    exit 1    

   done
    

fi

# For tsykkliga kõik 4 - kohalised pinnid läbi

for i in $( seq -w 0000 9999 ); do

# Kontroll kas PIN on kasutaja sisendiga sama

if [ $PIN -eq "$i" ]; then

# Kausta kontroll

  test -d $PIN

  if [ $? = 0 ]; then

            echo "Kaust juba olemas"

     else

           mkdir $PIN

           touch $PIN/koodid.txt

   fi

fi
   
# Faili kirjutamine

if [ $i -gt $PIN ]; then

    echo $i >> $PIN/koodid.txt

fi 
done

#Faili otsimine ja pakkimine kui vaja

NOW=$(date +'%Y-%m-%d') 
FAIL="$NOW.tar.gz"
SCRIPT=$(readlink -f "$0")

find $FAIL

if [ $? = 0 ]; then

	exit 2
else
	tar -zcvf $FAIL $SCRIPT
fi	


