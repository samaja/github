#!/bin/bash

#Autor: Jaak Aals

#Script väljastab kõik võimalikud pangakaartide PIN-koodid



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

else


#Muutuja puudumisel for tsükkel mis väljastab kõikvõimalkud Pin-Koodid.

    for i in {0000..9999}; do 

        echo $i; 

    done


fi

 

