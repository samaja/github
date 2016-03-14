#!/bin/bash
# Autor: Jaak Alas
# Grupp: A21
# Kodutöö 1


#Kontroll kas root õigused olemas.

if [ $UID -ne 0 ]
then
    echo "Käivita $0 juurkasutajas"
    exit 1
fi
# Kontroll, kas argumendid on olemas.

KAUST=$1
GRUPP=$2
SHARE=$3


if [ $# -eq 3 ]
then
	SHARE=$3
else

	if [ $# -eq 2 ]
        then
        
            SHARE=$(basename $KAUST)

else 
    echo "Viga, pole piisavalt parameetreid. Käivitada kaust grupp"

#eq 2 lõpp
fi
#eq 3 lõpp
fi

echo "Skript õnnestus siiamaani. Jagab välja kausta $SHARE, grupile $GROUP."

# Kontroll kas grupp on olemas. Kui mitte, siis luuakse.

getent group $GRUPP

if [ $? -eq 0 ]
then
    echo "Grupp on olemas"

	else
		addgrp $GRUPP
    
   fi 

# Kontroll kas kaust on olemas. Kui mitte, siis luuakse. 

test -d $KAUST || mkdir -p $KAUST || exit

# Määrame kausta grupiks etteantud grupi.

chgrp $GRUPP $KAUST


# Anname grupi õigused konkreetsele kaustale.

chmod g+ws $KAUST


# Samba paigalduse kontroll. Kui pole paigaldatud, siis installitakse.

dpkg -s samba

if [ $? -ne 0 ];
then
echo "Samba paigaldamine"

	apt-get install samba -y	

fi

# Konfi-faili varukoopia enne muutmist
	cp /etc/samba/smb.conf /etc/samba/smb_backup.conf
	
# Samba conf. faili muutmine (/etc/samba/smb_backup.conf)

cat >> /etc/samba/smb_backup.conf << EOF

[$SHARE]
    comment=share folder
    path=$KAUST
    writable=yes
    valid users=@$GRUPP
    force group=$GRUPP
    browsable=yes
    create mask=0664
    directory mask=0775
EOF

# Testime testparm -s käsu abil

	testparm -s /etc/samba/smb_backup.conf


# Juhul kui on ok, siis teeme reloadi
	if [ $? -ne 0 ];
then
	echo "Vigane confi-fail."
	exit 1;
else
		cp /etc/samba/smb_backup.conf /etc/samba/smb.conf
			echo "Kõik ok, reloadin"
	sudo /etc/init.d/smbd reload
fi
	

