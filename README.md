Sendy Prestashop newsletter module
=============

This module is compatible with Prestashop 1.5+ (tested on 1.5.6).

This is a module simmilar in apperance to Prestashop's native newsletter module. The big difference is that this module saves subscribers directly to your Sendy instalation.

Module can be hooked either to the left or the right column, it uses a combination of AJAX and PHP curl to smoothly add a subscriber to the list and provide feedback, but also falls back gracefully if user happens to have JavaScript disabled.

Features
---------

* AJAX powered sign up form
* You can choose to ask the subscriber to provide their name or not and make it optional or required
* You can collect the subscribers IP addreses, as they might be required by local law
* Sendy installation does not have to be on the same server

Set up requirements
-------------------

* You will need to have PHP curl extension enabled
* If your server uses a firewall, you will need to allow the connection to the server that  your Sendy is installed on


How to install
--------------

* Download this repository
* Repack the `sendynewsletter` folder
* Upload it in your Prestashop backoffice in the Modules section
* Alternatively upload the `sendynewsletter` folder directly to your `modules` folder