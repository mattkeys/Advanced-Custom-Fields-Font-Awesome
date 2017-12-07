=== Advanced Custom Fields: Font Awesome Field ===
Contributors: mattkeys
Tags: Advanced Custom Fields, ACF, Font Awesome, FontAwesome
Requires at least: 3.5
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a new 'Font Awesome Icon' field to the popular Advanced Custom Fields plugin.

== Description ==

Add a [Font Awesome](http://fontawesome.io/) icon field type to Advanced Custom Fields.

* Optionally set a default icon
* Returns Icon Element, Icon Class, Icon Unicode, or an Object including the element, class, and unicode value
* Optionally enqueues Font Awesome in footer where needed (when a font awesome field is being used on the page)
* Integrates with [jsDelivr](https://www.jsdelivr.com/) to automatically load the latest version of [Font Awesome](http://fontawesome.io/)
* Includes filters to override the which version of FontAwesome is loaded (See Optional Configuration)

Note: It is recommended to let this plugin enqueue the latest version of Font Awesome on your front-end; or include the latest version by some other means; so that available icons in the admin area will be displayed properly on your sites front-end.

= Compatibility =

This ACF field type is compatible with:
* ACF 5
* ACF 4

== Installation ==

1. Copy the `advanced-custom-fields-font-awesome` folder into your `wp-content/plugins` folder
2. Activate the Font Awesome plugin via the plugins admin page
3. Create a new field via ACF and select the Font Awesome type

== Optional Configuration ==

=== Filters ===

* **ACFFA_always_enqueue_fa**: Return true to always enqueue FontAwesome on the frontend, even if no ACF FontAwesome fields are in use on the page. This will enqueue FontAwesome in the header instead of the footer.
* **ACFFA_override_version**: Use to override the default FontAwesome icon version (latest). Return any valid version number from [jsDelivr](https://www.jsdelivr.com/projects/fontawesome)
* **ACFFA_admin_enqueue_fa**: Return false to stop enqueueing FontAwesome in the admin area. Useful if you already have FontAwesome enqueued by some other means.
* **ACFFA_load_chosen**: Return false to stop loading the [Chosen JS](https://harvesthq.github.io/chosen/) library in the admin area. Used in v4 of ACF only.
* **ACFFA_get_icons**: Filter the array of icons and icon details loaded from the database
* **ACFFA_get_fa_url**: Filter the URL used for enqueuing FontAwesome in the frontend and admin areas of the site.

== Screenshots ==

1. Set a default icon, and choose how you want icon data to be returned.
2. Searchable list of all icons, including large live preview

== Changelog ==

= 2.1.0 =
* Preventing any automatic updates to version 5.x of FontAwesome that could break plugin functionality and result in broken icons on sites currently using 4.x FontAwesome icons.

= 2.0.9 =
* Fixed bug effecting null value fields created in 1.x of this plugin would return a string of 'null' instead of boolean false when used in 2.x versions of this plugin.

= 2.0.8 =
* Fixed bug where fields marked to 'allow null' in acf v5 did not show the (x) to remove the selected option on the field

= 2.0.7 =
* Fixed bug with FA fields assigned to menu items + further refactoring of JS for ACF v5

= 2.0.6 =
* Refactored JS to simplify codebase and fix bugs where Chosen or Select2 fields would not initialize in a variety of field/sub-field configurations and display options.

= 2.0.5 =
* Fixed bug with ACF tabs + FontAwesome fields where Chosen/Select2 would not initialize beyond the first tab shown

= 2.0.4 =
* Added filter (ACFFA_always_enqueue_fa) to allow FontAwesome to always be enqueued on your sites frontend, even if no ACF FontAwesome fields are in use on the page.

= 2.0.3 =
* Fixed bug where a field set to return 'Icon Object' would instead return an array

= 2.0.2 =
* Fixed bug effecting Select fields when used along with a Font Awesome field in a repeater (ACF v5)

= 2.0.1 =
* Fixed bug causing incompatibilities with ACF Clone fields (ACF v5)
* Fixed bug where default icons could not be unselected when creating FontAwesome fields (ACF v5)

= 2.0.0 =
* Total rewrite of plugin to simplify codebase and better adhere to WordPress and Advanced Custom Fields coding standards and best practices
* Added option to disable the larger icon preview displayed with the FontAwesome select fields

= 1.7.4 =
* Fixed incompatibilities with latest ACF 5 + Select2 v4.x

= 1.7.3 =
* Updated Better Font Awesome library to latest version
* Updated 'fallback' ACF font to 4.6.3
* Bugfix overly broad CSS rules effecting non font awesome field groups
* Bugfix select2 not loading on new fontawesome icons until after saving fields

= 1.7.2 =
* Bugfix PHP Notice when trying to access property of 'null' value

= 1.7.1 =
* Updated Better Font Awesome Library for better compatibility with Better Font Awesome plugin
* Changed ACF 4/5 detection method for better integration with Better Font Awesome plugin
* Bugfix wrong preview icon appearing in ACF custom field creator area

= 1.7 =
* Added ability to select no icon by default
* Better handling of 'null' or 'no selection' items
* Fixed bug where default icon would not display in admin area if 'unicode' return type was selected 

= 1.6.4 =
* Misc JS performance improvements
* Fixed bug where select2 would not initialize on repeater items added mid-rows (using the plus icon at the end of a repeater row)

= 1.6.3 =
* Fixed asset path errors when including this add-on from a theme instead of the plugins directory

= 1.6.2 =
* Rolling back changes from 1.6.1 after a number of bugs were reported. Incompatibility issues with Better Font Awesome have been corrected in that plugins code.

= 1.6.1 =
* Addressing incompatibility issues between this plugin and the Better Font Awesome plugin

= 1.6 =
* Misc fixes to JS to properly target ACF fields in the DOM (based on changes to the ACF structure). This should resolve issues with duplicate, or missing select2 fields when picking font awesome icons.

= 1.5 =
* New Feature: Integrated with the [Better Font Awesome Library](https://github.com/MickeyKay/better-font-awesome-library) to automatically use the latest version of the Font Awesome icons

= 1.4 =
* Updated included FontAwesome to version 4.2

= 1.3 =
* Added support for ACF version 5.x

= 1.2 =
* Added support for new icons in FontAwesome 4.1
* Updated included FontAwesome to version 4.1

= 1.1.2 =
* Fixed overly specific JS selector which was causing font preview icons to not load when used on taxonomy term pages

= 1.1.1 =
* Fixed JS error which was breaking conditional field select boxes

= 1.1.0 =
* Added support for use in repeater fields
* Added support for use in flexible content fields
* Added live icon preview to field creation screen
* Fixed various bugs with Select2 initialization on dynamically added fields

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==

= 2.1.0 =
* Preventing any automatic updates to version 5.x of FontAwesome that could break plugin functionality and result in broken icons on sites currently using 4.x FontAwesome icons.

= 2.0.9 =
* Fixed bug effecting null value fields created in 1.x of this plugin would return a string of 'null' instead of boolean false when used in 2.x versions of this plugin.

= 2.0.8 =
* Fixed bug where fields marked to 'allow null' in acf v5 did not show the (x) to remove the selected option on the field

= 2.0.7 =
* Fixed bug with FA fields assigned to menu items + further refactoring of JS for ACF v5

= 2.0.6 =
* Refactored JS to simplify codebase and fix bugs where Chosen or Select2 fields would not initialize in a variety of field/sub-field configurations and display options.

= 2.0.5 =
* Fixed bug with ACF tabs + FontAwesome fields where Chosen/Select2 would not initialize beyond the first tab shown

= 2.0.4 =
* Added filter (ACFFA_always_enqueue_fa) to allow FontAwesome to always be enqueued on your sites frontend, even if no ACF FontAwesome fields are in use on the page.

= 2.0.3 =
* Fixed bug where a field set to return 'Icon Object' would instead return an array

= 2.0.2 =
* Fixed bug effecting Select fields when used along with a Font Awesome field in a repeater (ACF v5)

= 2.0.1 =
* Fixed bug causing incompatibilities with ACF Clone fields (ACF v5)
* Fixed bug where default icons could not be unselected when creating FontAwesome fields (ACF v5)

= 2.0.0 =
* Total rewrite of plugin to simplify codebase and better adhere to WordPress and Advanced Custom Fields coding standards and best practices
* Added option to disable the larger icon preview displayed with the FontAwesome select fields
