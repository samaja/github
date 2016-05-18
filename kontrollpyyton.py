#!/usr/bin/python
#-*- coding: utf-8 -*- 

#Autor: Jaak Alas A21
#Python KT

#Kasutatud materjal: http://stackoverflow.com/questions/14693646/writing-to-csv-with-python-adds-blank-lines
#https://docs.python.org/2/library/random.html
#http://stackoverflow.com/questions/2782229/most-lightweight-way-to-create-a-random-string-and-a-random-hexadecimal-number
#http://stackoverflow.com/questions/20347766/pythonically-add-header-to-a-csv-file
#http://stackoverflow.com/questions/16503560/read-specific-columns-from-csv-file-with-python-csv


import sys
import random
import string
import os
import csv

#Sisendite kontroll
if len(sys.argv) == 3:
    sisend_fail = sys.argv[1]
    v2ljund_fail = sys.argv[2]
else:
    filename = os.path.basename(__file__)
    print "K2ivita skript nii - ./"+filename+" [sisendfail] [v2ljundfail]"
    exit(1)
    
#sisendi avamine ja sisse lugemine
failis = open(sisend_fail, 'r')    
tudengid = failis.readlines()[1:]


#csv faili avamine ja p2ise kijurtjamine
with open(v2ljund_fail, 'wb') as csvfail:
    header = ['kasutajanimi', 'nimi', 'email', 'token']
    writer = csv.DictWriter(csvfail, fieldnames=header)
    writer.writeheader()
    writer.writerow = ['kasutajanimi', 'nimi', 'email', 'token']
    
#sisendi infot88tlus
for line in tudengid:
    rv = line.strip().split()
    if len(rv) == 0:
      continue
    a_eesnimi = str(rv[1])
    a_perenimi = str(rv[2])
    a_email = a_eesnimi + '.' + a_perenimi + "@itcollege.ee"
    a_kasutajanimi = a_eesnimi[:1] + '.' + a_perenimi 
    a_nimi = a_eesnimi + " " + a_perenimi 
    random_valik = [random.choice(string.ascii_letters + string.digits + "-_") for n in xrange(20)]
    a_token = "".join(random_valik)
    writer=csv.writer(open(v2ljund_fail, 'a'))
    writer.writerow([a_kasutajanimi.lower(), a_nimi, a_email.lower(), a_token])
      
             





       
