=== Pathpic ===
Contributors: jlscwpplugins
Donate link: 
Tags: paths, image, text, pic, generate, Windows, Linux.
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 0.8.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show path lists as an image or a tree of text representing the path hierarchies with a file system manager's style.  

== Description ==

### What to do? ###

Use a list of paths and produces a pic representing a hierarchy of paths (which can be a PNG image or tree
text) of a File Manager Windows or Linux.

### Why use it? ###
You can save time because you avoid that you have to capture the image of the paths in your File Manager,
save it, upload it and set it in Wordpress.

You will have available in your articles your paths in text format.
  
If you have installed Windows and you need to show your style paths with Linux File Manager you can
do with this plugin. The same case but Linux to Windows is also considered.

### How to use it? ###
You just need to write the paths you want to generate using the directory separators File System
correct, ie if you go to enter paths in the form of Linux, you will have to use as the directory separator
/ (Forwardslash) or if you're using Windows format you should use the \\ (backslash).

You will have to configure the visual characteristics of the pic choosing the graphical mode or text and style
is available for each mode.

In the shortcode's final indicator making sure that I not contain any spaces before ]  
  
Correct :  
[/pathpic]
  
Incorrect  
[/pathpic ] (see the space between pathpic and ])  

### Where to use?  ###
Once installed on wordpress plugin, you could use in your articles.  

### Parameters:  ###

1. style  
Select which style will have the pic generated from the list of paths used.
These are the available values:  
>> MODE-->VALUE--------->DESCRIPTION  
>> image->W7------------>Windows 7  
>> image->XP------------>Windows XP  
>> image->Ubuntu-------->Ubuntu  
>> image->Netbeans------>Netbeans 7  
>> text-->TotalTerminal->Mac  
>> text-->Guake--------->Ubuntu o Gnome  
>> text-->Yaquake------->Ubuntu o Gnome   
>
> Example: style = "TotalTerminal"  
  
1. mode  
Indicates the visual format of the pic. The available values are: image and text.
Example: mode = "text"  

1. os  
Indicates the format of the paths according to the operating system. The available values are: Linux and Windows.  
  
1. files  
It is a sequence of numbers separated by commas referring to the number of row in the list of paths
and will be shown as the output file. Note that the row numbers start at 0 (zero).
Example: files = "0,1,2"  

1. highlights  
It is a sequence of numbers separated by commas referred to the row number list
paths and Seru shown highlighted in the output. Note that the row numbers start at 0 (zero).
Example: highlights = "2"  

### Requeriments  ###

1. In php set buffer_output directive to 'On'.  
1. Library GD2 installed.   
1. Allowing to use of base64 encoding.  

== Installation ==

###Uploading The Plugin###

The quickest method for installing the importer is:

1. Visit Tools -> Import in the WordPress dashboard
1. Click on the WordPress link in the list of importers
1. Click "Install Now"
1. Finally click "Activate Plugin & Run Importer"

Extract all files from the ZIP file, **making sure to keep the file/folder structure intact**, and then upload it to   
`/wp-content/plugins/`.

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

###Plugin Activation###

Go to the admin area of your WordPress install and click on the "Plugins" menu.  
Click on "Activate" for the "Pathpic" plugin.

###Plugin Usage###

Just wrap your path(s) in `[pathpic]`, such as  
`[pathpic params]  
path(s) here  
[/pathpic]`  
  
Sample 1 (os="Linux")  
`[pathpic style="Ubuntu" mode="image" os="Linux" files="2,6" highlights="2"]  
myProject/source/com  
myProject/source/wickedlysmart  
myProject/source/wickedlysmart/MyClass.java  
myProject/classes  
myProject/classes/com  
myProject/classes/wickedlysmart  
myProject/classes/wickedlysmart/MyClass.class  
[/pathpic]  `
  
Sample 2 (os="Windows")  
`[pathpic style="TotalTerminal" mode="text" os="Windows" files="0,1,2" highlights="2"]  
trunk\wp-admin\user\admin.php  
trunk\wp-admin\user\index-extra.php  
trunk\wp-feed.php  
[/pathpic]`  
  
== Frequently Asked Questions ==

= I write the paths, but it only shows one path directory? =
Review that the directory separator corresponds with the 'os' parameter, backslash for Windows and forwardslash  
for Linux.   


== Screenshots ==

1. Sample 1 with style="Ubuntu" mode="image" os="Linux".
2. Sample 1 with style="TotalTerminal" mode="text" os="Linux".
3. Sample 2 with style="Netbeans" mode="image" os="Windows".
4. Sample 2 with style="TotalTerminal" mode="text" os="Windows".

== Changelog ==

= 0.8.1 =
* First release.

== Upgrade Notice ==
* No comments yet.