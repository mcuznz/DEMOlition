DEMOlition
==========

Convert City of Heroes .costume and Titan Sentinel+ XML files into CoH Demorecords!


SET UP
==========

1) The Silex Microframework is required for DEMOlition.  You can either download it
from http://silex.sensiolabs.org/ (the 'fat' version) and unpack it in the project
root (thus creating a 'vendor' folder) or use composer to fetch it from source.

The composer.json file is provided to make this second method easier. In either
case, be sure that web/index.php is not overwritten!


2) Create a Database for storing some light stats.  The file "stats.sql" is provided
so you can set up the necessary table and rows quickly if you're using MySQL.  If
you're using another Database format, please see the stats.sql file for reference.


3) Copy src/config_sample.php to src/config.php and make necessary changes.

The $db_config array defines your database connection parameters.  The 'STAT_TABLE'
constant is the name of the table you'd like your DEMOlition usage stats stored in,
as per your setup in Step 2.


4) When hosting this on a web server, be sure that the 'Document Root' for your
domain or subdomain points to the /web folder - the src, vendor, and views folder
should all exist outside of your browser-accessible folder structure.


5) You'll also need to create a folder called 'tmp', and make sure that it's writable.
Uploaded .costume and .xml files are temporarily moved to this folder, parsed, and
then deleted.


CONTRIBUTING
============

DEMOlition was put together over the course of a few nights, and I didn't put much
effort into styling it.  I'd also like to add more Camera Locations, but haven't had
a chance to dig through other Demorecord files and scout them out yet.  You're more
than welcome to Fork this project, and submit Pull Requests for any changes you'd
like to see implemented.


Long Live City of Heroes!

Mike Cousins / Duck L'Orange
Twitter: @mcuznz / @epicduckstudios
Web:     http://mcuznz.ca
