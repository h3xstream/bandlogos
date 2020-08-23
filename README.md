[BandLogos LastFM](https://github.com/h3xstream/bandlogos/blob/master/demo.png?raw=true)

## Installation
1. Download the [source files](https://github.com/h3xstream/bandlogos)
2. Download the [latest logos](http://bandlogos.descentrecords.com/logos.htm)
3. You need a installation of Apache/PHP5/MySQL/gd2
4. Change the settings in */classes/bd/DBConfig.class.php* and */classes/Config.class.php*
5. Run the SQL script */install.sql*
6. Extract all logos to */logos/* and run */filldb.php*

## Usage
* To add new logos, run filldb.php again
* To update users' banners, clear the table *lastfm\_images\_cache*

## Adding a new layout
Look at the *Layout* interface for detail guidelines.

## Security configuration

Authentication can be added to prevent unwanted calls to utility scripts.

**.htaccess**

Create a file with the following content.

    AuthName "Restricted Area" 
    AuthType Basic 
    AuthUserFile /some/path/.htpasswd 
    AuthGroupFile /dev/null 
    
    <Files filldb.php>
    require valid-user
    </Files>

**.htpasswd**

To create the password file use the following command.

    htpasswd -c .htpasswd adminuser
