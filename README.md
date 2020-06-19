# PhiloEditor

## Overview
PhiloEditor is a web application which allows to read diverse editions of literary works - currently, <b>I promessi sposi by Alessandro Manzoni</b> and <b>Le Avventure di Pinocchio by Carlo Collodi</b> -, to visualise their diachronic variants as marked by scholars, and to create a personal edition of an available text by assigning specific categories provided by the system to the automatically detected variants. 


## Software dependencies 
<b>PHP 7.4.0</b>.


## Back-end
The back-end of PhiloEditor is entirely developed in PHP (version 7.4.0). 

The available literary works are stored in .txt files, according to a specific tree structure. The root of the tree structure is a folder called <b>sources</b> containing a subfolder for each work provided. The subfolders correspond to the parents. Their name is structured according to a rigid pattern (a number in ascending order starting from 01 + - + name of the specific literary work). Each one of them contains in turn all the sources .txt files, divided in chapters. For each chapter, a child folder is provided. Its name follows the same pattern as the parents folders (a number in ascending order starting from 01 + - + name of the specific chapter). It contains a leaf .txt file for each edition provided. The .txt files names have to follow a rigid ascending order (for instance, 1.txt and 2.txt are respectively the plain text of the Introduzione of I promessi sposi 1827 and the plain text of the Introduzione of I promessi sposi 1840, 3.txt and 4.txt are respectively the plain text of the Capitolo 1 of I promessi sposi 1827 and the plain text of I promessi sposi 1840, and so on).

An example of the structure at hand is provided below.

<ul>
  <li><b>sources</b> folder</li>
  <ul>
    <li><b>01 - I promessi sposi</b> subfolder</li>
    <ul>
      <li><b>01 - Introduzione</b> subfolder</li>
      <ul>
        <li><b>1.txt</b> file</li>
        <li><b>2.txt</b> file</li>
      </ul>
      <li><b>02 - Capitolo 1</b> subfolder</li>
      <ul>
        <li><b>3.txt</b> file</li>
        <li><b>4.txt</b> file</li>
      </ul>
    </ul>
  </ul>
</ul>

Each .txt file is returned at each client request by means of PHP file getOpera.php. It returns a JSON file containing all the necessary information to build a works list, from which to access each individual chapter from the user-side. 
Afterwards, the getFilesS.php file is required to visualise the plain text of the chapters of each work on the user-side.

Since also a login functionality is provided, the data regarding users' login and sign up are stored in a JSON file. 
Specifically, the files necessary to perform the login, logout, sign up, change password operations are respectively: login.php, logout.ph, register.php and changePwd.php.


## Front-end
The web application interface has been developed in HTML, CSS, JavaScript, and jQuery (version 2.1.1) and Twitter Bootstrap (version 3.3.1) frameworks. The icons present belong to the Twitter Bootstrap library (version 3.3.1).

On the user side, the available literary works can be visualised in three different modes, as follows.

### Reading mode
The works are loaded in such a mode by means of getFilesS.php file. 
In this mode, it is possible to visualise only a single edition of a chapter of an available work. For instance, Introduzione of I promessi Sposi, 1827 edition.

### Variants mode
The works are loaded in such a mode by means of getFilesS.php file. 
In this mode, through a word-based diffing algorithm in JavaScript, WikiEd diff, the two editions of the same chapter are compared, and as output the text of the older edition is returned with the overlapping variants of the most recent edition. The variants appear within the text of the older edition in span HTML tags. The style is applied directly by CSS from the main.css file. 
Variants can be visualised in line spacing or in text. The corresponding functions are contained in the main.js file.

### Laboratory mode
The Laboratory provides, after authentication on the platform, the possibility of assigning the proposed categories to the variants detected by the system. These categories are set in the style.json file, which has to be placed in a folder called "metadata", created for each work and inserted in each parent folder. In this way, it is possible to create different categories for each work. The color assigned to each category is always set within the file at hand (style.json).

To mark the text up, the user must highlight the text of the object edition and click the corresponding button on the left bar. Thus, the highlighted text will take on the color of the assigned category.
Only authorised users can save the annotations made in this way. There are two ways of saving: Save (which overwrites the document) and Save as new which creates a new copy of the file. The file necessary for such operations is save.php.

The marked file can also be shared with other users via the coowner.php file.

Still regarding the marked file, it is possible to view the relative statistics (numerical classification and pie charts). These statics are calculated from the getStats.php file.
