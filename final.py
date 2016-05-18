#!/usr/bin/python
#-*- coding: utf-8 -*- 
#Antud skript kuvab k2surealt etteantud kaustast failid, mis on vanemad kui etteantud faili vanus p2evades.


# Moodulid

import os
import time
import sys


#Sisendite kontroll
if len(sys.argv) == 3:
    xdays = int(sys.argv[1])
    path  = sys.argv[2]
else:
    filename = os.path.basename(__file__)
    print "K2ivita skript nii - ./"+filename+" [p2evade arv] [soovitud kataloog]"
    exit(1) 
     
# Muutujad
xdays = int(sys.argv[1])
path  = sys.argv[2]
aeg   = time.time()          
 
# Vanemad failid kui xdays
print "Failid kaustas " + path + ", mis on vanemad kui" + ' ' + str(xdays) + " päeva."
print "==========================" 
for root, dirs, files in os.walk(path):
  for name in files:
    filename = os.path.join(root, name)
    if os.stat(filename).st_mtime < aeg - (xdays * 86400):
      print(filename) 

