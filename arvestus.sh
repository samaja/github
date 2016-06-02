#!/bin/bash

#Autor: Jaak Alas A21
#Arvestustöö

#Skript saab käsurealt 3 parameetrit: 1. Sisendfail 2. Väljundfail 3. Otsitav 

# exit koodid:

# exit 1 sisendfail ei ole loetav
# exit 2 väljundfaili ei saa kirjutamiseks avada
# exit 3 ei sisestatud täpselt kolm parameetrit
#
#Kasutatud materjal:
#http://unix.stackexchange.com/questions/190814/read-lines-and-match-against-pattern
#http://stackoverflow.com/questions/11287861/how-to-check-if-a-file-contains-a-specific-string-using-bash
#http://stackoverflow.com/questions/2578116/how-can-i-test-if-line-is-empty-in-shell-script
#http://unix.stackexchange.com/questions/190814/read-lines-and-match-against-pattern
#http://ss64.com/bash/find.html

export LC_ALL=C

if [ $# -ne 3 ]; 
	
	then
	
	echo "Kasuta skripti: $(basename $0) SISENDFAIL VÄLJUNDFAIL OTSITAV"
    
    exit 3
fi

#Muutujad

SISENDF=$1
VALJUNDF=$2
OTSITAVS=$3

#Kas fail eksisteerib

if [ ! -f $SISENDF ]; 

	then

	
	echo "Faili ei leitud!"

fi

#Kas fail on loetav ja fail. Kui mitte exit 1.

test -r $SISENDF

if [ $? -ne 0 ]; 

	then

	echo "Sisendfaili ei saa lugeda"

	exit 1

fi	

#Kas väljundfail olemas. Kui mitte siis luuakse.

if [ -f $VALJUNDF ]; 

	then

	VALJUNDF=$VALJUNDF\_$(date +'%Y-%m-%d-%H-%M')
	
	touch $VALJUNDF

fi

#Väljundi kirjutamine. Kui ei onnestu siis exit 2.

test -w $VALJUNDF

if [ $? -ne 0 ]; 

	then

	echo "Väljundit ei saa kirjutada"
	
	exit 2

fi

#Väljundfaili esimesele reale kirjutatakse 3. parameetrina antud string.

echo $OTSITAVS > $VALJUNDF

#Sisendi sisselugemine

while read line; 

do

#Kas sisendist loetud reale vastab fail/kaust
    
 if [[ -e $line ]]

  then

  if [[ -f $line ]]
 
  #Kui loetud rida on failinimi
    then

#3.parameetrina antud stringi järgi otsing

	 grep '$line' $OTSITAVS

      if [ $? -eq 0 ]

	    then

	        echo "$line,OLEMAS" >> $VALJUNDF
	    else

	        echo "$line,POLE OLEMAS" >> $VALJUNDF
	  
	  fi
         
         else

	   #Stringi järgi kaustade ja failide otsing

	      find $line -iname $OTSITAVS 

	       if [ $? -eq 0 ]
	   			
	   			then

		 echo "$line,OLEMAS" >> $VALJUNDF
	    
	      else
 
			echo "$line,POLE OLEMAS" >> $VALJUNDF
	       
	       fi

	fi

       else

    #Rida pole fail/kaust

    echo "$line,POLE OLEMAS" >> $VALJUNDF
    
    fi

done < "$SISENDF"


