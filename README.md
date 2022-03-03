# EU_HFR_Node__WebForm
PHP scripts for the data entry web form for managing HFR network information within the EU HFR Node.

These application is written in PHP language and is based on a MySQL database where the information about HFR networks, stations and data inserted in the web form are stored. The application is designed for High Frequency Radar (HFR) data management according to the European HFR node processing workflow that generates radial and total velocity files in netCDF format according to the European standard data and metadata model for near real time HFR current data.

The webform allows for:
- creating an account for managing HFR networks and related stations;
- managing the accounts (e.g. changing password, requesting the access to other HFR networks, etc);
- creating HFR network entries;
- inserting information about the HFR networks;
- creating HFR station entries related to HFR networks;
- inserting information about the HFR stations.

The fields of the webform request information based on the metadata of the European common data and metadata model defined in the Jerico-Next deliverable D5.14 (http://www.jerico-ri.eu/download/jerico-next-deliverables/JERICO-NEXT-Deliverable_5.14_V1.pdf).


The information inserted in the webform are stored into the MySQL database, that then feeds the Matlab applications responsible for the generation of radial and total velocity files in netCDF format according to the European standard data and metadata model for near real time HFR current data.

Cite as:
Lorenzo Corgnati. (2018). EU_HFR_NODE_WebForm. Zenodo. https://doi.org/10.5281/zenodo.3460422



Author: Lorenzo Corgnati

Date: November 20, 2018

E-mail: lorenzo.corgnati@sp.ismar.cnr.it
