#!/usr/bin/python
# -*- coding: utf-8 -*-
#Autor: Jaak Alas 
#Rühm A21
#Python kodutöö.
#Kirjeldus: antud skript on tehtud puhtalt õppeesmärkidel ja reaalne funktsionaalsus tal puudub.
#Kasutatud kirjandus:
#http://enos.itcollege.ee/~mernits/python/python-algkursus-v6-2012-fall.odp
#http://www.saltycrane.com/blog/2010/04/options-listing-files-directory-python/ 
#http://pythoncentral.io/series/python-recursive-file-and-directory-manipulation/
#https://docs.python.org/2/library/subprocess.html?highlight=stdout#subprocess.STDOUT
#http://www.tutorialspoint.com/python/os_walk.htm
#https://docs.python.org/2/library/time.html


#Moodulid
import os
import time
import subprocess



#Muutjujad    
a = "See skript suurt midagi ei tee, kuid siiski kuvab"
b = 0
c = "kaustas olevad failid."
path = '/home/student/Desktop/'


while b <= len(a):
	os.system("clear")
	print (a[:b]) 
	b+=1
	print (path + ' ' + c)
	time.sleep(0.1)
	
# Kõik failid 

print ("Kõik failid " + path + ' ' + "kaustas")
print ("--------------------------") + "-" * len(path)
for root, dirs, files in os.walk(path):
  for name in files:
    filename = os.path.join(root, name)
    print(filename)	



   
    


  
