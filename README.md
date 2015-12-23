# dirList-JSON-Hash
dirList-JSON-Hash is Command line tool for directory listing with JSON output format, hash(MD5,CRC32,SHA1,SHA256,SHA512) file support and file information. Usefull for generate list of update file for application updater.

# Feature
* Recursive Directory Listing
* JSON Output Format
* Beautiful JSON Output
* Fast
* Full mime type detection
* URL Prefix for Updater

# How To Use
Simply just run php dirList-JSON-Hash.php and it will show help for you

# Changelog

v1.1 - 14/12/2015
- Update Directory Listing system
- Remove Full PATH Directory
- Set Root Directory from Last PATH
- Add URL Prefix Mode

v1.1.1 - 21/12/2015
- Update filename to full Path Filename because it will not generate list if same filename (not accurate)
- Exclude Directory from scanning

v1.1.2 - 22/12/2015

- Update exclude directory from scanning 

v1.1.3 - 23/12/2015

- Add Progres file debug support

v1.1.4 24/12/2015

- Remove last directory path from JSON location
