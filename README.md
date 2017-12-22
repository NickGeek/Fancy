# Fancy
#### Fancy is a drop-in system that lets you take your static HTML code and add dynamically editable code.
Unlike other CMSs (like WordPress) you don't have to design your website around Fancy. You can design the website as if it was only ever going to be updated by manually editing the code. Fancy will then do all of the heavy lifting. Giving your clients a powerful editor to update their website. Hassle free. Not only is the editor super easy to use; it gets out of your way when you want to do more advanced work.

Go to https://nick.geek.nz/fancy for more information.

# Demo
You can view a live demo at [https://nick.geek.nz/fancy/demo](http://fancyxht.ml/demo) and view its dashboard at [https://nick.geek.nz/fancy/demodash](https://nick.geek.nz/fancy/demodash).

# Install
1. Extract the 'dashboard' folder from 'fancy-master.zip'

	a. If you used `git clone` to download, just copy the 'dashboard' directory
2. Upload your dashboard folder onto a webserver that can run PHP and MySQL
3. Create a MySQL compatible database for Fancy to use

	a. As of v2100 Fancy requires at least a database compatible with MySQL 5.6.5 or MariaDB 10.0.1
4. Go to the dashboard folder in a web browser
5. Follow the steps on screen

# Updating
1. Extract the 'dashboard' folder from 'fancy-master.zip'
	
    a. If you used `git clone` to download, just copy the 'dashboard' directory
2. Paste the 'dashboard' folder you extracted and put it on your webserver
	a. If you get asked you want to overwrite old files
3. Delete the createConfig.php file
4. You are now running a newer version of Fancy!

## Updating the API
While the Fancy dashboard is backwards compatible to Fancy API v1000, for access to newer features you might need to update the version of the API you are accessing.

If an update script exists for the update you wish to do, just click on the "Logged In" menu and underneath the Fancy API version number there should be an update button.

**REMEMBER TO UPLOAD THE NEW `FancyConnector.php` AND `settings.php` FILES TO YOUR SITES AFTER AN UPDATE**
