import os
import fnmatch
for filename in os.listdir("."):
  if fnmatch.fnmatch(filename, '*.php'):
    print("Whcrossing"+filename[6:])
    os.rename(filename, "Whcrossing"+filename[6:])
