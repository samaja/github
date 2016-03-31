#!/bin/bash
#Autor: Jaak Alas
#Rühm: A21
#Kodutöö 2
#Kirjeldus:
#Skript paigaldab apache2 serveri, kui see puudub.
#Loob nimelahenduse (/etc/hosts failis).
#Kopeerib vaikimisi veebisaidi ja modifitseerib index.html faili sisu vastavalt loodavale lehele.

#exit koodid:
#exit 1 - Kasutajal puuduvad root oigused.
#exit 2 - Parameetreid ei olnud õiged.
#exit 3 - Veebilehe kodukaust on juba olemas.
#exit 4 - Apache2 konfis tekkis viga.

#Kasutatud materjal:
#http://www.tutorialspoint.com/sed/
#https://wiki.itcollege.ee/index.php/Veebiserveri_labor_v.2


#Õiguste kontroll.
if [ $UID -ne 0 ];
then
    echo "Skripti käivitamiseks on vaja root oiguseid!"
exit 1
fi

#Argumentide kontroll ja muutujasse viimine.

if [ $# -ne 1 ];
then
    echo "Kasuta skripti: $(basename $0) www.mingiaadress.ee"
    exit 2
else
    WEB=$1    
fi

#Kas apache2 on installitud? Kui mitte, siis installitakse.

apt-cache policy apache2 | grep 'Installed: (none)' && apt-get update && apt-get install -y apache2

#Kas hosts failis on rida olemas? Kui mitte, siis lisatakse.

grep "127.0.0.1 $WEB" /etc/hosts || echo "127.0.0.1 $WEB" >> /etc/hosts

#Kodukausta loomine kui seda pole(index.html jaoks).

if [ -d /var/www/$WEB ] ;then
    echo "Veebilehe kodukaust $WEB juba eksisteerib."
    exit 3
else 
mkdir -p /var/www/$WEB/
echo " See on $WEB index.html" > /var/www/$WEB/index.html
fi

#Kopeeritakse vaikimisi konfifail endale sobivaks konfifailiks ja asendatakse Servername ja DocumentRoot.

sed -e "s/#ServerName www.example.com/ServerName $WEB/" -e "s@DocumentRoot /var/www/html@DocumentRoot /var/www/$WEB@" /etc/apache2/sites-available/000-default.conf > /etc/apache2/sites-available/$WEB.conf

#Aktiveeritakse loodud leht.

a2ensite $WEB

#Apache2 reload ja vajadusel katkise konfi tühistamine.

service apache2 reload

if [ $? -ne 0 ]
then   
    echo "Veebilehe loomisel tekkis viga"
    a2dissite $WEB
    service apache2 restart
    exit 4
else
    echo "Veebileht $WEB on loodud"
fi
exit 0
