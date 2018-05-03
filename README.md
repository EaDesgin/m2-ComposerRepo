# Magento 2 Composer Repository 

# Credits

- We got inspired by https://github.com/Genmato.

# Composer  Repository for Magento 2
 
- This extension works as a Magento 2 private storage manager for extension developers who sell Magento 2 extensions and want to provide easy installation of the composer. Extension was originally implemented for Magento 1 by Genmato, but we adapted the code and made it available to Magento 2. The logic is very similar to the M1 module but we actually re created the extension.

# Requirements:
 
 - Magento 2.x
 - Composer
 - Satis
 
# How does it work

The extension  is using Satis, composer.json and packages (stored on the server). For each package ordered through the web shop it checks if the ordered products are M2 packages (based on product_id) and inserts this into the customer_packages table so that the user will have access to the purchased package. If there is no customer_auth key available one will be generated.

Here's how the extension works in the 3 steps outlined below:

 1. With the auth key and secret the customer can install the package from the command file with Composer. For this Composer will request the packages.json from the repository. Based on the used key and secret the customer_id is checked for available packages and the output is built.
 
 2. When a download is requested it requests /composer/download/file/ with the parameters (m/[package_name]/h/[package_hash]/v/[normalized_version, again based on the used key and secret access to the file and version is checked, if allowed file is send from the [satis archive directory] to the user.
 
 3. Thru the notify-batch URL the installation of a package is recorded for the user (stores IP, Package, Version and User ID).

# Configuration:
 
 **Satis installation**

Install Satis outside of Magento Webroot, but accessible from the Magento web user. To install it, run the following command in your console:

`php bin/magento eadesign:composerrepo`

_[path] → The place where you want to install the Satis directory._

**NOTE !!!**

The Satis installation folder should not be accessible from the web. It is only used to collect composer's json files and to generate downloaded files.

# Repository Configuration

After installation of the extension go to: Stores => Configuration => EADESIGN SETTINGS  =>  Composer Repo  and complete the following fields:

**[Configuration]**

- Repository name: This name is used for the composer config repositories (for example: Eadesign's repositories),

- Repository URL: The url that serves the Repository (for example: _[  your domain ]/composer/packagist/index_ ),
- Include dev-master: Optionally enable the option to allow access to the dev-master package (disabled by default),
- Update period: The period in months that user can get free updates to a newer release, when the period ends the user only has access to the versions release before. Not possible when dev-master is enabled, leave empty for unlimited updates,
- Email Active: Yes or No.(By enabling this option, you can set when someone purchases a product from your store, also send installation instructions to the composer using the generated keys).

**[Satis Configuration]**

- Satis command path: Path to the Satis executable (for example:  _[ your project ]/satis/bin/satis_ ),

- Satis config path: Path to the satis.json configuration file (for example: _[ your project ]/satis/satis.json_ ),
- Name: Repository name (used for satis.json),
- Homepage URL: Repository URL (used for satis.json, example: _[ your domain ]/composer/packagist/index_ ),
- Output directory: Path the the Satis web directory (for example:  _[ your project]/satis/web_ ),

**[Satis Archive]**

- Format: Export format (zip or tar) of the packages (for example: zip)

- Absolute Directory: Path to where the downloaded packages should be places (for example: _[your project]/satis/packages/_)

# M2 Package configuration:

The Magento 2 package/extensions should be stored in a repository, it is important that the account where Satis is running from has access to download from this repository.

# Adding M2 packages:

To add a Magento 2 package go to: Eadesign =>  Composer Repository => Packages => Add new Package

**[Package Information]**

- Package Name: composer package name (for example: eadesign/composerrepo),

- Status: Enabled,
- Package Type: Normal / Bundled (Bundled packages will always be available in the packages.json, this can be useful for required library packages.),
- Package Title: Used to describe the package in the customer menu,
- Magento Product ID: Matching product entity_id for the ordered item,
- Repository URL: Git URL to the repository (for example: git@github.com:EaDesgin/m2-ComposerRepo.git)
- Repository options: json format of options available for the repository in the satis.json (see https://getcomposer.org/doc/articles/handling-private-packages-with-satis.md for details).

# Building the repository data

When the configuration and packages are ready the configration can be build with:

`php bin/magento eadesign:composerrepo`

This command can also be scheduled to run daily (or any frequency you prefer) and automatically update the repository data.

# Todo's:

- add email template;
- add statistics;
- code improvement.
