=== Azz Anonim Posting ===
Contributors: azzrael
Tags: post
Requires at least: 3.0
Stable tag: 1.0.0
Tested up to: 3.9
License: GPLv2 or later

Allows your users to add posts with images by anonymous, without any registration and authorization.

== Description ==

Allows your users to add posts with images by anonymous, without any registration and authorization.
From any page of your blog, where you place the form.

* You can disable image upload option at plugin options page.
* You can set your own template for generating post. Simply from options page.

== Installation ==

1. Upload the Azz Anonim Posting plugin directory to your plugins folder and activate it.
2. Then place `<?php if (function_exists('azzap_form')) {azzap_form();} ?>` at any page of your theme.
3. Check: `Options > Anonim Posting` for any options of the plugin.
4. Style Form by edit style.css in plugin folder.

== Screenshots ==

1. Main form.
2. Admin options.

== Thanks ==
Uses [jQuery File Upload](http://blueimp.github.io/jQuery-File-Upload/ "jQuery File Upload") by Sebastian Tschan for image transfer.